<?php

class PedidosController extends AppController
{

    var $name = 'Pedidos';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos', 'PedidosAuditoria');
    var $uses = array(
        'Pedido',
        'PedidosDetalle',
        'Producto',
        'TipoCategoria',
        'Empresa',
        'EmpresasAprobadore',
        'Sucursale',
        'EstadoPedido',
        'User',
        'PlantillasDetalle',
        'TipoPedido',
        'SucursalesPlantilla',
        'LocalidadRelRuta',
        'Cronograma',
        'SucursalesPresupuestosPedido',
        'TipoMovimiento',
        'PedidosAudit',
        'Encuesta',
        'EncuestasDiligenciada',
        'Consecutivo',
        'Ruta'
    );

    function isAuthorized()
    {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Pedidos', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Pedidos', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index()
    {
        $this->set('interno', array_search($this->Session->read('Auth.User.rol_id'), $this->Permisos->RolesInternos()));
    }

    function observaciones($id = null)
    {
        $pedidos = $this->PedidosDetalle->find('all', array('fields' => 'Pedido.id, Pedido.mes_pedido, Pedido.clasificacion_pedido, Pedido.guia_despacho, PedidosDetalle.id, PedidosDetalle.observacion_producto, Pedido.observaciones, Producto.codigo_producto, Producto.nombre_producto, Producto.marca_producto, TipoCategoria.tipo_categoria_descripcion, Pedido.fecha_entrega_1, Pedido.fecha_entrega_2', 'conditions' => array('PedidosDetalle.pedido_id' => $id)));
        $this->set('pedidos', $pedidos);

        $this->Pedido->set($this->data);
        if (!empty($this->data['Pedido'])) {
            foreach ($this->data['Pedido'] as $key => $value) {
                $aux = explode('_', $key);
                $pedido_id = $aux[1];
                $detalle_id = $aux[2];
                //if (!empty($this->data['Pedido'][$key])) {
                if ($pedido_id > 0) {
                    $this->Pedido->updateAll(array("Pedido.observaciones" => "'" . $this->data['Pedido'][$key] . "'", "Pedido.fecha_entrega_1" => "'" . $this->data['Pedido']['fecha_entrega_1'] . "'", "Pedido.fecha_entrega_2" => "'" . $this->data['Pedido']['fecha_entrega_2'] . "'", "Pedido.mes_pedido" => "'" . $this->data['Pedido']['mes_pedido'] . "'", "Pedido.clasificacion_pedido" => "'" . $this->data['Pedido']['clasificacion_pedido'] . "'", "Pedido.guia_despacho" => "'" . $this->data['Pedido']['guia_despacho'] . "'"), array("Pedido.id" => $pedido_id));
                } else {
                    $this->PedidosDetalle->updateAll(array("PedidosDetalle.observacion_producto" => "'" . $this->data['Pedido'][$key] . "'"), array("PedidosDetalle.id" => $detalle_id));
                }
                //}
            }
            $this->Session->setFlash(__('Las observaciones del pedido se ha actualizado correctamente.', true));
            if ($this->Session->read('Pedido.pedido_id')) {
                $this->redirect(array('controller' => 'pedidos', 'action' => 'observaciones/' . $this->data['Pedido']['pedido_id']));
            } else {
                $this->redirect(array('controller' => 'pedidos', 'action' => 'search_orden'));
            }
        }
    }

    function cambiar_estado()
    {
        date_default_timezone_set('America/Bogota');
        if ($this->Session->read('Auth.User.rol_id') == '1') {

            $this->set('pedido_id', null);
            $pedido = array();

            if (!empty($this->data)) {
                if (!empty($this->data['Pedido']['id'])) {
                    if (strpos($this->data['Pedido']['id'], ',')) {
                        $pedidos = explode(',', $this->data['Pedido']['id']);
                    } else {
                        $pedidos = $this->data['Pedido']['id'];
                    }
                    $pedido = $this->Pedido->find('all', array('fields' => 'Pedido.id, Pedido.pedido_fecha, Empresa.nombre_empresa, Sucursale.nombre_sucursal, EstadoPedido.nombre_estado, TipoPedido.nombre_tipo_pedido, EstadoPedido.id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'Pedido.id' => $pedidos), 'order' => 'Pedido.id DESC'));
                }
                if (!empty($this->data['Pedido']['pedido_estado_pedido'])) {
                    $pedidos_marcados = array();
                    foreach ($this->data['Pedido'] as $key => $value) {

                        if ($key != 'id') {
                            if ($key != 'pedido_estado_pedido') {
                                // echo $key . ' - ' . $value . '<br>';
                                if ($value > 0) {
                                    $this->PedidosAuditoria->AuditoriaCambioEstado($value, $this->data['Pedido']['pedido_estado_pedido'], $this->Session->read('Auth.User.id'), 'Cambiar de Estado Pedido.');
                                    array_push($pedidos_marcados, $value);
                                }
                            }
                        }
                    }
                    if ($this->Pedido->updateAll(array("Pedido.pedido_estado_pedido" => "'" . $this->data['Pedido']['pedido_estado_pedido'] . "'"), array("Pedido.pedido_estado_pedido <" => '6', "Pedido.id" => $pedidos_marcados))) {
                        if ($this->data['Pedido']['pedido_estado_pedido'] == '4') {
                            $this->Pedido->updateAll(array("Pedido.fecha_aprobado_pedido" => "'" . date('Y-m-d H:i:s') . "'"), array("Pedido.pedido_estado_pedido <" => '6', "Pedido.id" => $pedidos_marcados));
                        }
                        if (/* $this->data['Pedido']['pedido_estado_pedido'] == '1' || */$this->data['Pedido']['pedido_estado_pedido'] == '2') {
                            $this->Pedido->updateAll(array("Pedido.fecha_aprobado_pedido" => "null"), array("Pedido.pedido_estado_pedido <" => '6', "Pedido.id" => $pedidos_marcados));
                            // Eliminar los productos que tenga anteriormente el pedido
                            $delete = "DELETE FROM pedidos_detalles WHERE pedido_id IN (" . implode(',', $pedidos_marcados) . ");";
                            $this->PedidosDetalle->query($delete);
                        }
                        $this->Session->setFlash(__('El estado del pedido se ha actualizado correctamente.', true));
                        $this->redirect(array('controller' => 'pedidos', 'action' => 'cambiar_estado'));
                    }
                }
            }
            $this->set('data_pedidos', $pedido);
            $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('EstadoPedido.id' => array('1', '2', '4'))));
            $this->set(compact('estados'));
        } else {
            $this->set('data_pedidos', array());
            $this->Session->setFlash(__('No tiene los suficientes permisos para cambiar estados de pedido. Debe ser Administrador.', true));
        }
    }

    function orden_pedido()
    {
        ini_set('memory_limit', '1024M');
        date_default_timezone_set('America/Bogota');
        if (!empty($this->data)) {

            $consecutivo_data = $this->Consecutivo->find("first", array("conditions" => array("Consecutivo.id" => $this->data["Pedido"]["consecutivo_id"]), "fields" => ["numero_seq", "id", "numero_contrato"]));
            $consecutivo_pedido = $consecutivo_data["Consecutivo"]["numero_seq"] + 1;

            $this->Consecutivo->save([
                "Consecutivo" => array(
                    "id" => $consecutivo_data["Consecutivo"]["id"],
                    "numero_seq" => $consecutivo_pedido
                )
            ]);

            $this->data['Pedido']['pedido_fecha'] = date('Y-m-d');
            $this->data['Pedido']['pedido_hora'] = date('H:i:s');
            $this->data['Pedido']['fecha_orden_pedido'] = date('Y-m-d H:i:s');
            $this->data['Pedido']['pedido_fecha_creacion'] = date('Y-m-d H:i:s'); /* 2020-01-31 */
            $this->data['Pedido']['pedido_estado_pedido'] = '1'; // En proceso
            $this->data['Pedido']['tipo_categoria_id'] = implode(",", $this->data['Pedido']['tipo_categoria_id']);
            $this->data['Pedido']['consecutivo'] = $consecutivo_pedido;
            $this->data['Pedido']['numero_contrato'] =  $consecutivo_data["Consecutivo"]["numero_contrato"];

            $this->Pedido->create();

            if ($this->Pedido->save($this->data)) {
                $this->PedidosAuditoria->AuditoriaCambioEstado($this->Pedido->getInsertID(), '1', $this->Session->read('Auth.User.id'));

                $tipo_pedido = $this->Pedido->find('all', array('fields' => 'empresa_id, sucursal_id, tipo_pedido_id, tipo_categoria_id,fecha_entrega_1,fecha_entrega_2,clasificacion_pedido', 'conditions' => array('Pedido.id' => $this->Pedido->getInsertID())));

                $this->Session->write('Pedido.pedido_id', $this->Pedido->getInsertID());
                //$this->Session->write('Pedido.tipo_pedido_id', $this->data['Pedido']['tipo_pedido_id']);
                // $this->Session->write('Pedido.empresa_id', $this->data['Pedido']['empresa_id']); // BBVA
                // $this->Session->write('Pedido.sucursal_id', $this->data['Pedido']['sucursal_id']); // nueva
                // $this->Session->write('Pedido.tipo_categoria_id', $this->data['Pedido']['tipo_categoria_id']);

                $this->Session->write('Pedido.tipo_pedido_id', $tipo_pedido['0']['Pedido']['tipo_pedido_id']);
                $this->Session->write('Pedido.empresa_id', $tipo_pedido['0']['Pedido']['empresa_id']); // BBVA
                $this->Session->write('Pedido.sucursal_id', $tipo_pedido['0']['Pedido']['sucursal_id']);
                $this->Session->write('Pedido.tipo_categoria_id', $tipo_pedido['0']['Pedido']['tipo_categoria_id']);
                $this->Session->write('Pedido.fecha_entrega_1', $tipo_pedido['0']['Pedido']['fecha_entrega_1']);
                $this->Session->write('Pedido.fecha_entrega_2', $tipo_pedido['0']['Pedido']['fecha_entrega_2']);
                $this->Session->write('Pedido.clasificacion_pedido', $tipo_pedido['0']['Pedido']['clasificacion_pedido']);

                $categoria = "UPDATE pedidos 
                    SET tipo_categoria_id = tipo_pedidos.tipo_categoria_id
                    FROM tipo_pedidos
                    WHERE pedidos.tipo_pedido_id = tipo_pedidos.id
                    AND pedidos.id = " . $this->Session->read('Pedido.pedido_id') . ";";
                $this->PedidosDetalle->query($categoria);


                // BBVA
                $iniciar_presupuesto = "INSERT INTO sucursales_presupuestos (sucursal_id,pedido_id,tipo_pedido_id,presupuesto_utilizado,pedido_estado_pedido) 
                        VALUES (" . $this->data['Pedido']['sucursal_id'] . "," . $this->Pedido->getInsertID() . "," . $this->data['Pedido']['tipo_pedido_id'] . ",0,1);";
                $this->Pedido->query($iniciar_presupuesto);

                // BBVA
                $encuesta = $this->Encuesta->find('first', array('fields' => 'id', 'conditions' => array('empresa_id' => $this->data['Pedido']['empresa_id'], 'estado_encuesta' => true)));
                if (!empty($encuesta['Encuesta']['id'])) {
                    $encuestaDiligenciada = "INSERT INTO encuestas_diligenciadas (encuesta_id, pedido_id, fecha_encuesta, empresa_id, sucursal_id) VALUES (" . $encuesta['Encuesta']['id'] . "," . $this->Pedido->getInsertID() . ",now()," . $this->data['Pedido']['empresa_id'] . "," . $this->data['Pedido']['sucursal_id'] . ")";
                    $this->Pedido->query($encuestaDiligenciada);
                }

                $this->Session->setFlash(__('Se ha realizado la orden de pedido. Por favor relacione los productos a solicitar. Nuevo estado: En Proceso.', true));
                // $this->redirect(array('action' => 'detalle_pedido')); // BBVA
                $this->redirect(array('action' => 'detalle_pedido/' . $this->Pedido->getInsertID()));
            } else {
                $this->Session->setFlash(__('La orden de pedido no se ha realizado. Por favor intente de nuevo.', true));
            }
        }
        // Activar Cronogramas por empresa
        $activar = "SELECT id FROM cronogramas WHERE NOW()::date BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $data_activar = $this->Cronograma->query($activar);
        foreach ($data_activar as $value) {
            $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'true'), array("Cronograma.id" => $value[0]['id']));
        }

