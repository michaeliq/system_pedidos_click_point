<?php

class PlantillasController extends AppController {

    var $name = 'Plantillas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Plantilla', 'PlantillasDetalle', 'Producto', 'TipoCategoria', 'TipoPedido', 'Empresa', 'EmpresasAprobadore', 'Regionale', 'PlantillasAudit');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Plantillas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Plantillas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function view($id = null) {
        $auditorias = $this->PlantillasAudit->find('all', array('conditions' => array('PlantillasAudit.plantilla_id' => base64_decode($id))));
        $this->set('auditorias', $auditorias);
    }

    function index() {
        // Configure::write('debug', 2);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');

        //31052018
        $empresas_permisos = array();
        //$sucursales_permisos = array();
        $regionales_permisos = array();

        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions = array();
            $conditions_empresa = array('Empresa.estado_empresa' => true);
        } else {
            // $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
            $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.regional_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));

            foreach ($permisos as $permiso) {
                array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
                // array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
                array_push($regionales_permisos, $permiso['EmpresasAprobadore']['regional_id']);
            }

            $conditions = array('Plantilla.empresa_id' => $empresas_permisos/* , 'Plantilla.sucursal_id' => $regionales_permisos */);
            $conditions_empresa = array('Empresa.estado_empresa' => true, 'id' => $empresas_permisos);
        }

        // $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        $conditions_regionales = array('Regionale.id' => $regionales_permisos, 'Regionale.estado' => true);
        //31052018
        $this->Plantilla->set($this->data);
        if (!empty($this->data)) {
            // $conditions = array();
            if (!empty($this->data['Plantilla']['id'])) {
                $where = "+Plantilla+.+id+ = '" . $this->data['Plantilla']['id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Plantilla']['empresa_id'])) {
                $where = "+Plantilla+.+empresa_id+ = '" . $this->data['Plantilla']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Plantilla']['regional_id'])) {
                $where = "+Plantilla+.+sucursal_id+ = '" . $this->data['Plantilla']['regional_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Plantilla']['estado_plantilla'])) {
                $where = "+Plantilla+.+estado_plantilla+ = '" . $this->data['Plantilla']['estado_plantilla'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Plantilla']['plantilla_base'])) {
                $where = "+Plantilla+.+plantilla_base+ = '" . $this->data['Plantilla']['plantilla_base'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->Plantilla->recursive = 0;
            $this->paginate = array('limit' => 100, 'order' => array('Plantilla.empresa_id' => 'asc'));
            $this->helpers['Paginator'] = array('ajax' => 'Ajax');
            $this->set('plantillas', $this->paginate($conditions));
        } else {
            $conditions = array('Plantilla.estado_plantilla' => true); // 
            $this->Plantilla->recursive = 0;
            $this->paginate = array('limit' => 100, 'order' => array('Plantilla.plantilla_base' => 'asc'));
            $this->helpers['Paginator'] = array('ajax' => 'Ajax');
            $this->set('plantillas', $this->paginate($conditions));
        }

        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', array('conditions' => $conditions_regionales)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        // print_r($empresas);
        $this->set(compact('empresas', 'regionales', 'estados'));
    }

    function add() {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        date_default_timezone_set('America/Bogota');

        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        $regionales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
            array_push($regionales_permisos, $permiso['EmpresasAprobadore']['regional_id']);
        }

        $conditions = array('Plantilla.empresa_id' => $empresas_permisos, 'Plantilla.sucursal_id' => $regionales_permisos);
        $conditions_empresa = array('Empresa.estado_empresa' => true, 'id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        $conditions_regionales = array('Regionale.id' => $regionales_permisos, 'Regionale.estado' => true);
        //31052018
        // Se consultan todos los productos activos en el sistema.
        $productos = $this->Producto->find('all', array('order' => 'Producto.proveedor_producto, Producto.codigo_producto', 'conditions' => array('Producto.estado' => true, 'Producto.mostrar_producto' => true)));
        $this->set('productos', $productos);

        if (!empty($this->data)) {
            $this->Plantilla->create();

            if (!empty($this->data['Plantilla']['plantilla_id'])) {
                $this->data['Plantilla']['plantilla_base'] = false;
            }

            // Si es una sub-plantilla va a tener la información de la plantilla base
            if (!empty($this->data['Plantilla']['plantilla_id'])) {
                $this->data['Plantilla']['plantilla_base_id'] = $this->data['Plantilla']['plantilla_id'];
            } else {
                $this->data['Plantilla']['plantilla_base_id'] = null;
            }

            // Se guarda la información de la plantilla
            if ($this->Plantilla->save($this->data)) {
                $plantilla_id = $this->Plantilla->getInsertID();

                if (!empty($this->data['Plantilla']['plantilla_id'])) {
                    $sql_detalles = "INSERT INTO plantillas_detalles (plantilla_id, producto_id, fecha_creacion, precio_producto, iva_producto, medida_producto, tipo_categoria_id, fecha_creacion_timestamp, precio_producto_2, plantilla_base, precio_producto_bs_2)
                        SELECT " . $plantilla_id . ", producto_id, NOW(), precio_producto, iva_producto, medida_producto, tipo_categoria_id, NOW(), precio_producto_2, " . $this->data['Plantilla']['plantilla_id'] . ", precio_producto_bs_2
                        FROM plantillas_detalles
                        WHERE plantilla_id = " . $this->data['Plantilla']['plantilla_id'] . ";";
                    $this->Plantilla->query($sql_detalles);
                    $this->Session->setFlash(__('Se agregaron los productos a la plantilla (' . $this->data['Plantilla']['nombre_plantilla'] . ') segun la plantilla base seleccionada.', true));
                    if (!empty($this->data['Plantilla']['plantilla_id'])) {
                        $this->redirect(array('controller' => 'plantillas', 'action' => 'edit/' . $plantilla_id));
                    } else {
                        $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
                    }
                    exit;
                }

                // Por cada producto seleccionado, se agrega al detalle de la plantilla
                foreach ($productos as $producto) :
                    $this->PlantillasDetalle->create();
                    if ($this->data['Plantilla'][$producto['Producto']['id']] > 0) {
                        $detalle_plantilla = array('plantilla_id' => $plantilla_id,
                            'producto_id' => $producto['Producto']['id'],
                            'precio_producto' => $this->data['Plantilla']['precio_producto_' . $producto['Producto']['id']],
                            'precio_producto_2' => $this->data['Plantilla']['precio_producto_2_' . $producto['Producto']['id']], //31052018
                            'precio_producto_bs_2' => $this->data['Plantilla']['precio_producto_bs_2_' . $producto['Producto']['id']], //31052018
                            'iva_producto' => $producto['Producto']['iva_producto'], //$this->data['Plantilla']['iva_producto_' . $producto['Producto']['id']],
                            'medida_producto' => $producto['Producto']['medida_producto'], //$this->data['Plantilla']['medida_producto_' . $producto['Producto']['id']],
                            'tipo_categoria_id' => $producto['Producto']['tipo_categoria_id']//$this->data['Plantilla']['tipo_categoria_id_' . $producto['Producto']['id']]
                        );
                        $this->PlantillasDetalle->save($detalle_plantilla, FALSE);
                    }
                endforeach;
                $this->Session->setFlash(__('Se agregaron los productos a la plantilla (' . $this->data['Plantilla']['nombre_plantilla'] . ').', true));
                if (!empty($this->data['Plantilla']['plantilla_id'])) {
                    $this->redirect(array('controller' => 'plantillas', 'action' => 'edit/' . $plantilla_id));
                } else {
                    $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('La plantilla no se puede salvar, verifique los campos obligatorios e intente de nuevo.', true));
            }
        }
        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', array('conditions' => $conditions_regionales)));
        $plantillas_base = $this->Plantilla->find('list', array('fields' => 'Plantilla.nombre_plantilla', 'order' => 'Plantilla.nombre_plantilla', 'conditions' => array('Plantilla.estado_plantilla' => true, 'Plantilla.plantilla_base' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $unidadMedida = array(
            'UNI' => 'UNIDAD',
            'GAL' => 'GALON',
            'LIT' => 'LITRO',
            'CUN' => 'CUNETE',
            'PAQ X 6' => 'PAQUETE X 6',
            'PAQ' => 'PAQUETE',
            'MTS' => 'METROS',
            'KG' => 'KILOS',
            'LB' => 'LIBRA',
            'GR' => 'GRAMOS',
            'CAJ' => 'CAJAS',
            'PAR' => 'PAR');
        $this->set(compact('tipoCategoria', 'unidadMedida', 'tipoPedido', 'empresas', 'plantillas_base', 'regionales'));
    }

    function edit($id = null) {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        date_default_timezone_set('America/Bogota');

        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        $regionales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
            array_push($regionales_permisos, $permiso['EmpresasAprobadore']['regional_id']);
        }

        $conditions = array('Plantilla.empresa_id' => $empresas_permisos, 'Plantilla.sucursal_id' => $regionales_permisos);
        $conditions_empresa = array('Empresa.estado_empresa' => true, 'id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        $conditions_regionales = array('Regionale.id' => $regionales_permisos, 'Regionale.estado' => true);
        //31052018
        // Se consulta los productos que están en la plantilla base
        $plantilla_base = $this->Plantilla->find('all', array('fields' => 'plantilla_base_id', 'conditions' => array('Plantilla.id' => $id)));
        if (!empty($plantilla_base[0]['Plantilla']['plantilla_base_id'])) {
            $plantilla_base_id = $plantilla_base[0]['Plantilla']['plantilla_base_id'];
            $productos_base = $this->PlantillasDetalle->find('list', array('fields' => 'producto_id', 'conditions' => array('PlantillasDetalle.plantilla_id' => $plantilla_base_id)));
        } else {
            $plantilla_base_id = $id;
            $productos_base = array();
        }

        // Insertar en la plantilla sub base, los productos nuevos de la base
        $sub_bases = $this->Plantilla->find('all', array('fields' => 'id, plantilla_base_id', 'conditions' => array('Plantilla.plantilla_base_id' => $plantilla_base_id)));
        foreach ($sub_bases as $sub_base):
            $nuevos_productos = "INSERT INTO plantillas_detalles (plantilla_id, producto_id, precio_producto, precio_producto_2, precio_producto_bs_2, iva_producto, medida_producto, tipo_categoria_id, plantilla_base)
			(SELECT " . $sub_base['Plantilla']['id'] . ", producto_id, precio_producto, precio_producto_bs_2, precio_producto_2, iva_producto, medida_producto, tipo_categoria_id, " . $plantilla_base_id . "
			FROM plantillas_detalles WHERE plantilla_id = " . $plantilla_base_id . " AND producto_id NOT IN (SELECT producto_id FROM plantillas_detalles WHERE plantilla_id IN (SELECT id FROM plantillas WHERE id = " . $sub_base['Plantilla']['id'] . " AND plantilla_base_id = " . $plantilla_base_id . ")));";
            // $this->Plantilla->query($nuevos_productos);
        endforeach;
        //print_r($sub_bases);		
        // Se consultan todos los productos activos en el sistema.
        if (count($productos_base) > 0) {
            $productos = $this->Producto->find('all', array('order' => 'Producto.proveedor_producto, Producto.codigo_producto', 'conditions' => array('Producto.id' => $productos_base, 'Producto.estado' => true, 'Producto.mostrar_producto' => true)));
        } else {
            $productos = $this->Producto->find('all', array('order' => 'Producto.proveedor_producto, Producto.codigo_producto', 'conditions' => array('Producto.estado' => true, 'Producto.mostrar_producto' => true)));
        }
        $this->set('productos', $productos);

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró la plantilla', true));
            /* $this->redirect(array('action' => 'index')); */header("Location: index");
            exit;
        }

        if (!empty($this->data)) {
            if ($this->Plantilla->save($this->data)) {
                $delete = "DELETE FROM plantillas_detalles WHERE plantillas_detalles.plantilla_id = " . $id . "";
                $this->PlantillasDetalle->query($delete);

                $plantilla_id = $id;

                // Por cada producto seleccionado, se agrega al detalle de la plantilla
                $detalle_plantilla = array();
                foreach ($productos as $producto) :
                    $this->PlantillasDetalle->create();
                    if ($this->data['Plantilla'][$producto['Producto']['id']] > 0) {
                        $detalle_plantilla = array('plantilla_id' => $plantilla_id,
                            'producto_id' => $producto['Producto']['id'],
                            'precio_producto' => $this->data['Plantilla']['precio_producto_' . $producto['Producto']['id']],
                            'precio_producto_2' => $this->data['Plantilla']['precio_producto_2_' . $producto['Producto']['id']], //31052018
                            'precio_producto_bs_2' => $this->data['Plantilla']['precio_producto_bs_2_' . $producto['Producto']['id']], //31052018
                            'iva_producto' => $producto['Producto']['iva_producto'], //$this->data['Plantilla']['iva_producto_' . $producto['Producto']['id']],
                            'medida_producto' => $producto['Producto']['medida_producto'], //$this->data['Plantilla']['medida_producto_' . $producto['Producto']['id']],
                            'tipo_categoria_id' => $producto['Producto']['tipo_categoria_id'], //$this->data['Plantilla']['tipo_categoria_id_' . $producto['Producto']['id']]
                            'plantilla_base' => $plantilla_base_id
                        );
                        // print_r($detalle_plantilla);
                        $this->PlantillasDetalle->save($detalle_plantilla, FALSE);
                        // auditoria
                        $this->PlantillasAudit->create();
                        $detalle_auditoria = array('plantilla_id' => $plantilla_id,
                            'fecha_cambio_estado' => date('Y-m-d H:i:s'),
                            'user_id' => $this->Session->read('Auth.User.id'),
                            'observaciones' => implode(" ", $detalle_plantilla)
                        );
                        $this->PlantillasAudit->save($detalle_auditoria, FALSE);
                    }

                endforeach;

                if (!empty($plantilla_base_id)) {
                    //  Tomar los datos de la plantilla cuando es sub-base
                    $update_plantillas = "UPDATE plantillas_detalles 
                    SET precio_producto = plantillas_detalles2.precio_producto,
                    iva_producto = plantillas_detalles2.iva_producto,
                    medida_producto = plantillas_detalles2.medida_producto,
                    tipo_categoria_id = plantillas_detalles2.tipo_categoria_id,
                    fecha_actualizacion_timestamp = now()
                    FROM plantillas_detalles as plantillas_detalles2 
                    WHERE plantillas_detalles2.plantilla_id = " . $plantilla_base_id . " -- base
                    AND plantillas_detalles.plantilla_id IN (SELECT id FROM plantillas WHERE plantilla_base_id = " . $plantilla_base_id . ")
                    AND plantillas_detalles.producto_id  = plantillas_detalles2.producto_id;";
                    $this->Plantilla->query($update_plantillas);
                }
                /*
                 * CUANDO SE INSERTE UNO NUEVO 
                  SELECT * FROM plantillas_detalles
                  WHERE plantilla_id = 59
                  AND producto_id NOT IN (SELECT producto_id FROM plantillas_detalles WHERE plantilla_id IN (SELECT id FROM plantillas WHERE plantilla_base_id = 59));
                 *  */
                $this->Session->setFlash(__('Se agregaron los productos a la plantilla (' . $this->data['Plantilla']['nombre_plantilla'] . ').', true));
                $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Plantilla->read(null, $id);
            $plantillas_detalles = $this->PlantillasDetalle->find('all', array('conditions' => array('PlantillasDetalle.plantilla_id' => $id)));
            $this->set('plantillas_detalles', $plantillas_detalles);
        }

        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', array('conditions' => $conditions_regionales)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $unidadMedida = array(
            'UNI' => 'UNIDAD',
            'GAL' => 'GALON',
            'LIT' => 'LITRO',
            'CUN' => 'CUNETE',
            'PAQ X 6' => 'PAQUETE X 6',
            'PAQ' => 'PAQUETE',
            'MTS' => 'METROS',
            'KG' => 'KILOS',
            'LB' => 'LIBRA',
            'GR' => 'GRAMOS',
            'CAJ' => 'CAJAS',
            'PAR' => 'PAR');
        $this->set(compact('tipoCategoria', 'unidadMedida', 'tipoPedido', 'empresas', 'regionales'));

        /*
          SELECT * FROM productos
          WHERE id NOT IN (SELECT producto_id FROM plantillas_detalles WHERE plantilla_id = 4)
          AND estado = true;
         */
    }

    function delete($id = null) {
        $this->Plantilla->set($this->data);
        if (!empty($this->data)) {
            foreach ($this->data['Plantilla'] as $key => $value) {
                if ($value > 0) {
                    $estado = $this->Plantilla->find('first', array('fields' => 'Plantilla.estado_plantilla', 'conditions' => array('Plantilla.id' => $value)));
                    if ($estado['Plantilla']['estado_plantilla']) {
                        $this->Plantilla->updateAll(array("Plantilla.estado_plantilla" => 'false'), array("Plantilla.id" => $value));
                    } else {
                        $this->Plantilla->updateAll(array("Plantilla.estado_plantilla" => 'true'), array("Plantilla.id" => $value));
                    }
                }
            }
            $this->Session->setFlash(__('Las plantillas seleccionados se les ha cambiado su estado masivamente.', true));
            $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
            exit;
        }

        if (!$id) {
            $this->Session->setFlash(__('Plantilla invalido.', true));
            /* $this->redirect(array('action' => 'index')); */header("Location: index");
        }
        if ($id > 0) {
            $estado = $this->Plantilla->find('first', array('fields' => 'Plantilla.estado_plantilla', 'conditions' => array('Plantilla.id' => $id)));
            if ($estado['Plantilla']['estado_plantilla']) {
                $this->Plantilla->updateAll(array("Plantilla.estado_plantilla" => 'false'), array("Plantilla.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO de la plantilla.', true));
                $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
            } else {
                $this->Plantilla->updateAll(array("Plantilla.estado_plantilla" => 'true'), array("Plantilla.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO de la plantilla.', true));
                $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
            }
            exit;
        }
        $this->Session->setFlash(__('La plantilla indicado no fue encontrado.', true));
        $this->redirect(array('controller' => 'plantillas', 'action' => 'index'));
    }

    function update_file($id=null){
        date_default_timezone_set('America/Bogota');
        $dir_file = 'plantilla_producto/masivos/';
        $max_file = 20145728;
        $productos_validos = array();
        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }

        if ($this->RequestHandler->isPost()) {
            if ($this->data['Plantilla']['archivo_csv']['name']) {
                if (($this->data['Plantilla']['archivo_csv']['type'] == 'text/csv') || ($this->data['Plantilla']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    if ($this->data['Plantilla']['archivo_csv']['size'] < $max_file) {
                        move_uploaded_file($this->data['Plantilla']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Plantilla']['archivo_csv']['name']);
                        $file = fopen($dir_file . '/' . $this->data['Plantilla']['archivo_csv']['name'], 'r');
                        if ($file) {
                            $row = 0;
                            $headers = [];
                            while (($data = fgetcsv($file, null, ";")) !== FALSE) {

                                if ($row == 0) {
                                    $headers = $data;
                                } else {
                                    $data_producto = array();
                                    for ($i = 0; $i < count($headers); $i++) {
                                        $data_producto[$headers[$i]] = utf8_encode($data[$i]);
                                    }
                                    $producto_from_db = $this->Producto->find("first", array(
                                        'conditions' => array(
                                            'Producto.codigo_producto' => $data_producto["COD_PRODUCTO"],
                                        ),
                                        'fields' => ["id","codigo_producto"]
                                    ));
                                    $detalle_plantilla_from_db = $this->PlantillasDetalle->find("first", array(
                                        'conditions' => array(
                                            'PlantillasDetalle.producto_id' => $detalle_plantilla_from_db["Producto"]["id"],
                                            'PlantillasDetalle.plantilla_id'
                                        )
                                    ));

                                    

                                    /* $this->TempLocalidad->create();
                                    $this->TempLocalidad->save(array(
                                        "TempLocalidad" => array(
                                            "nombre_localidad" => $data_producto["LOCALIDAD"],
                                            "localidad_id" => $data_producto["ID"],
                                            "to_create" => $localidad_from_db ? false : true,
                                        )
                                    )); */

                                    if ($detalle_plantilla_from_db) {
                                        $data_producto["existe"] = true;
                                    } else {
                                        $data_producto["existe"] = false;
                                    }
                                    array_push($productos_validos, $data_producto);
                                }
                                $row++;
                            }
                        }

                        $this->set("productos_validos", $productos_validos);
                        fclose($file);
                        unlink($dir_file . '/' . $this->data['Localidades']['archivo_csv']['name']);
                    } else {
                        $this->set("productos_validos", $productos_validos);
                        $this->Session->setFlash('El tamaño del archivo supera el maximo establecido (20MB).', 'flash_failure');
                    }
                } else {
                    $this->set("productos_validos", $productos_validos);
                    $this->Session->setFlash('El tipo de archivo no es el admitido para este proceso.', 'flash_failure');
                }
            } else {
                $this->set("productos_validos", $productos_validos);
                $this->Session->setFlash('Hubo un error al cargar el archivo. Verifique y vuelva a intentar.', 'flash_failure');
            }
        } else {
            $this->set("productos_validos", $productos_validos);
        }
    }

}
