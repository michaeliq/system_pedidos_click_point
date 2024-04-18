<?php

class OrdenComprasController extends AppController {

    var $name = 'OrdenCompras';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('OrdenCompra', 'MenuAction', 'OrdenComprasDetalle', 'MovimientosProducto', 'Producto', 'TipoOrden', 'TipoEstadoOrden', 'Proveedore', 'TipoFormasPago', 'VCantidadProducto');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('OrdenCompras', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('OrdenCompras', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        /* 	print_r($this->MenuAction->find('all'));

          $this->data['MenuAction']['id'] = 190;
          $this->data['MenuAction']['menu_id'] = 20;
          $this->data['MenuAction']['menus_actions_descripcion'] = 'Cambiar de Estado Orden';
          $this->data['MenuAction']['menus_actions_accion'] = 'cambiar_estado';
          $this->data['MenuAction']['menus_actions_ajax'] = false;
          $this->MenuAction->create();
          $this->MenuAction->save($this->data); */

        $this->set('interno', array_search($this->Session->read('Auth.User.rol_id'), $this->Permisos->RolesInternos()));
    }

    function orden_compra() {
        if (!empty($this->data)) {

            $this->data['OrdenCompra']['empresa_id'] = '1';
            $this->data['OrdenCompra']['fecha_orden_compra'] = date('Y-m-d H:i:s');
            $this->data['OrdenCompra']['fecha_elaboracion'] = date('Y-m-d H:i:s');
            $this->data['OrdenCompra']['tipo_estado_orden_id'] = '1'; // En proceso
            $this->data['OrdenCompra']['user_id_elaboro'] = $this->Session->read('Auth.User.id'); // Elaboó
            $this->data['OrdenCompra']['user_id'] = $this->Session->read('Auth.User.id'); // Elaboó

            $this->OrdenCompra->create();
            if ($this->OrdenCompra->save($this->data)) {
                $this->Session->write('OrdenCompra.id', $this->OrdenCompra->getInsertID());
                $this->Session->write('MovimientosEntrada.proveedor_id', $this->data['OrdenCompra']['proveedor_id']);  // Consultar el último precio

                $orden = $this->OrdenCompra->find('all', array('fields' => 'Proveedore.nombre_proveedor, OrdenCompra.fecha_orden_compra, TipoOrden.nombre_tipo_orden, proveedor_id, OrdenCompra.observaciones', 'conditions' => array('OrdenCompra.id' => $this->OrdenCompra->getInsertID(), 'OrdenCompra.tipo_estado_orden_id' => '1')));
                $encabezado = "<b>Proveedor:</b> " . $orden[0]['Proveedore']['nombre_proveedor'] . "<br><b>Fecha Orden:</b> " . $orden[0]['OrdenCompra']['fecha_orden_compra'] . "<br> <b>Tipo de Orden:</b> " . $orden[0]['TipoOrden']['nombre_tipo_orden'] . "<br> <b>Observaciones:</b> " . $orden[0]['OrdenCompra']['observaciones'] . "<br>";
                $this->Session->write('OrdenCompra.encabezado', $encabezado);
                $this->Session->setFlash(__('Se ha realizado la orden de compra. Por favor relacione los productos a solicitar. Nuevo estado: En Proceso.', true));
                $this->redirect(array('action' => 'detalle_compra'));
            } else {
                $this->Session->setFlash(__('La orden de pedido no se ha realizado. Por favor intente de nuevo.', true));
            }
        }

        $tipoOrden = $this->TipoOrden->find('list', array('fields' => 'TipoOrden.nombre_tipo_orden', 'order' => 'TipoOrden.id'));
        $proveedores = $this->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $tipoFormasPagos = $this->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        $this->set(compact('tipoOrden', 'proveedores', 'tipoFormasPagos'));
    }

    function detalle_compra($id = null) {
        if (!empty($id)) {
            $this->Session->delete('OrdenCompra.id');
            $this->Session->delete('MovimientosEntrada.proveedor_id');
            $this->Session->delete('OrdenCompra.encabezado');

            $orden = $this->OrdenCompra->find('all', array('fields' => 'Proveedore.nombre_proveedor, OrdenCompra.fecha_orden_compra, TipoOrden.nombre_tipo_orden, OrdenCompra.proveedor_id, OrdenCompra.observaciones', 'conditions' => array('OrdenCompra.id' => $id, 'OrdenCompra.tipo_estado_orden_id' => '1')));
            $this->Session->write('OrdenCompra.id', $id);
            $this->Session->write('MovimientosEntrada.proveedor_id', $orden[0]['OrdenCompra']['proveedor_id']);  // Consultar el último precio
            $encabezado = "<b>Proveedor:</b> " . $orden[0]['Proveedore']['nombre_proveedor'] . "<br><b>Fecha Orden:</b> " . $orden[0]['OrdenCompra']['fecha_orden_compra'] . "<br> <b>Tipo de Orden:</b> " . $orden[0]['TipoOrden']['nombre_tipo_orden'] . "<br> <b>Observaciones:</b> " . $orden[0]['OrdenCompra']['observaciones'] . "<br>";
            $this->Session->write('OrdenCompra.encabezado', $encabezado);
            $this->redirect(array('action' => 'detalle_compra'));
        }

        if (!empty($this->data)) {
            $this->OrdenComprasDetalle->create();
            $aux = explode('|', $this->data['OrdenComprasDetalle']['producto_id2']);
            $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.codigo_producto' => trim($aux[0]))));
            if (count($aux) > 0) {
                $this->data['OrdenComprasDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                $this->data['OrdenComprasDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                $this->data['OrdenComprasDetalle']['precio_producto'] = $this->data['OrdenComprasDetalle']['precio_producto'];
                $this->data['OrdenComprasDetalle']['iva_producto'] = $aux[0]['Producto']['iva_producto'];
                $this->data['OrdenComprasDetalle']['medida_producto'] = $aux[0]['Producto']['medida_producto'];
                $this->data['OrdenComprasDetalle']['orden_compra_id'] = $this->Session->read('OrdenCompra.id');
                $this->data['OrdenComprasDetalle']['fecha_orden_compra'] = date('Y-m-d H:i:s');
                $this->data['OrdenComprasDetalle']['observaciones_producto'] = $this->data['OrdenComprasDetalle']['observaciones'];

                if ($this->OrdenComprasDetalle->save($this->data)) {
                    $this->Session->setFlash(__('Se agregó el producto a la orden de compra!', true));
                    $this->redirect(array('action' => 'detalle_compra'));
                }
            }
        }

        // SUGERIDO DE COMPRA  
        $conditions = array('MovimientosProducto.proveedor_id' => $this->Session->read('MovimientosEntrada.proveedor_id'));
        $inventarios = $this->MovimientosProducto->find('all', array('conditions' => $conditions, 'order' => 'Producto.codigo_producto'));
        $this->set('inventarios', $inventarios);
        //print_r($inventarios);

        $productos = array();
        foreach ($inventarios as $inventario):
            array_push($productos, $inventario['MovimientosProducto']['producto_id']);
        endforeach;

        // PRODUCTOS APROBADOS
        $productos_aprobados = array();
        if (count($productos) > 0) {
            $productos_aprobados = $this->MovimientosProducto->query("
                        SELECT pedidos_detalles.producto_id, SUM(pedidos_detalles.cantidad_pedido)
                        FROM pedidos,
                         pedidos_detalles
                        WHERE pedidos.id = pedidos_detalles.pedido_id AND 
                        pedidos.pedido_estado_pedido = 4 AND
                        pedidos_detalles.producto_id IN (" . implode(',', $productos) . ")
                        GROUP BY pedidos_detalles.producto_id
                        ORDER BY pedidos_detalles.producto_id;");
        }
        $this->set('productos_aprobados', $productos_aprobados);

        //  PRODUCTOS IGUALES EN ORDEN DE COMPRA EN ESTADO APROBADO
        $detalles_aprobados = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenCompra.tipo_estado_orden_id' => array('4', '5'), 'OrdenComprasDetalle.producto_id' => $productos)));
        $this->set('detalles_aprobados', $detalles_aprobados);

        // PRODUCTOS AGREGADOS A LA ORDEN DE COMPRA
        $detalles = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenCompra.tipo_estado_orden_id' => '1', 'OrdenComprasDetalle.orden_compra_id' => $this->Session->read('OrdenCompra.id'))));
        $this->set('detalles', $detalles);

        //  Quitar productos que ya estan en la orden de compra
        $productos = array();
        foreach ($detalles as $detalle) {
            array_push($productos, $detalle['Producto']['id']);
        }
        $this->set('productos', $this->Producto->find('all', array('fields'=>array('codigo_producto','nombre_producto','producto_completo'),'conditions' => array('Producto.mostrar_producto' => true,'Producto.estado' => true, "NOT" => array('Producto.id' => $productos)))));
    }

    function agregar_sugerido() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (is_numeric($_REQUEST['cantidadOrden']) && is_numeric($_REQUEST['precioProducto']) && is_numeric($_REQUEST['productoId'])) {
                $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.id' => $_REQUEST['productoId'])));
                if (count($aux) > 0) {
                    $this->data['OrdenComprasDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                    $this->data['OrdenComprasDetalle']['cantidad_orden'] = (is_numeric($_REQUEST['cantidadOrden']) ? $_REQUEST['cantidadOrden'] : '0');
                    $this->data['OrdenComprasDetalle']['orden_compra_id'] = $this->Session->read('OrdenCompra.id');
                    $this->data['OrdenComprasDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                    $this->data['OrdenComprasDetalle']['precio_producto'] = (is_numeric($_REQUEST['precioProducto']) ? $_REQUEST['precioProducto'] : $aux[0]['Producto']['precio_producto']);
                    $this->data['OrdenComprasDetalle']['tipo_categoria_id'] = $aux[0]['Producto']['tipo_categoria_id'];
                    // $this->data['OrdenComprasDetalle']['precio_producto'] = $aux[0]['Producto']['precio_producto'];
                    $this->data['OrdenComprasDetalle']['iva_producto'] = $aux[0]['Producto']['iva_producto'];
                    $this->data['OrdenComprasDetalle']['medida_producto'] = $aux[0]['Producto']['medida_producto'];
                    $this->data['OrdenComprasDetalle']['fecha_orden_compra'] = date('Y-m-d H:i:s');
                    $this->data['OrdenComprasDetalle']['observaciones_producto'] = null;

                    if ($this->OrdenComprasDetalle->save($this->data)) {
                        echo true;
                        $this->Session->setFlash(__('El producto se ha agregado a la orden exitosamente.', true));
                    } else {
                        echo false;
                        $this->Session->setFlash(__('No se logró agregar el producto de la orden. Por favor intente de nuevo.', true));
                    }
                }
            }
            exit;
        }
    }

    function quitar_producto() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['OrdenComprasDetalleId'])) {
                $this->OrdenComprasDetalle->id = $_REQUEST['OrdenComprasDetalleId'];
                if ($this->OrdenComprasDetalle->delete()) {
                    echo true;
                    $this->Session->setFlash(__('El producto se ha quitado de la orden exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró quitar el producto de la orden. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function cancelar_orden() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['OrdenCompraId'])) {
                if ($this->OrdenComprasDetalle->updateAll(array("OrdenComprasDetalle.cantidad_orden" => '0'), array("OrdenComprasDetalle.orden_compra_id" => $_REQUEST['OrdenCompraId']))) {
                    if ($this->OrdenCompra->updateAll(array("OrdenCompra.tipo_estado_orden_id" => '2', "OrdenCompra.estado_orden" => 'false'), array("OrdenCompra.id" => $_REQUEST['OrdenCompraId']))) {
                        echo true;
                        $this->Session->setFlash(__('La orden de compra se ha cancelado exitosamente.', true));
                    } else {
                        echo false;
                        $this->Session->setFlash(__('No se logró cancelar la orden de compra. Por favor intente de nuevo.', true));
                    }
                }
            }
        }
        exit;
    }

    function terminar_orden() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['OrdenCompraId'])) {
                if ($this->OrdenCompra->updateAll(array("OrdenCompra.tipo_estado_orden_id" => '3', "OrdenCompra.estado_orden" => 'true'), array("OrdenCompra.id" => $_REQUEST['OrdenCompraId'], "OrdenCompra.estado_orden" => true))) {
                    echo true;
                    $this->Session->setFlash(__('La orden de compra se ha terminado exitosamente. Nuevo estado: Pendiente de Aprobación', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró terminar la orden de compra. Por favor intente de nuevo.', true));
                }
            }
        }
        exit;
    }