        // Desactivar Cronogramas por empresa
        // $inactivar = "SELECT id FROM cronogramas WHERE fecha_fin < NOW()::date;";
        $inactivar = "SELECT id FROM cronogramas WHERE NOW()::date NOT BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . "  AND estado_cronograma= TRUE;";
        $data_inactivar = $this->Cronograma->query($inactivar);
        foreach ($data_inactivar as $value) {
            $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'false'), array("Cronograma.id" => $value[0]['id']));
        }

        // Consultar cronogramas
        $cronograma = $this->Cronograma->find('all', array('fields' => 'tipo_pedido_id_2', 'conditions' =>
        array(
            'Cronograma.empresa_id' => $this->Session->read('Auth.User.empresa_id'),
            'Cronograma.estado_cronograma' => true
        )));

        // Consultar presupuestos por sucursal - Informativo
        $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Auth.User.sucursal_id'), /* 'tipo_pedido_id'=>$this->Session->read('Pedido.tipo_pedido_id'), */ 'SucursalesPresupuestosPedido.presupuesto_asignado > SucursalesPresupuestosPedido.presupuesto_utilizado')));
        $this->set('presupuestos', $presupuestos);

        $conditions = array(
            'Pedido.user_id' => $this->Session->read('Auth.User.id'),
            'Pedido.pedido_estado' => true,
            'Pedido.pedido_estado_pedido' => array('1'),
            'EmpresasAprobadore.user_id' => '1'
        ); // Muestra solo las que estan en proceso.
        $this->paginate = array('limit' => 1);
        $this->paginate($conditions); // $pedidos = array();
        $pedidos = $this->Pedido->find('all', array('conditions' => $conditions));
        $this->set('pedidos', $pedidos);

        // print_r($this->Pedido->find('all', array('conditions' => $conditions)));
        if (count($pedidos)) {
            $this->Session->write('Pedido.tipo_pedido_id', $pedidos[0]['Pedido']['tipo_pedido_id']);
            $this->Session->setFlash(__('ATENCIÓN: Tiene ordenes de pedido en proceso sin terminar. Haga click en el icono <div class="glyphicon glyphicon-arrow-right"></div> para continuar.', true));
        }

        $condition_cronograma = array('TipoPedido.estado' => true);
        if ($cronograma) {
            $condition_cronograma['TipoPedido.id'] = explode(",", $cronograma[0]['Cronograma']['tipo_pedido_id_2']);
        }

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array(
            'TipoPedido.estado' => true,
            'TipoPedido.id' => $condition_cronograma
        )));


        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions_empresa = array('id' => array_unique($empresas_permisos), 'estado_empresa' => true);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);

        if ($this->Session->read('Auth.User.rol_id') == '1' /* || $this->Session->read('Auth.User.rol_id') == '4' */) {
            $inventarios_salida = array('IVS01', 'IVS02', 'IVS03', 'IVS04', 'IVS05', 'IVS06', 'IVS07');
            $tipoMovimientos = $this->TipoMovimiento->find('list', array('fields' => 'TipoMovimiento.nombre_tipo_movimiento', 'order' => 'TipoMovimiento.id', 'conditions' => array('TipoMovimiento.codigo_tipo_movimiento' => $inventarios_salida, 'TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'S')));
        } else {
            $inventarios_salida = array('IVS01');
            $tipoMovimientos_tmp = $this->TipoMovimiento->find('all', array('fields' => 'TipoMovimiento.id', 'order' => 'TipoMovimiento.id', 'conditions' => array('TipoMovimiento.codigo_tipo_movimiento' => $inventarios_salida, 'TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'S')));
            $tipoMovimientos = $tipoMovimientos_tmp[0]['TipoMovimiento']['id'];
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales1 = $this->Sucursale->find('list', array('fields' => ['Sucursale.v_regional_sucursal'], 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales)); //, 'conditions' => array('Sucursale.estado_sucursal' => true)

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => ['Sucursale.regional_sucursal', 'Sucursale.id'], 'order' => 'Sucursale.regional_sucursal'));

        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $user_asociado = $this->User->find('first', ["conditions" => ["User.id" => $this->Session->read('Auth.User.id')], "fields" => "asociado_id"]);
        $consecutivos_empresa = $this->Consecutivo->find('all', array(
            "fields" => ["Consecutivo.id", "Asociado.nombre_asociado", "Consecutivo.numero_contrato"],
            "conditions" => array("Consecutivo.asociado_id" => $user_asociado["User"]["asociado_id"])
        ));
        $consecutivos = array();
        foreach ($consecutivos_empresa as $consecutivo) {
            $consecutivos[$consecutivo["Consecutivo"]["id"]] = $consecutivo["Asociado"]["nombre_asociado"] . ' - ' . $consecutivo["Consecutivo"]["numero_contrato"];
        }
        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.id'));
        $this->set('sucursales', $this->Sucursale->find('all', array('conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id')))));
        $this->set(compact('empresas', 'tipo_pedido', 'sucursales1', 'tipoMovimientos', 'tipoCategoria', 'regional', 'consecutivos'));
    }

    function detalle_pedido($id = null)
    {
        if ($this->Session->read('Auth.User.id') == '97') {
            //  Configure::write('debug', 2);
        }
        ini_set('memory_limit', '1024M');
        date_default_timezone_set('America/Bogota');

        // Consultar la plantilla que tiene el usuario
        // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id'))));
        $plantilla = $this->SucursalesPlantilla->find('all', array('fields' => 'SucursalesPlantilla.plantilla_id, Plantilla.nombre_plantilla, Plantilla.id, Plantilla.estado_plantilla, TipoPedido.id,  TipoPedido.nombre_tipo_pedido, TipoPedido.tipo_categoria_id', 'conditions' => array('SucursalesPlantilla.sucursale_id' => $this->Session->read('Pedido.sucursal_id')/* , 'TipoPedido.tipo_categoria_id' => $this->Session->read('Pedido.tipo_categoria_id') /* , 'SucursalesPlantilla.tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id') */)));
        $this->set('plantilla', $plantilla);

        $array_plantillas = array();
        foreach ($plantilla as $key => $value) {
            array_push($array_plantillas, $value['SucursalesPlantilla']['plantilla_id']);
        }

        if ($this->Session->read('Auth.User.id') == '97') {
            /*
            if (count($plantilla) > 1) {
                foreach ($plantilla as $key => $value) {
                    if ($this->Session->read('Pedido.tipo_pedido_id') == $value['TipoPedido']['id']) {
                        echo $value['TipoPedido']['tipo_categoria_id'];
                        echo $value['SucursalesPlantilla']['plantilla_id'];
                        echo "<br>";
                    }
                }
            }

            print_r($plantilla);
            echo 'sucursal:' . $this->Session->read('Pedido.sucursal_id') . '<br>';
            echo 'cate:' . $this->Session->read('Pedido.tipo_categoria_id') . '<br>';
            echo 'plantilla:' . $plantilla['0']['SucursalesPlantilla']['plantilla_id'] . '<br>'; */
        }
        // Consultar los productos relacionados a la plantilla
        $productos_sucursal = $this->PlantillasDetalle->find('all', array('fields' => 'producto_id, precio_producto_2, precio_producto_bs_2', 'conditions' => array('Plantilla.estado_plantilla' => true, 'PlantillasDetalle.plantilla_id' => $array_plantillas))); // $tmp_plantillas
        //$productos_sucursal = $this->PlantillasDetalle->find('all', array('fields' => 'producto_id, precio_producto_2, precio_producto_bs_2', 'conditions' => array('Plantilla.estado_plantilla' => true, 'PlantillasDetalle.plantilla_id' => $plantilla['0']['SucursalesPlantilla']['plantilla_id']))); // $tmp_plantillas

        $productos_id = array();
        foreach ($productos_sucursal as $value) {
            array_push($productos_id, $value['PlantillasDetalle']['producto_id']);
        }
        // echo $this->Session->read('Pedido.tipo_categoria_id');
        $productos = $this->Producto->find('all', array('order' => 'Producto.proveedor_producto, Producto.codigo_producto', 'conditions' => array('Producto.id' => $productos_id, 'Producto.tipo_categoria_id' => $this->Session->read('Pedido.tipo_categoria_id'), 'Producto.estado' => true, 'Producto.mostrar_producto' => true)));
        if ($this->Session->read('Auth.User.id') == '97') {
            //       print_r($productos);
        }
        // Consultar si el municipio de la sucursal es a nivel nacional o Bogota/Sabana
        $municipio_bs = $this->Sucursale->find('first', array('fields' => array('Municipio.municipio_bogota_sabana'), 'conditions' => array('Sucursale.id' => $this->Session->read('Pedido.sucursal_id'))));
        $this->set('municipio_bs', $municipio_bs);
        // echo $municipio_bs['Municipio']['municipio_bogota_sabana'];
        if (!empty($this->data)) {

            // Eliminar los productos que tenga anteriormente el pedido
            $delete = "DELETE FROM pedidos_detalles WHERE pedido_id =" . $this->Session->read('Pedido.pedido_id') . ";";
            $this->PedidosDetalle->query($delete);

            // Por cada producto seleccionado, se agrega al detalle de la plantilla
            $pedido_detalle = array();
            foreach ($productos as $producto) :
                $this->PedidosDetalle->create();

                if ($this->data['PedidosDetalle']['cantidad_pedido_' . $producto['Producto']['id']] > 0) {

                    $pedido_detalle = array(
                        'pedido_id' => $this->Session->read('Pedido.pedido_id'),
                        'producto_id' => $producto['Producto']['id'],
                        'precio_producto' => $this->data['PedidosDetalle']['precio_producto_' . $producto['Producto']['id']],
                        'cantidad_pedido' => $this->data['PedidosDetalle']['cantidad_pedido_' . $producto['Producto']['id']],
                        'lote' => $this->data['PedidosDetalle']['lote_' . $producto['Producto']['id']],
                        'fecha_expiracion' => $this->data['PedidosDetalle']['fecha_expiracion_' . $producto['Producto']['id']],
                        'iva_producto' => $producto['Producto']['iva_producto'],
                        'medida_producto' => $producto['Producto']['medida_producto'],
                        'tipo_categoria_id' => $producto['Producto']['tipo_categoria_id'],
                        'fecha_pedido_detalle' => date('Y-m-d H:i:s'),
                        'observacion_producto' => null,
                    );


                    $this->PedidosDetalle->save($pedido_detalle, FALSE);
                }

            endforeach;
            // TERMINAR PEDIDO
            if ($this->data['PedidosDetalle']['pedido_entrega_parcial']) {
                if ($this->Pedido->updateAll(array(
                    "Pedido.pedido_estado" => 'true',
                    "Pedido.pedido_estado_pedido" => '1',
                    "Pedido.pedido_fecha" => "'" . date('Y-m-d') . "'",
                    "Pedido.pedido_hora" => "'" . date('H:i:s') . "'",
                    "Pedido.fecha_orden_pedido" => "'" . date('Y-m-d H:i:s') . "'"
                ), array("Pedido.id" => $this->Session->read('Pedido.pedido_id')))) {
                    $this->PedidosAuditoria->AuditoriaCambioEstado($this->Session->read('Pedido.pedido_id'), '1', $this->Session->read('Auth.User.id'));
                    $this->Session->setFlash(__('La orden de pedido se ha guardado exitosamente. Estado: En Proceso.', true));
                    $this->redirect(array('controller' => 'pedidos', 'action' => 'search_orden/'));
                }
            } else {
                if ($this->Pedido->updateAll(array(
                    "Pedido.pedido_estado" => 'true',
                    "Pedido.pedido_estado_pedido" => '3',
                    "Pedido.pedido_fecha" => "'" . date('Y-m-d') . "'",
                    "Pedido.pedido_hora" => "'" . date('H:i:s') . "'",
                    "Pedido.fecha_orden_pedido" => "'" . date('Y-m-d H:i:s') . "'"
                ), array("Pedido.id" => $this->Session->read('Pedido.pedido_id')))) {
                    $this->PedidosAuditoria->AuditoriaCambioEstado($this->Session->read('Pedido.pedido_id'), '3', $this->Session->read('Auth.User.id'));
                    $this->Session->setFlash(__('La orden de pedido se ha terminado exitosamente. Nuevo estado: Pendiente Aprobacion.', true));
                    $this->redirect(array('controller' => 'pedidos', 'action' => 'ver_pedido/' . $this->Session->read('Pedido.pedido_id')));
                } else {
                    $this->Session->setFlash(__('No se logró terminar la orden de pedido exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }

        if (!empty($id)) {
            $tipo_pedido = $this->Pedido->find('all', array('fields' => 'empresa_id, sucursal_id, tipo_pedido_id, tipo_categoria_id,fecha_entrega_1,fecha_entrega_2, clasificacion_pedido', 'conditions' => array('Pedido.id' => $id)));

            $parametroEncuesta = $this->Empresa->find('first', array('fields' => 'parametro_encuesta', 'conditions' => array('Empresa.id' => $tipo_pedido['0']['Pedido']['empresa_id'])));

            if ($parametroEncuesta['Empresa']['parametro_encuesta'] == '1') {

                $encuestaDiligenciada = $this->EncuestasDiligenciada->find('first', array('fields' => 'estado_diligenciado', 'conditions' => array('EncuestasDiligenciada.pedido_id' => $id)));
                if ($encuestaDiligenciada['EncuestasDiligenciada']['estado_diligenciado'] == '0') {
                    $this->redirect(array('action' => '../encuestas/diligenciar/' . $id));
                }
                /* echo $encuestaDiligenciada['EncuestasDiligenciada']['estado_diligenciado'];
                  echo "qsa";
                  exit; */
            }

            $this->Session->write('Pedido.pedido_id', $id);
            $this->Session->write('Pedido.tipo_pedido_id', $tipo_pedido['0']['Pedido']['tipo_pedido_id']);
            $this->Session->write('Pedido.empresa_id', $tipo_pedido['0']['Pedido']['empresa_id']); // BBVA
            $this->Session->write('Pedido.sucursal_id', $tipo_pedido['0']['Pedido']['sucursal_id']);
            $this->Session->write('Pedido.tipo_categoria_id', $tipo_pedido['0']['Pedido']['tipo_categoria_id']);
            $this->Session->write('Pedido.fecha_entrega_1', $tipo_pedido['0']['Pedido']['fecha_entrega_1']);
            $this->Session->write('Pedido.fecha_entrega_2', $tipo_pedido['0']['Pedido']['fecha_entrega_2']);
            $this->Session->write('Pedido.clasificacion_pedido', $tipo_pedido['0']['Pedido']['clasificacion_pedido']);

            $categoria = "UPDATE pedidos 
                    SET tipo_categoria_id = tipo_pedidos.tipo_categoria_id
                    FROM tipo_pedidos
                    WHERE pedidos.tipo_pedido_id = tipo_pedidos.id
                    AND pedidos.id = " . $this->Session->read('Pedido.pedido_id') . ";";
            $this->PedidosDetalle->query($categoria);

            $this->redirect(array('action' => 'detalle_pedido'));
        }

        $this->set('pedidos', $this->Pedido->find('all', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('Pedido.pedido_estado' => true, 'Pedido.id' => $this->Session->read('Pedido.pedido_id')))));
        $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pedido_id')))));


        // print_r($plantilla);
        $tmp_plantillas = array();
        foreach ($plantilla as $value) {
            array_push($tmp_plantillas, $value['SucursalesPlantilla']['plantilla_id']);
        }

        // Si la sucursal no tiene plantilla, se cargan todos los productos activos.
        /* if (count($productos_sucursal) > '0') {
          // $this->set('productos', $this->Producto->find('all', array('fields' => '*', 'conditions' => array('Producto.id' => $productos_sucursal, 'Producto.estado' => true, 'Producto.mostrar_producto' => true))));

          } else {
          $this->set('productos', $this->Producto->find('all', array('fields' => '*', 'conditions' => array('Producto.estado' => true, 'Producto.mostrar_producto' => true))));
          } */
        $this->set('productos', $productos);
        $this->set('productos_sucursal', $productos_sucursal);

        // Verificar presupuestos por sucursal - Informativo
        $empresa = $this->Empresa->find('first', array('fields' => 'id, parametro_presupuesto_iva', 'conditions' => array('Empresa.id' => $this->Session->read('Pedido.empresa_id'))));
        $this->set('parametro_presupuesto_iva', $empresa['Empresa']['parametro_presupuesto_iva']);
        if ($empresa['Empresa']['parametro_presupuesto_iva'] == 1) { // Si es verdadero, se suma el valor del IVA. BBVA
            $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'), 'SucursalesPresupuestosPedido.presupuesto_asignado > (SucursalesPresupuestosPedido.presupuesto_utilizado + SucursalesPresupuestosPedido.presupuesto_iva)')));
        } else {
            $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'), 'SucursalesPresupuestosPedido.presupuesto_asignado > SucursalesPresupuestosPedido.presupuesto_utilizado')));
        }

        $this->set('presupuestos', $presupuestos);
        $this->set('presupuesto_disponible', '');
        if (count($presupuestos) == 0) {
            $this->Session->setFlash(__('ATENCIÓN. Verifique el presupuesto asignado para la sucursal. Realice los siguientes pasos:<br>1. Contacte el administrador del sistema para Terminar el pedido.<br>2. Cambiar las cantidades de los productos ajustandose al presupuesto.', true));
            $this->set('presupuesto_disponible', 'red');
        }

        // Consultar presupuestos por sucursal - Informativo
        $presupuestos_info = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'))));
        $this->set('presupuestos_info', $presupuestos_info);

        // 05032018
        $tipo_categorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.id', 'conditions' => array('TipoCategoria.id' => explode(',', $this->Session->read('Pedido.tipo_categoria_id'))))); //05032018
        $this->set('tipo_categorias', $tipo_categorias);
    }

    /* INACTIVAR FUNCION ORIGINAL DE PEDIDOS
      2022-12-21 */

    function detalle_pedido_1($id = null)
    {
        ini_set('memory_limit', '1024M');
        date_default_timezone_set('America/Bogota');


        if (!empty($this->data)) {

            $this->PedidosDetalle->create();
            $aux = explode('|', $this->data['PedidosDetalle']['producto_id2']);
            $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.codigo_producto' => trim($aux[0]))));
            if (count($aux) > 0) {
                // Verificar que el producto este en la plantilla de la sucursal
                // Consultar la plantilla que tiene el usuario
                // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id'))));
                $plantilla = $this->SucursalesPlantilla->find('all', array('fields' => 'SucursalesPlantilla.plantilla_id', 'conditions' => array('SucursalesPlantilla.sucursale_id' => $this->Session->read('Pedido.sucursal_id'), 'TipoPedido.id' => $this->Session->read('Pedido.tipo_pedido_id'))));
                $parametro_precio = $this->Sucursale->find('all', array('fields' => 'Sucursale.parametro_precio', 'conditions' => array('Sucursale.id' => $this->Session->read('Pedido.sucursal_id'))));
                $parametro_precio = $parametro_precio['0']['Sucursale']['parametro_precio'];
                $tmp_plantillas = array();
                foreach ($plantilla as $value) {
                    array_push($tmp_plantillas, $value['SucursalesPlantilla']['plantilla_id']);
                }
                // Consultar los productos relacionados a la plantilla
                $productos_sucursal = $this->PlantillasDetalle->find('all', array('fields' => 'plantilla_id, producto_id, precio_producto, precio_producto_2, iva_producto, medida_producto', 'conditions' => array('PlantillasDetalle.producto_id' => $aux[0]['Producto']['id'], 'PlantillasDetalle.plantilla_id' => $plantilla['0']['SucursalesPlantilla']['plantilla_id']))); //  $tmp_plantillas
                // Si el producto seleccionado esta en la plantilla se procede a guardar el pedido
                if (count($productos_sucursal) > '0') {
                    $this->data['PedidosDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                    $this->data['PedidosDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                    $this->data['PedidosDetalle']['precio_producto'] = ($parametro_precio) == '1' ? $productos_sucursal[0]['PlantillasDetalle']['precio_producto'] : $productos_sucursal[0]['PlantillasDetalle']['precio_producto_2'];
                    $this->data['PedidosDetalle']['iva_producto'] = $productos_sucursal[0]['PlantillasDetalle']['iva_producto'];
                    $this->data['PedidosDetalle']['medida_producto'] = $productos_sucursal[0]['PlantillasDetalle']['medida_producto'];
                    $this->data['PedidosDetalle']['pedido_id'] = $this->Session->read('Pedido.pedido_id');
                    $this->data['PedidosDetalle']['fecha_pedido_detalle'] = date('Y-m-d H:i:s');
                    $this->data['PedidosDetalle']['observacion_producto'] = $this->data['PedidosDetalle']['observaciones'];

                    if ($this->PedidosDetalle->save($this->data)) {

                        // Actualizar observaciones pedido
                        if (!empty($this->data['PedidosDetalle']['observaciones'])) {
                            $update_observacion = "UPDATE pedidos SET observaciones = concat(observaciones,'" . $aux[0]['Producto']['codigo_producto'] . " - " . $this->data['PedidosDetalle']['observaciones'] . "<br>') WHERE id=" . $this->Session->read('Pedido.pedido_id') . "";
                            $this->Pedido->query($update_observacion);
                        }
                        $this->redirect(array('action' => 'detalle_pedido'));
                    } else {
                        $this->Session->setFlash(__('Por favor verifique los datos ingresados para el producto.', true));
                    }
                } else {
                    $this->Session->setFlash(__('El producto ingresado (' . $this->data['PedidosDetalle']['producto_id2'] . ') no esta registrado en la plantilla de la sucursal.', true));
                    //                    $this->redirect(array('action' => 'detalle_pedido'));
                }
            }
        }

        if (!empty($id)) {
            $tipo_pedido = $this->Pedido->find('all', array('fields' => 'empresa_id, sucursal_id, tipo_pedido_id, tipo_categoria_id,fecha_entrega_1,fecha_entrega_2', 'conditions' => array('Pedido.id' => $id)));

            $parametroEncuesta = $this->Empresa->find('first', array('fields' => 'parametro_encuesta', 'conditions' => array('Empresa.id' => $tipo_pedido['0']['Pedido']['empresa_id'])));

            if ($parametroEncuesta['Empresa']['parametro_encuesta'] == '1') {

                $encuestaDiligenciada = $this->EncuestasDiligenciada->find('first', array('fields' => 'estado_diligenciado', 'conditions' => array('EncuestasDiligenciada.pedido_id' => $id)));
                if ($encuestaDiligenciada['EncuestasDiligenciada']['estado_diligenciado'] == '0') {
                    $this->redirect(array('action' => '../encuestas/diligenciar/' . $id));
                }
            }

            $this->Session->write('Pedido.pedido_id', $id);
            $this->Session->write('Pedido.tipo_pedido_id', $tipo_pedido['0']['Pedido']['tipo_pedido_id']);
            $this->Session->write('Pedido.empresa_id', $tipo_pedido['0']['Pedido']['empresa_id']); // BBVA
            $this->Session->write('Pedido.sucursal_id', $tipo_pedido['0']['Pedido']['sucursal_id']);
            $this->Session->write('Pedido.tipo_categoria_id', $tipo_pedido['0']['Pedido']['tipo_categoria_id']);
            $this->Session->write('Pedido.fecha_entrega_1', $tipo_pedido['0']['Pedido']['fecha_entrega_1']);
            $this->Session->write('Pedido.fecha_entrega_2', $tipo_pedido['0']['Pedido']['fecha_entrega_2']);
            $this->redirect(array('action' => 'detalle_pedido'));
        }

        $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pedido_id')))));

        // Consultar la plantilla que tiene el usuario
        // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id'))));
        $plantilla = $this->SucursalesPlantilla->find('all', array('fields' => 'SucursalesPlantilla.plantilla_id, Plantilla.nombre_plantilla, Plantilla.id, TipoPedido.nombre_tipo_pedido', 'conditions' => array('SucursalesPlantilla.sucursale_id' => $this->Session->read('Pedido.sucursal_id'), 'SucursalesPlantilla.tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'))));
        $this->set('plantilla', $plantilla);
        // print_r($plantilla);
        $tmp_plantillas = array();
        foreach ($plantilla as $value) {
            array_push($tmp_plantillas, $value['SucursalesPlantilla']['plantilla_id']);
        }
        // Consultar los productos relacionados a la plantilla
        $productos_sucursal = $this->PlantillasDetalle->find('list', array('fields' => 'producto_id', 'conditions' => array('PlantillasDetalle.plantilla_id' => $plantilla['0']['SucursalesPlantilla']['plantilla_id']))); // $tmp_plantillas
        // Si la sucursal no tiene plantilla, se cargan todos los productos activos.
        if (count($productos_sucursal) > '0') {
            $this->set('productos', $this->Producto->find('all', array('fields' => 'id,codigo_producto,nombre_producto,marca_producto,mensaje_advertencia,multiplo,producto_completo', 'conditions' => array('Producto.id' => $productos_sucursal, 'Producto.estado' => true))));
        } else {
            $this->set('productos', $this->Producto->find('all', array('fields' => 'id,codigo_producto,nombre_producto,marca_producto,mensaje_advertencia,multiplo,producto_completo', 'conditions' => array('Producto.estado' => true))));
        }

        // Verificar presupuestos por sucursal - Informativo
        $empresa = $this->Empresa->find('first', array('fields' => 'id, parametro_presupuesto_iva', 'conditions' => array('Empresa.id' => $this->Session->read('Pedido.empresa_id'))));
        $this->set('parametro_presupuesto_iva', $empresa['Empresa']['parametro_presupuesto_iva']);
        if ($empresa['Empresa']['parametro_presupuesto_iva'] == 1) { // Si es verdadero, se suma el valor del IVA. BBVA
            $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'), 'SucursalesPresupuestosPedido.presupuesto_asignado > (SucursalesPresupuestosPedido.presupuesto_utilizado + SucursalesPresupuestosPedido.presupuesto_iva)')));
        } else {
            $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'), 'SucursalesPresupuestosPedido.presupuesto_asignado > SucursalesPresupuestosPedido.presupuesto_utilizado')));
        }

        $this->set('presupuestos', $presupuestos);
        $this->set('presupuesto_disponible', '');
        if (count($presupuestos) == 0) {
            $this->Session->setFlash(__('ATENCIÓN. Verifique el presupuesto asignado para la sucursal. Realice los siguientes pasos:<br>1. Contacte el administrador del sistema para Terminar el pedido.<br>2. Cambiar las cantidades de los productos ajustandose al presupuesto.', true));
            $this->set('presupuesto_disponible', 'red');
        }

        // Consultar presupuestos por sucursal - Informativo
        $presupuestos_info = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $this->Session->read('Pedido.sucursal_id'), 'tipo_pedido_id' => $this->Session->read('Pedido.tipo_pedido_id'))));
        $this->set('presupuestos_info', $presupuestos_info);

        // 05032018
        $tipo_categorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.id', 'conditions' => array('TipoCategoria.id' => explode(',', $this->Session->read('Pedido.tipo_categoria_id'))))); //05032018
        $this->set('tipo_categorias', $tipo_categorias);
    }

    /*  */

    function detalle_pedido_aprobacion()
    {
        date_default_timezone_set('America/Bogota');
        if (!empty($this->data)) {

            $this->PedidosDetalle->create();
            $aux = explode('|', $this->data['PedidosDetalle']['producto_id2']);
            $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.codigo_producto' => trim($aux[0]))));
            if (count($aux) > 0) {

                // Verificar que el producto este en la plantilla de la sucursal
                $tipo_pedido = $this->Pedido->find('all', array('fields' => 'sucursal_id, tipo_pedido_id', 'conditions' => array('Pedido.id' => $this->data['PedidosDetalle']['id_pedido'])));

                // Consultar la plantilla que tiene el usuario
                // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id'))));
                // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->data['PedidosDetalle']['sucursal_id'])));
                $plantilla = $this->SucursalesPlantilla->find('all', array('fields' => 'SucursalesPlantilla.plantilla_id', 'conditions' => array('SucursalesPlantilla.sucursale_id' => $tipo_pedido['0']['Pedido']['sucursal_id'], 'TipoPedido.id' => $tipo_pedido['0']['Pedido']['tipo_pedido_id'])));

                // Consultar los productos relacionados a la plantilla
                // $productos_sucursal = $this->PlantillasDetalle->find('list', array('fields' => 'producto_id', 'conditions' => array('PlantillasDetalle.producto_id' => $aux[0]['Producto']['id'], 'PlantillasDetalle.plantilla_id' => $plantilla['0']['Sucursale']['plantilla_id'])));
                // $productos_sucursal = $this->PlantillasDetalle->find('all', array('fields' => 'plantilla_id, producto_id, precio_producto, iva_producto, medida_producto', 'conditions' => array('PlantillasDetalle.producto_id' => $aux[0]['Producto']['id'], 'PlantillasDetalle.plantilla_id' => $plantilla['0']['Sucursale']['plantilla_id'])));
                $productos_sucursal = $this->PlantillasDetalle->find('all', array('fields' => 'plantilla_id, producto_id, precio_producto, iva_producto, medida_producto', 'conditions' => array('PlantillasDetalle.producto_id' => $aux[0]['Producto']['id'], 'PlantillasDetalle.plantilla_id' => $plantilla['0']['SucursalesPlantilla']['plantilla_id'])));

                // Si el producto seleccionado esta en la plantilla se procede a guardar el pedido
                if (count($productos_sucursal) > '0') {
                    $this->data['PedidosDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                    $this->data['PedidosDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                    /* $this->data['PedidosDetalle']['precio_producto'] = $aux[0]['Producto']['precio_producto'];
                      $this->data['PedidosDetalle']['iva_producto'] = $aux[0]['Producto']['iva_producto'];
                      $this->data['PedidosDetalle']['medida_producto'] = $aux[0]['Producto']['medida_producto']; */
                    $this->data['PedidosDetalle']['precio_producto'] = $productos_sucursal[0]['PlantillasDetalle']['precio_producto'];
                    $this->data['PedidosDetalle']['iva_producto'] = $productos_sucursal[0]['PlantillasDetalle']['iva_producto'];
                    $this->data['PedidosDetalle']['medida_producto'] = $productos_sucursal[0]['PlantillasDetalle']['medida_producto'];
                    $this->data['PedidosDetalle']['pedido_id'] = $this->data['PedidosDetalle']['id_pedido'];
                    $this->data['PedidosDetalle']['fecha_pedido_detalle'] = date('Y-m-d H:i:s');

                    if ($this->PedidosDetalle->save($this->data)) {
                        $this->redirect(array('action' => 'aprobar_pedido/' . $this->data['PedidosDetalle']['id_pedido']));
                    } else {
                        $this->Session->setFlash(__('Por favor verifique los datos ingresados para el producto.', true));
                        $this->redirect(array('action' => 'aprobar_pedido/' . $this->data['PedidosDetalle']['id_pedido']));
                    }
                } else {
                    $this->Session->setFlash(__('El producto ingresado (' . $this->data['PedidosDetalle']['producto_id2'] . ') no esta registrado en la plantilla de la sucursal.', true));
                    $this->redirect(array('action' => 'aprobar_pedido/' . $this->data['PedidosDetalle']['id_pedido']));
                }
            }
        }
        $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pedido_id')))));
        $this->set('productos', $this->Producto->find('all', array('conditions' => array('Producto.estado' => true))));
    }

    function modificar_pedido()
    {
        if ($this->RequestHandler->isAjax()) { //condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['PedidosDetalleId']) && !empty($_REQUEST['PedidosDetalleCantidadPedido'])) {
                $this->PedidosDetalle->id = $_REQUEST['PedidosDetalleId'];
                $this->PedidosDetalle->cantidad_pedido = $_REQUEST['PedidosDetalleCantidadPedido'];

                if ($this->PedidosDetalle->saveField('cantidad_pedido', $this->PedidosDetalle->cantidad_pedido)) {
                    echo true;
                    $this->Session->setFlash(__('El producto se ha modificado exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró modificar el producto de la orden de pedido. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function quitar_pedido()
    {
        if ($this->RequestHandler->isAjax()) { //condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['PedidosDetalleId'])) {
                $this->PedidosDetalle->id = $_REQUEST['PedidosDetalleId'];
                if ($this->PedidosDetalle->delete()) {
                    echo true;
                    $this->Session->setFlash(__('El producto se ha quitado de la orden de pedido exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró quitar el producto de la orden de pedido. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function cancelar_pedido()
    {
        if ($this->RequestHandler->isAjax()) { //condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['PedidosDetalleId'])) {
                if ($this->Pedido->updateAll(array("Pedido.pedido_estado" => 'false', "Pedido.pedido_estado_pedido" => '2'), array("Pedido.id" => $_REQUEST['PedidosDetalleId']))) {
                    $this->PedidosAuditoria->AuditoriaCambioEstado($_REQUEST['PedidosDetalleId'], '2', $this->Session->read('Auth.User.id'));
                    $this->PedidosDetalle->updateAll(array("PedidosDetalle.cantidad_pedido" => '0'), array("PedidosDetalle.pedido_id" => $_REQUEST['PedidosDetalleId']));
                    echo true;
                    $this->Session->setFlash(__('La orden de pedido se ha cancelado exitosamente. Nuevo estado: Cancelado.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró cancelar la orden de pedido. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function terminar_pedido()
    {
        date_default_timezone_set('America/Bogota');
        if ($this->RequestHandler->isAjax()) { //condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['PedidosDetalleId'])) {
                /* 2020-01-31 - Ajuste fecha de pedido 
                 * Agregar un nuevo campo que se guarde cuando se creea el pedido
                 * ALTER TABLE pedidos  ADD COLUMN pedido_fecha_creacion timestamp without time zone;               
                 */

                if ($this->Pedido->updateAll(array(
                    "Pedido.observaciones" => "concat(observaciones,'" . $_REQUEST['PedidosDetalleObservaciones'] . "<br>')",
                    "Pedido.pedido_estado" => 'true',
                    "Pedido.pedido_estado_pedido" => '3',
                    "Pedido.pedido_fecha" => "'" . date('Y-m-d') . "'",
                    "Pedido.pedido_hora" => "'" . date('H:i:s') . "'",
                    "Pedido.fecha_orden_pedido" => "'" . date('Y-m-d H:i:s') . "'"
                ), array("Pedido.id" => $_REQUEST['PedidosDetalleId']))) {
                    $this->PedidosAuditoria->AuditoriaCambioEstado($_REQUEST['PedidosDetalleId'], '3', $this->Session->read('Auth.User.id'));
                    echo true;
                    $this->Session->setFlash(__('La orden de pedido se ha terminado exitosamente. Nuevo estado: Pendiente Aprobacion.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró terminar la orden de pedido exitosamente. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function ver_pedido($id = null)
    {
        date_default_timezone_set('America/Bogota');
        if (!empty($id)) {
            $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id))));
            $this->set('id', $id);
        } else {
            $detalles = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pedido_id'))));
            /* $asesor = $this->User->find('all', array('fields' => array('User.nombres_persona', 'User.email_persona'), 'conditions' => array('User.id' => $detalles['0']['Empresa']['user_id'])));

              $week_days = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
              $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
              $year_now = date("Y");
              $month_now = date("n");
              $day_now = date("j");
              $week_day_now = date("w");
              $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;

              $para = $detalles['0']['Empresa']['email_empresa'] . ',' . $asesor['0']['User']['email_persona'];
              $titulo = 'KOPANCOBA DELIVERY SAS - Orden de Pedido: #000' . $this->Session->read('Pedido.pedido_id');

              // Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
              $cabeceras = "Content-type: text/html\r\n";
              // $cabeceras .= 'To: KOPANCOBA DELIVERY SAS <kopancoba@kopancoba.com>' . "\r\n";
              $cabeceras .= 'From: KOPANCOBA DELIVERY SAS <kopancoba@kopancoba.com>' . "\r\n";
              $cabeceras .= 'Cc: ' . $detalles['0']['Empresa']['email_contacto'] . ',' . $detalles['0']['Sucursale']['email_sucursal'] . '  ' . "\r\n";
              $cabeceras .= 'Bcc: fmontes.botero@gmail.com, heidy.ventas@kopancoba.com' . "\r\n";

              $mensaje = "
              <div>" . $date . "</div>
              <div>&nbsp;</div>
              <div>Apreciado cliente</div>
              <div>&nbsp;</div>
              <div>Le informamos que la sucursal <b>" . $detalles['0']['Sucursale']['nombre_sucursal'] . "</b> de la empresa <b>" . $detalles['0']['Empresa']['nombre_empresa'] . "</b> a realizado una orden de pedido de productos, la cual usted puede observar su detalle en el siguiente enlace.</div>
              <div>&nbsp;</div>
              <a href='http://www.kopancoba.com/pedidos/pedidos/pedido_pdf/" . $this->Session->read('Pedido.pedido_id') . "'><b>Orden de Pedido #000" . $detalles['0']['Pedido']['id'] . "</b></a>
              <div>&nbsp;</div>
              <div><b>Fecha Pedido:</b> " . $detalles['0']['Pedido']['pedido_fecha'] . "</div>
              <div><b>Empleado:</b> " . $detalles['0']['User']['nombres_persona'] . "</div>
              <div><b>Celular Contacto:</b> " . $detalles['0']['User']['celular_persona'] . "</div>
              <div>&nbsp;</div>
              <div>El asesor encargado a su empresa es: <b>" . $asesor['0']['User']['nombres_persona'] . "</b></div>
              <div>&nbsp;</div>

              <div>Cordial Saludo,</div>
              <div>&nbsp;</div>
              <div>KOPANCOBA DELIVERY SAS - Nit 900.751.920-8</div>
              <div>Calle 13 No. 28-51</div>
              <div>Tel: 3512515 3603134</div>
              <div>&nbsp;</div>
              <div><b>Esta es una notificación automática, por favor no responda este mensaje</b></div>
              ";

              // mail($para, $titulo, $mensaje, $cabeceras); */

            $this->set('detalles', $detalles);
            $this->set('id', null);
        }
    }

    function list_ordenes()
    {
        ini_set('memory_limit', '1024M');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => array('1', '3'), 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => array_unique($empresas_permisos), 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018
        //       //       if ($this->Session->read('Auth.User.rol_id') == '1') {
        //            $conditions = array('EmpresasAprobadore.user_id' => '1', // $this->Session->read('Auth.User.id'),
        //                'Pedido.pedido_estado' => true,
        //                'Pedido.pedido_estado_pedido' => array('1', '3'/* , '4' */)); // Muestra solo las que estan pendientes de aprobaci�n.
        //            $conditions_empresa = array();
        //            $conditions_sucursales = array('id_empresa !=' => '1', 'Sucursale.estado_sucursal' => true);
        //        } else {
        //            $conditions = array('Pedido.user_id' => $this->Session->read('Auth.User.id'),
        //                'EmpresasAprobadore.user_id' => '1',
        //                'Pedido.pedido_estado' => true,
        //                'Pedido.pedido_estado_pedido' => array('1', '3'/* , '4' */)); // Muestra solo las que estan pendientes de aprobaci�n.
        //            $conditions_empresa = array('id' => $this->Session->read('Auth.User.empresa_id'));
        //            $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true);
        //        }


        $this->Pedido->set($this->data);
        if (!empty($this->data)) {
            // $conditions = array();
            if (!empty($this->data['Pedido']['pedido_id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['Pedido']['pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['Pedido']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['pedido_estado_pedido'])) {
                $where = "+Pedido+.+pedido_estado_pedido+ = " . $this->data['Pedido']['pedido_estado_pedido'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['Pedido']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['Pedido']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['Pedido']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['guia_despacho'])) {
                $where = "+Pedido+.+guia_despacho+ = '" . $this->data['Pedido']['guia_despacho'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['Pedido']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->set('pedidos', $this->paginate($conditions));
        }

        $this->paginate = array('limit' => 500, 'order' => array(
            'Pedido.id' => 'desc'
        ));
        $this->set('pedidos', $this->paginate($conditions));

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales)); // , 'conditions' => array('Sucursale.estado_sucursal' => true)
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('id' => array('1', '3'))));
        $this->set(compact('empresas', 'estados', 'sucursales', 'tipo_pedido'));
    }

    function aprobar_orden()
    {
        ini_set('memory_limit', '1024M');
        date_default_timezone_set('America/Bogota');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => array('3'), 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => array_unique($empresas_permisos), 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018
        //        if ($this->Session->read('Auth.User.rol_id') == '1') {
        //            $conditions = array('EmpresasAprobadore.user_id' => '1', // $this->Session->read('Auth.User.id'),
        //                'Pedido.pedido_estado' => true,
        //                'Pedido.pedido_estado_pedido' => array('3'/* , '4' */)); // Muestra solo las que estan pendientes de aprobación.
        //
        //            $conditions_empresa = array();
        //            $conditions_sucursales = array('id_empresa !=' => '1', 'Sucursale.estado_sucursal' => true);
        //        } else {
        //            $conditions = array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'),
        //                'Pedido.pedido_estado' => true,
        //                'Pedido.pedido_estado_pedido' => array('3'/* , '4' */)); // Muestra solo las que estan pendientes de aprobación.            
        //            $conditions_empresa = array('id' => $this->Session->read('Auth.User.empresa_id'));
        //            $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true);
        //        }

        $this->Pedido->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Pedido']['pedido_id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['Pedido']['pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['Pedido']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['Pedido']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['Pedido']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['Pedido']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['Pedido']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->set('pedidos', $this->paginate($conditions));
        }

        $this->paginate = array('limit' => 500, 'order' => array(
            'Pedido.id' => 'desc'
        ));
        $this->set('pedidos', $this->paginate($conditions));


        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales)); // , 'conditions' => array('Sucursale.estado_sucursal' => true)

        $this->set(compact('empresas', 'sucursales', 'tipo_pedido'));
    }

    function aprobar_pedido($id = null)
    {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');
        if (!empty($id)) {
            $pedido_detalle = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => '3', 'PedidosDetalle.pedido_id' => $id)));
            /* if (count($pedido_detalle) > 0) { */
            $this->set('detalles', $pedido_detalle);
            $this->set('id', $id);
            /* } else {
              $this->Session->setFlash(__('Las orden de pedido #000' . $id . ' ya ha sido aprobada anteriormente. Verifique el historial de la orden.', true));
              $this->redirect(array('controller' => 'pedidos', 'action' => 'aprobar_orden'));
              } */
        }

        // Consultar la plantilla que tiene el usuario
        $tipo_pedido = $this->Pedido->find('all', array('fields' => 'sucursal_id, tipo_pedido_id', 'conditions' => array('Pedido.id' => $id)));

        // $plantilla = $this->Sucursale->find('all', array('fields' => 'Sucursale.plantilla_id', 'conditions' => array('Sucursale.id' => $this->Session->read('Auth.User.sucursal_id'))));
        $plantilla = $this->SucursalesPlantilla->find('all', array('fields' => 'SucursalesPlantilla.plantilla_id', 'conditions' => array('SucursalesPlantilla.sucursale_id' => $tipo_pedido['0']['Pedido']['sucursal_id'], 'TipoPedido.id' => $tipo_pedido['0']['Pedido']['tipo_pedido_id'])));

        // Consultar los productos relacionados a la plantilla
        $productos_sucursal = $this->PlantillasDetalle->find('list', array('fields' => 'producto_id', 'conditions' => array('PlantillasDetalle.plantilla_id' => $plantilla['0']['SucursalesPlantilla']['plantilla_id'])));

        // Si la sucursal no tiene plantilla, se cargan todos los productos activos.
        if (count($productos_sucursal) > '0') {
            $this->set('productos', $this->Producto->find('all', array('conditions' => array('Producto.id' => $productos_sucursal, 'Producto.estado' => true))));
        } else {
            $this->set('productos', $this->Producto->find('all', array('conditions' => array('Producto.estado' => true))));
        }
    }

    function aprobar_masivo()
    {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');
        $ordenes_aprobadas = '';
        foreach ($this->data['Pedido'] as $key => $value) {
            if ($this->aprobar_pedido_ok($value)) {
                $ordenes_aprobadas = $ordenes_aprobadas . ' #000' . $value . ' ';
            }
        }
        $this->Session->setFlash(__('Las orden de pedido (' . $ordenes_aprobadas . ') se han aprobado masivamente. Nuevo estado: Aprobado.', true));
        $this->redirect(array('controller' => 'pedidos', 'action' => 'aprobar_orden'));
    }

    function aprobar_pedido_ok($id = null)
    {
        date_default_timezone_set('America/Bogota');
        if ($this->RequestHandler->isAjax() || !empty($id)) { //condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['PedidosDetalleId']) || !empty($id)) {
                if (!empty($id)) {
                    $_REQUEST['PedidosDetalleId'] = $id;
                }
                if ($this->Pedido->updateAll(array("Pedido.pedido_estado" => 'true', "Pedido.pedido_estado_pedido" => '4', "Pedido.fecha_aprobado_pedido" => "'" . date('Y-m-d H:i:s') . "'"), array("Pedido.id" => $_REQUEST['PedidosDetalleId']))) {
                    $this->PedidosAuditoria->AuditoriaCambioEstado($_REQUEST['PedidosDetalleId'], '4', $this->Session->read('Auth.User.id'));
                    // $detalles = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $_REQUEST['PedidosDetalleId'])));
                    /*
                     * $asesor = $this->User->find('all', array('fields' => array('User.nombres_persona', 'User.email_persona'), 'conditions' => array('User.id' => $detalles['0']['Empresa']['user_id'])));

                      $week_days = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
                      $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                      $year_now = date("Y");
                      $month_now = date("n");
                      $day_now = date("j");
                      $week_day_now = date("w");
                      $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;

                      $para = $detalles['0']['Empresa']['email_empresa'] . ',' . $asesor['0']['User']['email_persona'];
                      $titulo = 'KOPANCOBA DELIVERY SAS - Aprobado Orden de Pedido: #000' . $detalles['0']['Pedido']['id'];

                      // Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
                      $cabeceras = "Content-type: text/html\r\n";
                      // $cabeceras .= 'To: KOPANCOBA DELIVERY SAS <kopancoba@kopancoba.com>' . "\r\n";
                      $cabeceras .= 'From: KOPANCOBA DELIVERY SAS <kopancoba@kopancoba.com>' . "\r\n";
                      $cabeceras .= 'Cc: ' . $detalles['0']['Empresa']['email_contacto'] . ',' . $detalles['0']['Sucursale']['email_sucursal'] . '  ' . "\r\n";
                      $cabeceras .= 'Bcc: fmontes.botero@gmail.com, heidy.ventas@kopancoba.com' . "\r\n";

                      $mensaje = "
                      <div>" . $date . "</div>
                      <div>&nbsp;</div>
                      <div>Le informamos que la orden de pedido <b>#000" . $detalles['0']['Pedido']['id'] . "</b> ha sido aprobada.</div>
                      <div>&nbsp;</div>
                      <a href='http://www.kopancoba.com/pedidos/pedidos/pedido_pdf/" . $detalles['0']['Pedido']['id'] . "'><b>Orden de Pedido #000" . $detalles['0']['Pedido']['id'] . "</b></a>
                      <div>&nbsp;</div>
                      <div><b>Fecha Pedido:</b> " . $detalles['0']['Pedido']['pedido_fecha'] . "</div>
                      <div><b>Empleado:</b> " . $detalles['0']['User']['nombres_persona'] . "</div>
                      <div><b>Celular Contacto:</b> " . $detalles['0']['User']['celular_persona'] . "</div>
                      <div>&nbsp;</div>
                      <div>El asesor encargado para esta empresa es: <b>" . $asesor['0']['User']['nombres_persona'] . "</b></div>
                      <div>&nbsp;</div>

                      <div>Cordial Saludo,</div>
                      <div>&nbsp;</div>
                      <div>KOPANCOBA DELIVERY SAS - Nit 900.751.920-8</div>
                      <div>Calle 13 No. 28-51</div>
                      <div>Tel: 3512515 3603134</div>
                      <div>&nbsp;</div>
                      <div><b>Esta es una notificación automática, por favor no responda este mensaje</b></div>
                      ";


                      mail($para, $titulo, $mensaje, $cabeceras); */
                    if (!empty($id)) {
                        $this->Session->setFlash(__('La orden de pedido #000' . $id . ' se ha aprobado exitosamente. Nuevo estado: Aprobado.', true));
                        return true;
                    } else {
                        $this->Session->setFlash(__('La orden de pedido #000' . $_REQUEST['PedidosDetalleId'] . ' se ha aprobado exitosamente. Nuevo estado: Aprobado.', true));
                        echo true;
                    }
                } else {
                    if (!empty($id)) {
                        $this->Session->setFlash(__('No se logró aprobar la orden de pedido exitosamente. Por favor intente de nuevo.', true));
                        return false;
                    } else {
                        $this->Session->setFlash(__('No se logró aprobar la orden de pedido exitosamente. Por favor intente de nuevo.', true));
                        echo false;
                    }
                }
            }
        }
    }

    function pedido_pdf($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'pdf';

        $detalles = $this->PedidosDetalle->find('all', array('order' => 'Producto.nombre_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id)));
        $this->set('detalles', $detalles);
    }

    function pedido_pdf_shalom($id = null)
    {

        Configure::write('debug', 0);
        $this->layout = 'pdf';

        $detalles = $this->PedidosDetalle->find('all', array('order' => 'Producto.nombre_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id)));

        $localidad = $this->LocalidadRelRuta->find('first', 
        array(
            "conditions" => [
                        "or" => [
                            "LocalidadRelRuta.id" => $detalles[0]["Sucursale"]["localidad_rel_rutas_id"],
                            "LocalidadRelRuta.codigo_sirbe" => $detalles[0]["Sucursale"]["id"]
                        ]
                    ]
           ));

        $this->set('detalles', $detalles);
        $this->set('localidad', $localidad);
    }

    function pedido_pdf_v2($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'pdf';

        //$detalles = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id)));
        $detalles = $this->PedidosDetalle->find('all', array('order' => 'Producto.nombre_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id)));
        $this->set('detalles', $detalles);
    }

    function pedido_pdf_masivo()
    {
        Configure::write('debug', 0);
        ini_set('memory_limit', 536870912);
        // ini_set('memory_limit', '3072M');
        $this->layout = 'pdf';
        if (count($this->Session->read('Pedido.pdf_masivos')) > 0) {
            $pedidos = $this->Pedido->find('all', array('order' => 'Pedido.id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'Pedido.pedido_estado' => true, 'Pedido.id' => $this->Session->read('Pedido.pdf_masivos'))));
            $this->set('pedidos', $pedidos);

            // $detalles = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pdf_masivos'))));
            $detalles = $this->PedidosDetalle->find('all', array(
                'fields' => 'Pedido.id, Pedido.pedido_estado, PedidosDetalle.pedido_id, PedidosDetalle.pedido_id, PedidosDetalle.cantidad_pedido, PedidosDetalle.observacion_producto, Producto.codigo_producto, Producto.nombre_producto, Producto.medida_producto, Producto.marca_producto',
                'order' => 'Producto.nombre_producto',
                'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pdf_masivos'))
            ));

            $this->set('detalles', $detalles);
        } else {
            $this->set('pedidos', array());
            $this->set('detalles', array());
        }
    }

    function pedido_pdf_masivo_shalom()
    {
        Configure::write('debug', 0);
        ini_set('memory_limit', 536870912);
        // ini_set('memory_limit', '3072M');
        $this->layout = 'pdf';
        if (count($this->Session->read('Pedido.pdf_masivos')) > 0) {
            $pedidos = array();
            $pedidos_data = $this->Pedido->find('all', array('order' => 'Pedido.id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'Pedido.pedido_estado' => true, 'Pedido.id' => $this->Session->read('Pedido.pdf_masivos'))));

            foreach ($pedidos_data as $detalle) {
                $localidad_nombre = $this->LocalidadRelRuta->find('first', array(
                    "conditions" => [
                        "or" => [
                            "LocalidadRelRuta.id" => $detalle["Sucursale"]["localidad_rel_rutas_id"],
                            "LocalidadRelRuta.codigo_sirbe" => $detalle["Sucursale"]["id"]
                        ]
                    ],
                    "fields" => "LocalidadRelRuta.nombre_rel"
                ));
                if ($localidad_nombre) {
                    $detalle["LocalidadRelRuta"] = $localidad_nombre["LocalidadRelRuta"]["nombre_rel"];
                }
                array_push($pedidos, $detalle);
            };

            $this->set('pedidos', $pedidos);

            $detalles = $this->PedidosDetalle->find('all', array(
                'fields' => 'Pedido.id, Pedido.pedido_estado, PedidosDetalle.pedido_id, PedidosDetalle.pedido_id, PedidosDetalle.cantidad_pedido, PedidosDetalle.observacion_producto, Producto.codigo_producto, Producto.nombre_producto, Producto.medida_producto, Producto.marca_producto,PedidosDetalle.lote,PedidosDetalle.fecha_expiracion',
                'order' => 'Producto.nombre_producto',
                'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $this->Session->read('Pedido.pdf_masivos'))
            ));

            $this->set('detalles', $detalles);
        } else {
            $this->set('pedidos', array());
            $this->set('detalles', array());
        }
    }

    function search_orden()
    {
        ini_set('memory_limit', '1024M');

        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'DISTINCT EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => array_unique($empresas_permisos), 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => array_unique($empresas_permisos));
        $conditions_sucursales = array('Sucursale.id' => array_unique($sucursales_permisos), 'Sucursale.estado_sucursal' => true);

        $this->Pedido->set($this->data);
        if (!empty($this->data)) {

            if (!empty($this->data['Pedido']['pedido_id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['Pedido']['pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['Pedido']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['fecha_entregado'])) {
                $where = "+Pedido+.+fecha_entregado+ = '" . $this->data['Pedido']['fecha_entregado'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['pedido_estado_pedido'])) {
                $where = "+Pedido+.+pedido_estado_pedido+ = " . $this->data['Pedido']['pedido_estado_pedido'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['Pedido']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['Pedido']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['Pedido']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['guia_despacho'])) {
                $where = "+Pedido+.+guia_despacho+ = '" . $this->data['Pedido']['guia_despacho'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Pedido']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['Pedido']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->paginate = array('limit' => 500, 'order' => array(
                'Pedido.id' => 'desc'
            ));
            $this->set('pedidos', $this->paginate($conditions));
        } else {
            $this->Pedido->recursive = 0;
            $this->helpers['Paginator'] = array('ajax' => 'Ajax');
            $this->paginate = array('limit' => 100, 'order' => array(
                'Pedido.id' => 'desc'
            ));
            $this->set('pedidos', $this->paginate($conditions));
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales)); //, 'conditions' => array('Sucursale.estado_sucursal' => true)
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $this->set(compact('estados', 'sucursales', 'tipo_pedido', 'empresas'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function despacho()
    {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');

        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => array('4'), 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => array_unique($empresas_permisos), 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => array_unique($empresas_permisos));
        $conditions_sucursales = array('Sucursale.id' => array_unique($sucursales_permisos), 'Sucursale.estado_sucursal' => true);


        $this->Pedido->set($this->data);
        if (!empty($this->data['Pedido'])) {
            $ordenes_despachadas = '';
            $ordenes_facturadas = '';
            $despachadas = 0;
            $facturadas = 0;
            if ($this->Session->read('Auth.User.rol_id') == '1' || $this->Session->read('Auth.User.rol_id') == '6' || $this->Session->read('Auth.User.rol_id') == '7') {
                foreach ($this->data['Pedido'] as $key => $value) {
                    if ($value > 0) {
                        if (!empty($this->data['Pedido']['guia_' . $key])) {

                            if ($this->Pedido->updateAll(array("Pedido.pedido_estado" => 'true', "Pedido.pedido_estado_pedido" => '5', "Pedido.fecha_despacho" => "'" . date('Y-m-d H:i:s') . "'", "Pedido.guia_despacho" => "'" . $this->data['Pedido']['guia_' . $key] . "'", "Pedido.numero_factura" => $this->Session->read('Auth.User.id')), array("Pedido.id" => $value, 'Pedido.pedido_estado_pedido' => '4'))) {
                                $this->PedidosAuditoria->AuditoriaCambioEstado($value, '5', $this->Session->read('Auth.User.id'));
                                $ordenes_despachadas = $ordenes_despachadas . ' #000' . $value . ' ';
                                $despachadas++;
                            }
                        }
                    }

                    // Facturadas 
                    /* if (!empty($this->data['Pedido']['factura_' . $key])) {
                      //echo $this->data['Pedido']['guia_' . $key];
                      if ($this->Session->read('Auth.User.rol_id') == '1') {
                      if ($this->Pedido->updateAll(array("Pedido.fecha_factura" => "'" . date('Y-m-d H:i:s') . "'", "Pedido.numero_factura" => "'" . $this->data['Pedido']['factura_' . $key] . "'"), array("Pedido.id" => $value))) {
                      $ordenes_facturadas = $ordenes_facturadas . ' #000' . $value . ' ';
                      $facturadas++;
                      }
                      } else {
                      $this->Session->setFlash(__('Para aprobar ordenes debe ser Administrador del sistema.', true));
                      exit;
                      }
                      } */
                }
            } else {
                $this->Session->setFlash(__('Para aprobar ordenes debe ser Administrador del sistema, Compras o Asistente de Logisitica.', true));
            }

            if ($despachadas > 0) {
                $this->Session->setFlash(__('Las orden de pedido (' . $ordenes_despachadas . ') han sido despachadas. Nuevo estado: Despachado. <br> Las ordenes sin No. de Guia NO fueron despachadas.<br>', true));
            } /* else {
                $this->Session->setFlash(__('Las ordenes sin No. de Guia NO pueden ser despachadas.', true));
            } */
        }


        if (!empty($this->data['PedidoDespacho'])) {
            // $conditions = array('Pedido.pedido_estado_pedido' => '4', 'EmpresasAprobadore.user_id' => '1'/* $this->Session->read('Auth.User.id') */);


            if (!empty($this->data['PedidoDespacho']['id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['PedidoDespacho']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['PedidoDespacho']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['PedidoDespacho']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['PedidoDespacho']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['PedidoDespacho']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['PedidoDespacho']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        $this->Pedido->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->paginate = array('limit' => 250, 'order' => array(
            'Pedido.id' => 'desc'
        ));
        $this->set('pedidos', $this->paginate($conditions));

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $this->set(compact('estados', 'sucursales', 'tipo_pedido', 'empresas'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function entregas_parciales($id = null)
    {
        if (!empty($id)) {
            $pedido_detalle = $this->PedidosDetalle->find('all', array('order' => 'TipoCategoria.tipo_categoria_orden,Producto.codigo_producto', 'conditions' => array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => '4', 'PedidosDetalle.pedido_id' => base64_decode($id))));
            $this->set('detalles', $pedido_detalle);
            $this->set('id', base64_decode($id));
        }

        if (!empty($this->data)) {
            if (!empty($this->data['PedidosDetalle']['id'])) {
                $id_detalle = $this->data['PedidosDetalle']['id'];
                $cantidad_parcial = $this->data['PedidosDetalle']['cantidad_pedido_parcial_' . $id_detalle];
                if (!empty($cantidad_parcial) && $cantidad_parcial > 0) {
                    if ($this->PedidosDetalle->updateAll(array("PedidosDetalle.fecha_pedido_parcial" => 'now()', "PedidosDetalle.cantidad_pedido_parcial" => $cantidad_parcial), array("PedidosDetalle.id" => $id_detalle))) {
                        $this->Session->setFlash(__('Se actualizo la cantidad parcial de entrega para el producto.', true));
                        $this->redirect(array('action' => 'entregas_parciales/' . $id));
                    }
                } else {
                    $this->Session->setFlash(__('Debe colocar una cantidad faltante para el producto.', true));
                }
            }
            if (!empty($this->data['PedidosDetalle']['id_pedido'])) {
                $sql_entregas_parciales = "SELECT pedidos_entregas_parciales(" . $this->data['PedidosDetalle']['id_pedido'] . "," . $this->Session->read('Auth.User.id') . ");";
                //$this->Pedido->query($sql_entregas_parciales);

                $viejo_pedido = $this->Pedido->find("first", array("conditions" => ["Pedido.id" => $this->data['PedidosDetalle']['id_pedido']]));

                $nuevo_pedido = $this->Pedido->find("first", array(
                    "conditions" => ["pedido_id" => $this->data['PedidosDetalle']['id_pedido']],
                    "fields" => ["Pedido.id"]
                ));

                $this->Pedido->save(array(
                    "Pedido" => array(
                        "id" => $nuevo_pedido["Pedido"]["id"],
                        "contrato" => $viejo_pedido["Pedido"]["numero_contrato"],
                        "consecutivo" => $viejo_pedido["Pedido"]["consecutivo"],
                    )
                ));

                $this->redirect(array('action' => 'ver_pedido/' . $nuevo_pedido['Pedido']['id']));
            }
        }
    }

    function entregas_parciales_refresh($id = null)
    {
        if (!empty($id)) {
            $pedido_detalle = $this->PedidosDetalle->find('all', array('fields' => 'PedidosDetalle.pedido_id', 'groupby' => 'PedidosDetalle.pedido_id', 'conditions' => array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => '4', 'PedidosDetalle.id' => $id)));
            if ($this->PedidosDetalle->updateAll(array("PedidosDetalle.fecha_pedido_parcial" => null, "PedidosDetalle.cantidad_pedido_parcial" => null), array("PedidosDetalle.id" => $id))) {
                $this->Session->setFlash(__('Se restauro la cantidad parcial de entrega para el producto.', true));
                $this->redirect(array('action' => 'entregas_parciales/' . base64_encode($pedido_detalle['0']['PedidosDetalle']['pedido_id'])));
            }
        }
    }

    /* ENTREGADO */

    function entregado()
    {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('Pedido.pedido_estado' => true, 'Pedido.pedido_estado_pedido' => array('5'), 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => array_unique($empresas_permisos), 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => array_unique($empresas_permisos),);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018

        $ordenes_despachadas = '';
        $ordenes_facturadas = '';
        $despachadas = 0;
        $facturadas = 0;

        $this->Pedido->set($this->data);
        if (!empty($this->data['Pedido'])) {
            foreach ($this->data['Pedido'] as $key => $value) {
                if ($value > 0) {
                    if (!empty($this->data['Pedido']['fecha_entregado_' . $key])) {
                        //echo $this->data['Pedido']['guia_' . $key];
                        if ($this->Pedido->updateAll(array("Pedido.pedido_estado" => 'true', "Pedido.pedido_estado_pedido" => '6', "Pedido.fecha_entregado" => "'" . $this->data['Pedido']['fecha_entregado_' . $key] . "'"), array("Pedido.id" => $value, 'Pedido.pedido_estado_pedido' => '5'))) {
                            $this->PedidosAuditoria->AuditoriaCambioEstado($value, '6', $this->Session->read('Auth.User.id'));
                            $ordenes_despachadas = $ordenes_despachadas . ' #000' . $value . ' ';
                            $despachadas++;
                        }
                    }

                    $dir_arc = 'pedidos/cumplidos/';
                    $max_file = 3145728; // 3 MB = 3145728 byte
                    // Verificar que se haya cargado un archivo	
                    if (!empty($this->data['Pedido']['archivo_cumplido_' . $key]['name'])) {
                        // Verificar si el archivo tiene formato .jpg o pdf
                        if ($this->data['Pedido']['archivo_cumplido_' . $key]['type'] == 'image/jpeg' || $this->data['Pedido']['archivo_cumplido_' . $key]['type'] == 'image/png' || $this->data['Pedido']['archivo_cumplido_' . $key]['type'] == 'application/pdf') {
                            // Verificar el tamaño del archivo

                            if ($this->data['Pedido']['archivo_cumplido_' . $key]['size'] < $max_file) {
                                move_uploaded_file($this->data['Pedido']['archivo_cumplido_' . $key]['tmp_name'], $dir_arc . '/' . $this->data['Pedido']['archivo_cumplido_' . $key]['name']);
                                $aux = explode('.', $this->data['Pedido']['archivo_cumplido_' . $key]['name']);
                                rename($dir_arc . $this->data['Pedido']['archivo_cumplido_' . $key]['name'], $dir_arc . $key . '_' . $this->data['Pedido']['guia_' . $key] . '.' . $aux[1]);
                                $archivo_cumplido = $dir_arc . $key . '_' . $this->data['Pedido']['guia_' . $key] . '.' . $aux[1];
                                if ($this->Pedido->updateAll(array("Pedido.archivo_cumplido" => "'" . $archivo_cumplido . "'"), array("Pedido.id" => $value, 'Pedido.pedido_estado_pedido' => '6'))) {
                                }
                            }
                        }
                    }
                }
            }



            if ($despachadas > 0) {
                $this->Session->setFlash(__('Las orden de pedido (' . $ordenes_despachadas . ') han sido entregadas. Nuevo estado: Entregado. <br> Las ordenes sin fecha de entrega NO fueron marcadas.<br>', true));
            } else {
                $this->Session->setFlash(__('Las ordenes sin fecha de entrega NO pueden ser cambiar de estado.', true));
            }
        }

        if (!empty($this->data['PedidoDespacho'])) {
            // $conditions = array('Pedido.pedido_estado_pedido' => '5', 'EmpresasAprobadore.user_id' => '1'/* $this->Session->read('Auth.User.id') */);
            if (!empty($this->data['PedidoDespacho']['id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['PedidoDespacho']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['PedidoDespacho']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['PedidoDespacho']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['PedidoDespacho']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['PedidoDespacho']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['PedidoDespacho']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        //else {
        //    $conditions = array('Pedido.pedido_estado_pedido' => '5', 'EmpresasAprobadore.user_id' => '1' /* $this->Session->read('Auth.User.id') */);
        // } //31052018

        $this->Pedido->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->paginate = array('limit' => 250, 'order' => array(
            'Pedido.id' => 'desc'
        ));
        $this->set('pedidos', $this->paginate($conditions));

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));

        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales)); //, 'conditions' => array('Sucursale.estado_sucursal' => true)

        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));

        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }

        $this->set(compact('estados', 'sucursales', 'tipo_pedido', 'empresas', 'regional'));
    }

    /* FIN ENTREGADO */

    function copiar_pedido($id = null)
    {
        date_default_timezone_set('America/Bogota');
        if (!empty($id)) {
            $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id))));
            $this->set('id', $id);
        }
        if (!empty($this->data)) {
            $id = $this->data['Pedido']['id'];
            $empresa_id = $this->data['Pedido']['empresa_id'];
            $sucursal_id = $this->data['Pedido']['sucursal_id'];
            /* 2020-01-31 - pedido_fecha_creacion */
            $copiar_pedido = "INSERT INTO pedidos (
                                empresa_id,
                                sucursal_id ,
                                pedido_direccion ,
                                pedido_telefono ,
                                pedido_valor_total ,
                                pedido_fecha ,
                                pedido_hora ,
                                pedido_estado,
                                pedido_estado_pedido,
                                user_id,
                                departamento_id,
                                municipio_id,
                                observaciones,
                                fecha_aprobado_pedido,
                                fecha_orden_pedido,
                                fecha_despacho,
                                pedido_id,
                                guia_despacho,
                                tipo_pedido_id,
                                tipo_movimiento_id,
                                pedido_fecha_creacion) 
                                (
                                SELECT 
                                " . $empresa_id . ",
                                " . $sucursal_id . " ,
                                pedido_direccion ,
                                pedido_telefono ,
                                pedido_valor_total ,
                                CURRENT_DATE as pedido_fecha ,
                                CURRENT_TIME as pedido_hora ,
                                true as pedido_estado,
                                3 as pedido_estado_pedido,
                                " . $this->Session->read('Auth.User.id') . " as user_id,
                                departamento_id,
                                municipio_id,
                                observaciones,
                                null as fecha_aprobado_pedido,
                                now() as fecha_orden_pedido,
                                null as fecha_despacho,
                                " . $id . " as pedido_id,
                                null, 
                                tipo_pedido_id,
                                tipo_movimiento_id,
                                " . date('Y-m-d H:i:s') . "
                                FROM pedidos
                                WHERE id = " . $id . ");";
            $this->Pedido->query($copiar_pedido);



            $consultar_nuevo = "SELECT pedidos.id, max(fecha_orden_pedido) 
                                FROM pedidos WHERE pedidos.pedido_id = " . $id . "
                                AND pedido_id IS NOT NULL
                                GROUP BY pedidos.id
                                ORDER BY max(fecha_orden_pedido) DESC
                                LIMIT 1;";
            $result_nuevo = $this->Pedido->query($consultar_nuevo);
            $id_nuevo = $result_nuevo['0']['0']['id'];
            $this->PedidosAuditoria->AuditoriaCambioEstado($id_nuevo, '3', $this->Session->read('Auth.User.id'), 'Copiar Pedido #000' . $id);

            $copiar_detalles = "INSERT INTO pedidos_detalles 
                                (producto_id,
                                tipo_categoria_id,
                                cantidad_pedido,
                                pedido_id ,
                                iva_producto,
                                medida_producto,
                                precio_producto,
                                fecha_pedido_detalle)
                                (SELECT  producto_id,
                                tipo_categoria_id,
                                cantidad_pedido,
                                " . $id_nuevo . " as pedido_id ,
                                iva_producto,
                                medida_producto,
                                precio_producto,
                                now() as fecha_pedido_detalle
                                FROM pedidos_detalles
                                WHERE pedido_id = " . $id . ");";
            $this->Pedido->query($copiar_detalles);

            /* $diferencia = "SELECT pedidos_detalles.id as detalle_id, 
              pedidos.id,
              plantillas_detalles.producto_id,
              plantillas_detalles.precio_producto,
              plantillas_detalles.iva_producto,
              plantillas_detalles.tipo_categoria_id
              FROM pedidos, pedidos_detalles, plantillas_detalles
              where pedidos.id = pedidos_detalles.pedido_id
              and pedidos_detalles.producto_id = plantillas_detalles.producto_id
              and  plantillas_detalles.precio_producto <> pedidos_detalles.precio_producto
              and pedidos.id = " . $id_nuevo . "
              and pedidos.pedido_id = " . $id . ";";
              $data_pedidos = $this->Pedido->query($diferencia);

              foreach ($data_pedidos as $data_pedido) :
              $update = "UPDATE pedidos_detalles SET
              precio_producto = " . $data_pedido['0']['precio_producto'] . ",
              iva_producto = " . $data_pedido['0']['iva_producto'] . ",
              tipo_categoria_id = " . $data_pedido['0']['tipo_categoria_id'] . "
              WHERE id = " . $data_pedido['0']['detalle_id'] . ";";
              $this->Pedido->query($update);
              endforeach; */
            $this->Session->setFlash(__('Se ha realizado el copiado exitoso de la orden de pedido (#000' . $id_nuevo . ')', true));
            $this->set('id_nuevo', $id_nuevo);
            $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id_nuevo))));
        }
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa'));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $this->set(compact('empresas', 'sucursales'));
    }

    function rotulos($id = null)
    {
        $this->layout = 'none';

        $id = $this->params['url']['id'];
        $rotulos = $this->params['url']['rotulos'];

        if (!empty($id) && !empty($rotulos)) {
            $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado' => true, 'PedidosDetalle.pedido_id' => $id))));
            $this->set('rotulos', $rotulos);
        }
    }

    function facturado()
    {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');
        $pedido_inicial = 18139;
        $this->Pedido->set($this->data);
        if (!empty($this->data['Pedido'])) {
            $ordenes_despachadas = '';
            $ordenes_facturadas = '';
            $despachadas = 0;
            $facturadas = 0;
            foreach ($this->data['Pedido'] as $key => $value) {
                if ($value > 0) {
                    // Facturadas 
                    if (!empty($this->data['Pedido']['factura_' . $key])) {
                        //echo $this->data['Pedido']['guia_' . $key];
                        if ($this->Session->read('Auth.User.rol_id') == '1') {
                            if ($this->Pedido->updateAll(array("Pedido.fecha_factura" => "'" . date('Y-m-d H:i:s') . "'", "Pedido.numero_factura" => "'" . $this->data['Pedido']['factura_' . $key] . "'"), array("Pedido.id" => $value, 'Pedido.pedido_estado_pedido' => '5'))) {
                                $ordenes_facturadas = $ordenes_facturadas . ' #000' . $value . ' ';
                                $facturadas++;
                            }
                        } else {
                            $this->Session->setFlash(__('Para aprobar ordenes debe ser Administrador del sistema.', true));
                            exit;
                        }
                    }
                }
            }
            if ($facturadas > 0) {
                $this->Session->setFlash(__('Las ordenes sin No. de Factura NO fueron facturadas.<br> Las orden de pedido (' . $ordenes_facturadas . ') han sido facturadas. <br>', true));
            } else {
                $this->Session->setFlash(__('Las ordenes sin No. de Factura NO pueden ser facturadas.', true));
            }
        }


        if (!empty($this->data['PedidoDespacho'])) {
            $conditions = array('Pedido.pedido_estado_pedido' => '5', 'EmpresasAprobadore.user_id' => '1', 'Pedido.id >' => $pedido_inicial);
            if (!empty($this->data['PedidoDespacho']['id'])) {
                $where = "+Pedido+.+id+ = " . $this->data['PedidoDespacho']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['pedido_fecha'])) {
                $where = "+Pedido+.+pedido_fecha+ = '" . $this->data['PedidoDespacho']['pedido_fecha'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['empresa_id'])) {
                $where = "+Pedido+.+empresa_id+ = " . $this->data['PedidoDespacho']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['sucursal_id'])) {
                $where = "+Pedido+.+sucursal_id+ = " . $this->data['PedidoDespacho']['sucursal_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['numero_factura'])) {
                $where = "+Pedido+.+numero_factura+ = '" . $this->data['PedidoDespacho']['numero_factura'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['fecha_factura'])) {
                $where = "+Pedido+.+fecha_factura+::date = '" . $this->data['PedidoDespacho']['fecha_factura'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['regional_sucursal'])) {
                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['PedidoDespacho']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['PedidoDespacho']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        } else {
            $conditions = array('Pedido.pedido_estado_pedido' => '5', 'EmpresasAprobadore.user_id' => '1', 'Pedido.id >' => $pedido_inicial);
        }
        $this->Pedido->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->paginate = array('limit' => 500, 'order' => array(
            'Pedido.numero_factura' => 'desc'
        ));
        $this->set('pedidos', $this->paginate($conditions));

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa'));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('id_empresa !=' => '1', 'Sucursale.estado_sucursal' => true))); //, 'conditions' => array('Sucursale.estado_sucursal' => true)
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $this->set(compact('estados', 'sucursales', 'tipo_pedido', 'empresas'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function auditorias($id = null)
    {
        $auditorias = $this->PedidosAudit->find('all', array('conditions' => array('PedidosAudit.pedido_id' => base64_decode($id))));
        $this->set('auditorias', $auditorias);
    }
}
