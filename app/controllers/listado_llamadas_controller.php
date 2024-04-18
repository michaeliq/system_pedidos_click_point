<?php

class ListadoLlamadasController extends AppController {

    var $name = 'ListadoLlamadas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('ListadoLlamada', 'ListadoLlamadasDetalle', 'Cotizacion', 'CotizacionDetalle',
        'Departamento', 'Municipio', 'PlantillasDetalle', 'Producto', 'BdCliente');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('ListadoLlamadas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('ListadoLlamadas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return true;
        }
    }

    function gestion_llamadas() {

        $conditions = array();
        $where = "+ListadoLlamadasDetalle+.+fecha_inicio+::date BETWEEN now()::date - interval '45' day AND now()::date AND +ListadoLlamadasDetalle+.+user_id+ = " . $this->Session->read('Auth.User.id') . "";
        $where = str_replace('+', '"', $where);
        array_push($conditions, $where);

        if (!empty($this->data)) {
            if (!empty($this->data['ListadoLlamada']['bd_razon_social'])) {
                $where = "+BdCliente+.+bd_razon_social+ ILIKE '%" . $this->data['ListadoLlamada']['bd_razon_social'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['ListadoLlamada']['bd_email'])) {
                $where = "+BdCliente+.+bd_email+ ILIKE '%" . $this->data['ListadoLlamada']['bd_email'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['ListadoLlamada']['bd_telefonos'])) {
                $where = "+BdCliente+.+bd_telefonos+ ILIKE '%" . $this->data['ListadoLlamada']['bd_telefonos'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['ListadoLlamada']['fecha_inicio'])) {
                $where = "+ListadoLlamadasDetalle+.+fecha_inicio+::date = '" . $this->data['ListadoLlamada']['fecha_inicio'] . "'::date";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        //$this->ListadoLlamadasDetalle->recursive = 0;
        // $this->paginate = array('limit' => 80, 'order' => array('ListadoLlamadasDetalle.fecha_inicio, ListadoLlamadasDetalle.estado_llamada, ListadoLlamadasDetalle.listado_llamada_id' => 'desc'));

        $detalle_llamada = $this->ListadoLlamadasDetalle->find('all', array('conditions' => $conditions, 'order' => 'fecha_inicio DESC'));
        $this->set('listadoLlamadas', $detalle_llamada);
    }

    function index() {

        Configure::write('debug', 2);
        /*
          TRUNCATE inv_tmp;
          SELECT 'UPDATE inv_tmp SET producto_id = '||id||' WHERE codigo_producto = ";'||codigo_producto||'";' FROM productos WHERE codigo_producto IN (SELECT codigo_producto FROM inv_tmp WHERE procesado = false);

         */

        $plantilla_id = 26;
        $plantilla_base = 50;
        $sql_plantilla = "UPDATE plantillas SET plantilla_base = FALSE, plantilla_base_id = " . $plantilla_base . " WHERE id = " . $plantilla_id . ";";
        // $this->ListadoLlamada->query($sql_plantilla);

        $sql_pd = "UPDATE plantillas_detalles
        SET precio_producto = tmp.precio_producto, precio_producto_2 = tmp.precio_producto, fecha_actualizacion_timestamp = now(), plantilla_base = " . $plantilla_base . "
        FROM inv_tmp as tmp 
        WHERE  tmp.producto_id = plantillas_detalles.producto_id
        AND plantillas_detalles.plantilla_id = " . $plantilla_id . ";

        DELETE FROM plantillas_detalles WHERE fecha_actualizacion_timestamp is null AND plantillas_detalles.plantilla_id = " . $plantilla_id . ";";
        // $this->ListadoLlamada->query($sql_pd);

        $sql_pd = "DELETE FROM plantillas_detalles WHERE plantilla_id = " . $plantilla_id;
        // $this->ListadoLlamada->query($sql_pd);

        $sql_detalles = "INSERT INTO plantillas_detalles (plantilla_id, producto_id, fecha_creacion, precio_producto, iva_producto, medida_producto, tipo_categoria_id, fecha_creacion_timestamp, precio_producto_2, plantilla_base)
                        SELECT " . $plantilla_id . ", producto_id, NOW(), precio_producto, iva_producto, medida_producto, tipo_categoria_id, NOW(), precio_producto_2, " . $plantilla_base . "
                        FROM plantillas_detalles
                        WHERE plantilla_id = " . $plantilla_base . ";";
        // $this->ListadoLlamada->query($sql_detalles);

        /*

          CREATE TABLE plantillas
          (
          id serial NOT NULL,
          nombre_plantilla character varying(60) NOT NULL,
          estado_plantilla boolean NOT NULL DEFAULT true,
          fecha_creacion timestamp without time zone NOT NULL DEFAULT now(),
          detalle_plantilla text,
          tipo_pedido_id integer,
          empresa_id integer NOT NULL,
          plantilla_base boolean NOT NULL DEFAULT false,
          plantilla_base_id integer,
          sucursal_id integer,

          CREATE TABLE public.plantillas_detalles
          (
          id integer NOT NULL DEFAULT nextval('plantillas_detalles_id_seq'::regclass),
          plantilla_id integer NOT NULL,
          producto_id integer NOT NULL,
          fecha_creacion time without time zone NOT NULL DEFAULT now(),
          precio_producto integer DEFAULT 0,
          iva_producto double precision,
          medida_producto character varying(10),
          tipo_categoria_id integer,
          fecha_creacion_timestamp timestamp without time zone DEFAULT now(),
          fecha_actualizacion_timestamp timestamp without time zone,
          precio_producto_2 integer DEFAULT 0,
          plantilla_base integer,
         *  
         */


        $llamadas_dia = 60;
        $conditions = array();
        $where = "+ListadoLlamada+.+fecha_registro+::date BETWEEN now()::date AND now()::date AND +ListadoLlamada+.+user_id+ = " . $this->Session->read('Auth.User.id') . "";
        $where = str_replace('+', '"', $where);
        array_push($conditions, $where);

        $llamadas_asignadas = $this->ListadoLlamada->find('all', array('fields' => array('id'), 'conditions' => $conditions));

        if (count($llamadas_asignadas) < 60) {
            /* $query_llamadas = "INSERT INTO listado_llamadas (bd_cliente_id, fecha_registro, user_id)
              (SELECT id, '" . date('Y-m-d H:i:s') . "', " . $this->Session->read('Auth.User.id') . " FROM bd_clientes
              WHERE id NOT IN (SELECT bd_cliente_id
              FROM listado_llamadas
              )
              ORDER BY RANDOM()
              LIMIT " . $llamadas_dia . ");"; //WHERE fecha_registro::date = now()::date
              // echo $query_llamadas; */
            $query_llamadas1 = "INSERT INTO listado_llamadas (bd_cliente_id, fecha_registro, user_id)
                    (SELECT id, '" . date('Y-m-d H:i:s') . "', " . $this->Session->read('Auth.User.id') . " FROM bd_clientes
                    WHERE id IN (SELECT bd_cliente_id 
                                    FROM listado_llamadas
                                    WHERE estado_llamada = false
                                    )
                    ORDER BY RANDOM()
                    LIMIT " . $llamadas_dia . ");";
            // 04/12/2017
            // Llamar a los clientes que nunca contestaron 
            $query_llamadas2 = "INSERT INTO listado_llamadas (bd_cliente_id, fecha_registro, user_id)
                    (SELECT id, '" . date('Y-m-d H:i:s') . "', " . $this->Session->read('Auth.User.id') . " FROM bd_clientes
                    WHERE id IN (SELECT listado_llamadas.bd_cliente_id
                                FROM listado_llamadas, listado_llamadas_detalles
                                WHERE listado_llamadas.id =  listado_llamadas_detalles.listado_llamada_id
                                AND listado_llamadas_detalles.detalle_encuesta::json->>'detalle_encuesta' = '1')
                    ORDER BY RANDOM()
                    LIMIT " . $llamadas_dia . ");";
            $this->ListadoLlamada->query($query_llamadas2);
        }

        $this->ListadoLlamada->recursive = 0;
        $this->paginate = array('limit' => 80, 'order' => array('ListadoLlamada.fecha_registro, ListadoLlamada.estado_llamada, ListadoLlamada.id' => 'desc'));
        $this->set('listadoLlamadas', $this->paginate($conditions));
    }

    function iniciar_llamada($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Id incorrecto', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('listado_llamada_id', $id);

        $detalle_llamada = $this->ListadoLlamadasDetalle->find('first', array('fields' => array('id', 'fecha_inicio', 'fecha_fin', 'duracion', 'duracion_actual'), 'conditions' => array('ListadoLlamadasDetalle.listado_llamada_id' => base64_decode($id))));
        // echo $detalle_llamada['ListadoLlamadasDetalle']['id'];
        if ($detalle_llamada['ListadoLlamadasDetalle']['id'] > 0) {
            $this->set('listadoLlamadaDetalle', $detalle_llamada);
            $this->set('listadoLlamada', $this->ListadoLlamada->read(null, base64_decode($id)));
        } else {
            $this->ListadoLlamadasDetalle->create();
            $this->data['ListadoLlamadasDetalle']['listado_llamada_id'] = base64_decode($id);
            $this->data['ListadoLlamadasDetalle']['user_id'] = $this->Session->read('Auth.User.id');
            $this->data['ListadoLlamadasDetalle']['fecha_inicio'] = date('Y-m-d H:i:s');
            $this->data['ListadoLlamadasDetalle']['fecha_fin'] = date('Y-m-d H:i:s');
            if ($this->ListadoLlamadasDetalle->save($this->data)) {
                $this->set('listadoLlamada', $this->ListadoLlamada->read(null, base64_decode($id)));
            }
        }
        $cotizacion = $this->Cotizacion->find('all', array('fields' => 'id,listado_llamada_id,cotizacion_estado_pedido', 'conditions' => array('Cotizacion.listado_llamada_id' => base64_decode($id))));
        $detalle_encuesta = array('1' => 'No contestó',
            '2' => 'No está interesado',
            '3' => 'Llamar luego (Agendar)',
            '4' => 'Realizar visita',
            '5' => 'Cotizar inmediatamente',
            '6' => 'Cotizar cuando cliente envíe información',
            '7' => 'No volver a llamar',
            '8' => 'Empresa inexistente',
            '9' => 'Datos Erroneos');

        $hora_probable = array('07:00' => '07:00',
            '07:30' => '07:30',
            '08:00' => '08:00',
            '08:30' => '08:30',
            '09:00' => '09:00',
            '09:30' => '09:30',
            '10:00' => '10:00',
            '10:30' => '10:30',
            '11:00' => '11:00',
            '11:30' => '11:30',
            '12:00' => '12:00',
            '12:30' => '12:30',
            '13:00' => '13:00',
            '13:30' => '13:30',
            '14:00' => '14:00',
            '14:30' => '14:30',
            '15:00' => '15:00',
            '15:30' => '15:30',
            '16:00' => '16:00',
            '16:30' => '16:30',
            '17:00' => '17:00',
            '17:30' => '17:30',
            '18:00' => '18:00',
            '18:30' => '18:30',
            '19:00' => '19:00');

        $this->set('cotizacion', $cotizacion);
        $this->set('detalle_encuesta', $detalle_encuesta);
        $this->set('hora_probable', $hora_probable);

        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $this->set(compact('departamentos', 'municipios'));
    }

    function cotizacion($id = null) {
        $listadoLlamada = $this->ListadoLlamada->find('first', array('conditions' => array('ListadoLlamada.id' => base64_decode($id))));
        $this->set('listadoLlamada', $listadoLlamada);
        if (!empty($this->data)) {
            $this->data['Cotizacion']['listado_llamada_id'] = base64_decode($this->data['Cotizacion']['listado_llamada_id']);
            $this->data['Cotizacion']['bd_cliente_id'] = base64_decode($this->data['Cotizacion']['bd_cliente_id']);
            $this->data['Cotizacion']['fecha_cotizacion'] = date('Y-m-d H:i:s');
            $this->data['Cotizacion']['cotizacion_estado_pedido'] = '1'; // En proceso
            $this->Cotizacion->create();
            if ($this->Cotizacion->save($this->data)) {
                $this->Session->write('Cotizacion.cotizacion_id', $this->Cotizacion->getInsertID());
                $this->Session->write('Cotizacion.listado_llamada_id', $this->data['Cotizacion']['listado_llamada_id']);
                // $this->Session->write('Pedido.tipo_pedido_id', $this->data['Pedido']['tipo_pedido_id']);
                //$this->Session->write('Pedido.sucursal_id', $this->data['Pedido']['sucursal_id']); // nueva
                $this->Session->setFlash(__('Se ha realizado la orden de cotización. Por favor relacione los productos a cotizar. Nuevo estado: En Proceso.', true));
                $this->redirect(array('action' => 'cotizacion_detalle'));
            } else {
                $this->Session->setFlash(__('La cotización no se ha realizado. Por favor intente de nuevo.', true));
            }
        }
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $tipoPedidos = $this->Cotizacion->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.id' => '1')));
        $this->set(compact('tipoPedidos', 'departamentos', 'municipios'));
    }

    function cotizacion_detalle($id = null) {
        if (!empty($this->data)) {
            $this->CotizacionDetalle->create();
            $aux = explode('-', $this->data['CotizacionDetalle']['producto_id2']);
            $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.codigo_producto' => trim($aux[0]))));
            if (count($aux) > 0) {
                $this->data['CotizacionDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                $this->data['CotizacionDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                $this->data['CotizacionDetalle']['precio_producto'] = $aux[0]['Producto']['precio_producto'];
                $this->data['CotizacionDetalle']['iva_producto'] = $aux[0]['Producto']['iva_producto'];
                $this->data['CotizacionDetalle']['medida_producto'] = $aux[0]['Producto']['medida_producto'];
                $this->data['CotizacionDetalle']['cotizacion_id'] = $this->Session->read('Cotizacion.cotizacion_id');
                $this->data['CotizacionDetalle']['fecha_cotizacion_detalle'] = date('Y-m-d H:i:s');

                if ($this->CotizacionDetalle->save($this->data)) {

                    // Actualizar observaciones cotizacion
                    if (!empty($this->data['CotizacionDetalle']['observaciones'])) {
                        $update_observacion = "UPDATE cotizacion SET observaciones = concat(observaciones,'" . $aux[0]['Producto']['codigo_producto'] . " - " . $this->data['CotizacionDetalle']['observaciones'] . "<br>') WHERE id=" . $this->Session->read('Cotizacion.cotizacion_id') . "";
                        $this->Cotizacion->query($update_observacion);
                    }
                    $this->redirect(array('action' => 'cotizacion_detalle'));
                } else {
                    $this->Session->setFlash(__('Por favor verifique los datos ingresados para el producto.', true));
                }
            }
        }

        if (!empty($id)) {
            $cotizacion = $this->Cotizacion->find('all', array('fields' => 'id,listado_llamada_id', 'conditions' => array('Cotizacion.id' => $id)));
            $this->Session->write('Cotizacion.cotizacion_id', $id);
            $this->Session->write('Cotizacion.listado_llamada_id', $cotizacion[0]['Cotizacion']['listado_llamada_id']);
            $this->redirect(array('action' => 'cotizacion_detalle'));
        }

        $this->set('detalles', $this->CotizacionDetalle->find('all', array('order' => 'CotizacionDetalle.producto_id', 'conditions' => array('CotizacionDetalle.cotizacion_id' => $this->Session->read('Cotizacion.cotizacion_id')))));
        $this->set('productos', $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'tipo_categoria_id' => array('1', '2', '3', '4', '5')))));
    }

    function cancelar_cotizacion() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['CotizacionDetalleId'])) {
                if ($this->Cotizacion->updateAll(array("Cotizacion.cotizacion_estado" => 'false', "Cotizacion.cotizacion_estado_pedido" => '2'), array("Cotizacion.id" => $_REQUEST['CotizacionDetalleId']))) {

                    echo true;
                    $this->Session->setFlash(__('La cotización se ha cancelado exitosamente. Por favor termine la llamada.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró cancelar la cotización exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }
        exit;
    }

    function terminar_cotizacion() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['CotizacionDetalleId'])) {
                if ($this->Cotizacion->updateAll(array("Cotizacion.cotizacion_estado" => 'true', "Cotizacion.cotizacion_estado_pedido" => '3'), array("Cotizacion.id" => $_REQUEST['CotizacionDetalleId']))) {
                    echo true;
                    $this->Session->setFlash(__('La cotización se ha terminado exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró terminar la cotización exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }
        exit;
    }

    function modificar_cotizacion() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['CotizacionDetalleId']) && !empty($_REQUEST['CotizacionDetalleCantidadPedido'])) {
                $this->CotizacionDetalle->id = $_REQUEST['CotizacionDetalleId'];
                $this->CotizacionDetalle->cantidad_pedido = $_REQUEST['CotizacionDetalleCantidadPedido'];

                if ($this->CotizacionDetalle->saveField('cantidad_pedido', $this->CotizacionDetalle->cantidad_pedido)) {
                    echo true;
                    $this->Session->setFlash(__('El producto se ha modificado exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró modificar el producto de la cotizacion. Por favor intente de nuevo.', true));
                }
                exit;
            }
        }
    }

    function quitar_cotizacion() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['CotizacionDetalleId'])) {
                $this->CotizacionDetalle->id = $_REQUEST['CotizacionDetalleId'];
                if ($this->CotizacionDetalle->delete()) {
                    echo true;
                    $this->Session->setFlash(__('El producto se ha quitado de la cotización exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró quitar el producto de la cotizacion. Por favor intente de nuevo.', true));
                }
                exit;
            }
        }
    }

    function cotizacion_pdf($id = null) {
        Configure::write('debug', 0);
        $this->layout = 'pdf';

        $detalles = $this->CotizacionDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Cotizacion.cotizacion_estado' => true, 'CotizacionDetalle.cotizacion_id' => $id)));
        $llamada = $this->ListadoLlamada->find('all', array('conditions' => array('ListadoLlamada.id' => $detalles[0]['Cotizacion']['listado_llamada_id'])));

        $this->set('detalles', $detalles);
        $this->set('llamada', $llamada);
    }

    function terminar_llamada() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['ListadoLlamadaId'])) {
                if ($this->ListadoLlamada->updateAll(array("ListadoLlamada.estado_llamada" => 'true'), array("ListadoLlamada.id" => base64_decode($_REQUEST['ListadoLlamadaId'])))) {
                    $detalle_encuesta = array('detalle_encuesta' => (!empty($_REQUEST['detalle_encuesta']) ? $_REQUEST['detalle_encuesta'] : ''),
                        'departamento_id' => $_REQUEST['departamento_id'],
                        'municipio_id' => $_REQUEST['municipio_id'],
                        'cliente_email' => $_REQUEST['cliente_email'],
                        'cliente_direccion' => $_REQUEST['cliente_direccion'],
                        'cliente_telefono' => $_REQUEST['cliente_telefono'],
                    );

                    $this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.detalle_encuesta" => "'" . json_encode($detalle_encuesta) . "'", "ListadoLlamadasDetalle.bd_cliente_id" => $_REQUEST['cliente_id'], "ListadoLlamadasDetalle.estado_llamada" => 'true', "ListadoLlamadasDetalle.fecha_fin" => "'" . date('Y-m-d H:i:s') . "'"), array("ListadoLlamadasDetalle.listado_llamada_id" => base64_decode($_REQUEST['ListadoLlamadaId'])));

                    if ($_REQUEST['detalle_encuesta'] == '6') {
                        $this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.click_cotizacion" => "true"), array("ListadoLlamadasDetalle.listado_llamada_id" => base64_decode($_REQUEST['ListadoLlamadaId'])));
                    }

                    if (!empty($_REQUEST['cliente_email']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_email" => "'" . $_REQUEST['cliente_email'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['cliente_direccion']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_direccion" => "'" . $_REQUEST['cliente_direccion'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['cliente_telefono']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_telefonos" => "'" . $_REQUEST['cliente_telefono'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if ((!empty($_REQUEST['departamento_id']) || !empty($_REQUEST['municipio_id'])) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_departamento_id" => $_REQUEST['departamento_id'], "bd_municipio_id" => $_REQUEST['municipio_id']), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['nombre_contacto']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_nombre_contacto" => "'" . $_REQUEST['nombre_contacto'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['observaciones']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_observaciones" => "'" . $_REQUEST['observaciones'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    echo true;
                    $this->Session->setFlash(__('La llamada se ha terminado exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró terminar la llamada exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }
        exit;
    }

    function agendar_llamada() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['ListadoLlamadaId'])) {
                if ($this->ListadoLlamada->updateAll(array("ListadoLlamada.estado_llamada" => 'true'), array("ListadoLlamada.id" => base64_decode($_REQUEST['ListadoLlamadaId'])))) {
                    $detalle_encuesta = array('detalle_encuesta' => $_REQUEST['detalle_encuesta'],
                        'departamento_id' => $_REQUEST['departamento_id'],
                        'municipio_id' => $_REQUEST['municipio_id'],
                        'cliente_email' => $_REQUEST['cliente_email'],
                        'cliente_direccion' => $_REQUEST['cliente_direccion'],
                        'cliente_telefono' => $_REQUEST['cliente_telefono'],
                        'cliente_id' => $_REQUEST['cliente_id'],
                    );

                    $this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.detalle_encuesta" => "'" . json_encode($detalle_encuesta) . "'", "ListadoLlamadasDetalle.bd_cliente_id" => $_REQUEST['cliente_id'], "ListadoLlamadasDetalle.click_agendar" => "true", "ListadoLlamadasDetalle.estado_llamada" => 'true', "ListadoLlamadasDetalle.fecha_fin" => "'" . date('Y-m-d H:i:s') . "'"), array("ListadoLlamadasDetalle.listado_llamada_id" => base64_decode($_REQUEST['ListadoLlamadaId'])));

                    if (!empty($_REQUEST['cliente_email']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_email" => "'" . $_REQUEST['cliente_email'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['cliente_direccion']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_direccion" => "'" . $_REQUEST['cliente_direccion'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['cliente_telefono']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_telefonos" => "'" . $_REQUEST['cliente_telefono'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if ((!empty($_REQUEST['departamento_id']) || !empty($_REQUEST['municipio_id'])) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_departamento_id" => $_REQUEST['departamento_id'], "bd_municipio_id" => $_REQUEST['municipio_id']), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['nombre_contacto']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_nombre_contacto" => "'" . $_REQUEST['nombre_contacto'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }
                    if (!empty($_REQUEST['observaciones']) && !empty($_REQUEST['cliente_id'])) {
                        $this->BdCliente->updateAll(array("BdCliente.bd_observaciones" => "'" . $_REQUEST['observaciones'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                    }


                    // Agendar nueva llamada 
                    $this->ListadoLlamada->create();
                    $this->data['ListadoLlamada']['bd_cliente_id'] = $_REQUEST['cliente_id'];
                    $this->data['ListadoLlamada']['fecha_registro'] = $_REQUEST['fecha_probable'] . ' ' . $_REQUEST['hora_probable'];
                    $this->data['ListadoLlamada']['user_id'] = $this->Session->read('Auth.User.id');
                    $this->data['ListadoLlamada']['agendada'] = true;
                    if ($this->ListadoLlamada->save($this->data)) {
                        $this->Session->setFlash(__('La llamada ha sido agendada para el ' . $_REQUEST['fecha_probable'] . ' ' . $_REQUEST['hora_probable'], true));
                    } else {
                        $this->Session->setFlash(__('La llamada no se logró agendar. Por favor intente de nuevo.', true));
                    }
                    echo true;
                    // $this->Session->setFlash(__('La llamada se ha terminado exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró terminar la llamada exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }
        exit;
    }

    function actualizar_cliente() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['ListadoLlamadaId'])) {
                $detalle_encuesta = array('departamento_id' => $_REQUEST['departamento_id'],
                    'municipio_id' => $_REQUEST['municipio_id'],
                    'cliente_email' => $_REQUEST['cliente_email'],
                    'cliente_direccion' => $_REQUEST['cliente_direccion'],
                    'cliente_telefono' => $_REQUEST['cliente_telefono'],
                    'cliente_id' => $_REQUEST['cliente_id'],
                );

                $this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.detalle_encuesta" => "'" . json_encode($detalle_encuesta) . "'"), array("ListadoLlamadasDetalle.listado_llamada_id" => base64_decode($_REQUEST['ListadoLlamadaId'])));

                if (!empty($_REQUEST['cliente_email']) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_email" => "'" . $_REQUEST['cliente_email'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                if (!empty($_REQUEST['cliente_direccion']) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_direccion" => "'" . $_REQUEST['cliente_direccion'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                if (!empty($_REQUEST['cliente_telefono']) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_telefonos" => "'" . $_REQUEST['cliente_telefono'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                if ((!empty($_REQUEST['departamento_id']) || !empty($_REQUEST['municipio_id'])) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_departamento_id" => $_REQUEST['departamento_id'], "bd_municipio_id" => $_REQUEST['municipio_id']), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                if (!empty($_REQUEST['nombre_contacto']) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_nombre_contacto" => "'" . $_REQUEST['nombre_contacto'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                if (!empty($_REQUEST['observaciones']) && !empty($_REQUEST['cliente_id'])) {
                    $this->BdCliente->updateAll(array("BdCliente.bd_observaciones" => "'" . $_REQUEST['observaciones'] . "'"), array("BdCliente.id" => $_REQUEST['cliente_id']));
                }
                echo true;
            }
        }
        exit;
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid listado llamada', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('listadoLlamada', $this->ListadoLlamada->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->ListadoLlamada->create();
            if ($this->ListadoLlamada->save($this->data)) {
                $this->Session->setFlash(__('La llamada se ha registrado exitosamente! ', true));
                $this->redirect(array('action' => 'iniciar_llamada/', base64_encode($this->ListadoLlamada->getInsertID())));
            } else {
                $this->Session->setFlash(__('La llamada no se ha podido registrar. Por favor intente nuevamente.', true));
            }
        }
        $bdClientes = $this->ListadoLlamada->BdCliente->find('list', array('fields' => 'id, bd_razon_social', 'order' => 'bd_razon_social'));
        $users = $this->ListadoLlamada->User->find('list');
        $this->set(compact('bdClientes', 'users'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid listado llamada', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->ListadoLlamada->save($this->data)) {
                $this->Session->setFlash(__('The listado llamada has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The listado llamada could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->ListadoLlamada->read(null, $id);
        }
        $bdClientes = $this->ListadoLlamada->BdCliente->find('list');
        $users = $this->ListadoLlamada->User->find('list');
        $this->set(compact('bdClientes', 'users'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for listado llamada', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->ListadoLlamada->delete($id)) {
            $this->Session->setFlash(__('Listado llamada deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Listado llamada was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    function clientes() {
        if ($this->RequestHandler->isAjax()) {//condici�n que pregunta si la petici�n es AJAX
            if (!empty($_REQUEST['ListadoLlamadaBdIdentificacion'])) {
                $conditions = array('BdCliente.bd_identificacion LIKE' => '%' . $_REQUEST['ListadoLlamadaBdIdentificacion'] . '%');
                $clientes = $this->BdCliente->find('all', array('conditions' => $conditions));
                if (count($clientes) > 0) {
                    echo json_encode($clientes);
                } else {
                    echo null;
                }
            }
        }
    }

}