    function cambiar_estado($id = null) {
        if (!empty($id)) {
            if ($this->OrdenCompra->updateAll(array("OrdenCompra.tipo_estado_orden_id" => '1', "OrdenCompra.estado_orden" => 'true', "OrdenCompra.user_id_aprobo" => null, "OrdenCompra.fecha_aprobado" => null), array("OrdenCompra.id" => $id))) {
                $this->Session->setFlash(__('La orden de compra #000' . $id . ' se ha activado exitosamente. Nuevo estado: En proceso', true));
                $this->redirect(array('controller' => 'ordenCompras', 'action' => 'listar_ordenes'));
            } else {
                $this->Session->setFlash(__('No se logro activar de nuevo la orden de compra. Por favor intente de nuevo.', true));
            }
        }
        exit;
    }

    function listar_ordenes() {
        $conditions = array();

        $this->OrdenCompra->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['OrdenCompra']['id'])) {
                $where = "+OrdenCompra+.+id+ = " . $this->data['OrdenCompra']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra']['proveedor_id'])) {
                $where = "+OrdenCompra+.+proveedor_id+ = " . $this->data['OrdenCompra']['proveedor_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra']['fecha_orden_compra'])) {
                $where = "+OrdenCompra+.+fecha_orden_compra+::date = '" . $this->data['OrdenCompra']['fecha_orden_compra'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra']['tipo_orden_id'])) {
                $where = "+OrdenCompra+.+tipo_orden_id+ = " . $this->data['OrdenCompra']['tipo_orden_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra']['tipo_estado_orden_id'])) {
                $where = "+OrdenCompra+.+tipo_estado_orden_id+ = " . $this->data['OrdenCompra']['tipo_estado_orden_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        $this->OrdenCompra->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->paginate = array('limit' => 100, 'order' => array(
                'OrdenCompra.id' => 'desc'
        ));
        $this->set('ordenes', $this->paginate($conditions));

        $tipoOrden = $this->TipoOrden->find('list', array('fields' => 'TipoOrden.nombre_tipo_orden', 'order' => 'TipoOrden.id'));
        $proveedores = $this->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $tipoEstadoOrden = $this->TipoEstadoOrden->find('list', array('fields' => 'TipoEstadoOrden.nombre_estado_orden', 'order' => 'TipoEstadoOrden.id'));
        $this->set(compact('tipoOrden', 'proveedores', 'tipoEstadoOrden'));
    }

    function ver_orden($id = null) {
        $ordenes = $this->OrdenCompra->find('all', array('order' => '', 'conditions' => array('OrdenCompra.id' => $id)));
        $this->set('ordenes', $ordenes);

        $detalles = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenComprasDetalle.orden_compra_id' => $id)));
        $this->set('detalles', $detalles);
    }

    function ver_aprobar_orden($id = null) {
        $ordenes = $this->OrdenCompra->find('all', array('order' => '', 'conditions' => array('OrdenCompra.id' => $id)));
        $this->set('ordenes', $ordenes);

        $detalles = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenComprasDetalle.orden_compra_id' => $id)));
        $this->set('detalles', $detalles);
    }

    function aprobar_ordenes() {
        $conditions = array('OrdenCompra.tipo_estado_orden_id' => '3');

        $this->OrdenCompra->set($this->data);
        if (!empty($this->data['OrdenCompra1'])) {
            if (!empty($this->data['OrdenCompra1']['id'])) {
                $where = "+OrdenCompra+.+id+ = " . $this->data['OrdenCompra1']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra1']['proveedor_id'])) {
                $where = "+OrdenCompra+.+proveedor_id+ = " . $this->data['OrdenCompra1']['proveedor_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra1']['fecha_orden_compra'])) {
                $where = "+OrdenCompra+.+fecha_orden_compra+::date = '" . $this->data['OrdenCompra1']['fecha_orden_compra'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra1']['tipo_orden_id'])) {
                $where = "+OrdenCompra+.+tipo_orden_id+ = " . $this->data['OrdenCompra1']['tipo_orden_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['OrdenCompra1']['tipo_estado_orden_id'])) {
                $where = "+OrdenCompra+.+tipo_estado_orden_id+ = " . $this->data['OrdenCompra1']['tipo_estado_orden_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        $this->OrdenCompra->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->paginate = array('limit' => 100, 'order' => array(
                'OrdenCompra.id' => 'desc'
        ));
        $this->set('ordenes', $this->paginate($conditions));

        if (!empty($this->data['OrdenCompra'])) {
            $ordenes_aprobadas = '';
            foreach ($this->data['OrdenCompra'] as $key => $value) {
                if ($this->OrdenCompra->updateAll(array("OrdenCompra.estado_orden" => 'true', "OrdenCompra.user_id_aprobo" => $this->Session->read('Auth.User.id'), "OrdenCompra.tipo_estado_orden_id" => '4', "OrdenCompra.fecha_aprobado" => "'" . date('Y-m-d H:i:s') . "'"), array("OrdenCompra.id" => $value))) {
                    $ordenes_aprobadas = $ordenes_aprobadas . ' #000' . $value . ' ';
                    $this->OrdenCompra->query('SELECT actualizar_totales_ordenes(' . $value . ')');
                }
            }
            $this->Session->setFlash(__('Las orden de compra (' . $ordenes_aprobadas . ') se han aprobado masivamente. Nuevo estado: Aprobado.', true));
            $this->redirect(array('controller' => 'ordenCompras', 'action' => 'aprobar_ordenes'));
        }

        $tipoOrden = $this->TipoOrden->find('list', array('fields' => 'TipoOrden.nombre_tipo_orden', 'order' => 'TipoOrden.id'));
        $proveedores = $this->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $tipoEstadoOrden = $this->TipoEstadoOrden->find('list', array('fields' => 'TipoEstadoOrden.nombre_estado_orden', 'order' => 'TipoEstadoOrden.id'));
        $this->set(compact('tipoOrden', 'proveedores', 'tipoEstadoOrden'));
    }

    function ver_orden_pdf($id = null) {
        Configure::write('debug', 0);
        $this->layout = 'pdf';

        $ordenes = $this->OrdenCompra->find('all', array('order' => '', 'conditions' => array('OrdenCompra.id' => $id)));
        $this->set('ordenes', $ordenes);

        $detalles = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenComprasDetalle.orden_compra_id' => $id)));
        $this->set('detalles', $detalles);
    }

}

?>