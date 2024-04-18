<?php

class MovimientosEntradasController extends AppController {

    var $name = 'MovimientosEntradas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('MovimientosEntrada', 'MovimientosEntradasDetalle', 'Producto', 'TipoMovimiento',
        'Empresa', 'Proveedore', 'TipoCategoria', 'Bodega', 'TipoFormasPago', 'Pedido', 'OrdenCompra', 'OrdenComprasDetalle');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('MovimientosEntradas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('MovimientosEntradas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->MovimientosEntrada->recursive = 0;
        $this->MovimientosEntrada->order = 'MovimientosEntrada.id desc';
        $conditions = array();

        if (!empty($this->data)) {
            if (!empty($this->data['MovimientosEntrada']['id'])) {
                $where = "+MovimientosEntrada+.+id+ = " . $this->data['MovimientosEntrada']['id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['MovimientosEntrada']['tipo_movimiento_id'])) {
                $where = "+MovimientosEntrada+.+tipo_movimiento_id+ = " . $this->data['MovimientosEntrada']['tipo_movimiento_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['MovimientosEntrada']['proveedor_id'])) {
                $where = "+MovimientosEntrada+.+proveedor_id+ = " . $this->data['MovimientosEntrada']['proveedor_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['MovimientosEntrada']['empresa_id']) && $this->data['MovimientosEntrada']['empresa_id'] > 0) {
                $where = "+MovimientosEntrada+.+empresa_id+ = " . $this->data['MovimientosEntrada']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['MovimientosEntrada']['bodega_id'])) {
                $where = "+MovimientosEntrada+.+bodega_id+ = " . $this->data['MovimientosEntrada']['bodega_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['MovimientosEntrada']['orden_compra_id'])) {
                $where = "+MovimientosEntrada+.+orden_compra_id+ = " . $this->data['MovimientosEntrada']['orden_compra_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['MovimientosEntrada']['fecha_movimiento'])) {
                $where = "+MovimientosEntrada+.+fecha_movimiento+ = '" . $this->data['MovimientosEntrada']['fecha_movimiento'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }
        $this->set('movimientosEntradas', $this->paginate($conditions));

        $tipoMovimientos = $this->MovimientosEntrada->TipoMovimiento->find('list', array('fields' => 'TipoMovimiento.nombre_tipo_movimiento', 'order' => 'TipoMovimiento.nombre_tipo_movimiento', 'conditions' => array('TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'E')));
        $proveedores = $this->MovimientosEntrada->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $bodegas = $this->MovimientosEntrada->Bodega->find('list', array('fields' => 'Bodega.nombre_bodega', 'order' => 'Bodega.nombre_bodega', 'conditions' => array('Bodega.estado_bodega' => true)));
        $tipoFormasPagos = $this->MovimientosEntrada->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        // $users = $this->MovimientosEntrada->User->find('list');
        $ordenCompra = $this->OrdenCompra->find('list', array('fields' => 'OrdenCompra.orden_compra_completa', 'order' => 'OrdenCompra.id', 'conditions' => array('OrdenCompra.tipo_estado_orden_id' => array('5', '6'), 'OrdenCompra.estado_orden' => true)));
        $this->set(compact('tipoMovimientos', /* 'empresas', */ 'proveedores', 'tipoCategorias', 'bodegas', 'tipoFormasPagos'/* , 'users' */,'ordenCompra')); 
    }

    function entradas() {
        
    }

    function salidas() {
        
    }

    function consultar_proveedor_cliente() {
        if (!empty($_REQUEST['MovimientosEntradaTipoMovimientoId'])) {
            $tipoProveedorCliente = $this->TipoMovimiento->find('all', array('conditions' => array('TipoMovimiento.id' => $_REQUEST['MovimientosEntradaTipoMovimientoId'])));
            echo json_encode($tipoProveedorCliente);
        }
    }

    function consultar_forma_pago() {
        if (!empty($_REQUEST['MovimientosEntradaProveedorId'])) {
            $tipoFormaPago = $this->Proveedore->find('all', array('conditions' => array('Proveedore.id' => $_REQUEST['MovimientosEntradaProveedorId'])));
            echo json_encode($tipoFormaPago);
        }
    }

    function consultar_empresas() {
        if (!empty($_REQUEST['MovimientosEntradaTipoMovimientoId'])) {
            $conditions = array('Empresa.id' => '0');
            if ($_REQUEST['MovimientosEntradaTipoMovimientoId'] == '3') {
                $conditions = array('Empresa.estado_empresa' => true);
            }

            if ($_REQUEST['MovimientosEntradaTipoMovimientoId'] == '4') {
                $conditions = array('Empresa.estado_empresa' => true, 'Empresa.id' => '1');
            }

            $empresas = $this->Empresa->find('all', array('fields' => 'Empresa.id,Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions));
            echo json_encode($empresas);
        }
    }

    function consultar_categorias() {
        $tipoCategorias = $this->MovimientosEntrada->TipoCategoria->find('all', array('order' => 'TipoCategoria.tipo_categoria_descripcion'));
        echo json_encode($tipoCategorias);
    }

    function validar_pedido() {
        $pedidos = array();
        if (!empty($_REQUEST['MovimientosEntradaPedidoId'])) {
            $pedidos = $this->Pedido->find('all', array('conditions' => array('Pedido.id' => $_REQUEST['MovimientosEntradaPedidoId'], 'Pedido.pedido_estado_pedido' => '5')));
            if (count($pedidos) > 0) {
                echo json_encode($pedidos);
            } else {
                echo json_encode($pedidos);
            }
        }
    }

    function consultar_orden_compra() {
        if (!empty($_REQUEST['MovimientosEntradaOrdenCompraId'])) {
            $ordenCompra = $this->OrdenCompra->find('all', array('conditions' => array('OrdenCompra.id' => $_REQUEST['MovimientosEntradaOrdenCompraId'])));
            echo json_encode($ordenCompra);
        }
        exit;
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid movimientos entrada', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('movimiento', $this->MovimientosEntrada->find('first', array('conditions' => array('MovimientosEntrada.id' => $id))));
        $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array('order' => 'MovimientosEntradasDetalle.producto_id', 'conditions' => array('MovimientosEntradasDetalle.estado_entrada' => true, 'MovimientosEntradasDetalle.movimientos_entrada_id' => $id)));
        $this->set('movimientosEntradas', $movimientosEntradas);
    }

    function add() {
        if (!empty($this->data)) {
            $this->MovimientosEntrada->create();

            $this->data['MovimientosEntrada']['factura_subtotal'] = (is_float($this->data['MovimientosEntrada']['factura_subtotal']) ? $this->data['MovimientosEntrada']['factura_subtotal'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_subtotal']));
            $this->data['MovimientosEntrada']['factura_iva'] = (is_float($this->data['MovimientosEntrada']['factura_iva']) ? $this->data['MovimientosEntrada']['factura_iva'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_iva']));
            $this->data['MovimientosEntrada']['factura_total'] = (is_float($this->data['MovimientosEntrada']['factura_total']) ? $this->data['MovimientosEntrada']['factura_total'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_total']));
            // print_r($this->data);
            if ($this->MovimientosEntrada->save($this->data)) {
                $this->Session->setFlash(__('El movimiento de entrada ha sido guardado', true));
                $this->Session->write('MovimientosEntrada.movimiento_id', $this->MovimientosEntrada->getInsertID());
                $this->Session->write('MovimientosEntrada.tipo_categoria_id', $this->data['MovimientosEntrada']['tipo_categoria_id']);
                $this->Session->write('MovimientosEntrada.proveedor_id', $this->data['MovimientosEntrada']['proveedor_id']);
                $this->Session->write('MovimientosEntrada.orden_compra_id', $this->data['MovimientosEntrada']['orden_compra_id']);
                $this->redirect(array('action' => 'detalle_movimiento'));
            } else {
                $this->Session->setFlash(__('El movimientos entrada no puede ser guardardo. Intente de nuevo', true));
            }
        }
        $ordenCompra = $this->OrdenCompra->find('list', array('fields' => 'OrdenCompra.orden_compra_completa', 'order' => 'OrdenCompra.id', 'conditions' => array('OrdenCompra.tipo_estado_orden_id' => array('4', '5'), 'OrdenCompra.estado_orden' => true)));
        $tipoMovimientos = $this->MovimientosEntrada->TipoMovimiento->find('list', array('fields' => 'TipoMovimiento.nombre_tipo_movimiento', 'order' => 'TipoMovimiento.nombre_tipo_movimiento', 'conditions' => array('TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'E')));
        $proveedores = $this->MovimientosEntrada->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $bodegas = $this->MovimientosEntrada->Bodega->find('list', array('fields' => 'Bodega.nombre_bodega', 'order' => 'Bodega.nombre_bodega', 'conditions' => array('Bodega.estado_bodega' => true)));
        $tipoFormasPagos = $this->MovimientosEntrada->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        // $users = $this->MovimientosEntrada->User->find('list');
        $this->set(compact('tipoMovimientos', /* 'empresas', */ 'proveedores', 'tipoCategorias', 'bodegas', 'tipoFormasPagos'/* , 'users' */, 'ordenCompra'));
    }

    function detalle_movimiento($id = null) {
        if (!empty($this->data)) {
            $this->MovimientosEntradasDetalle->create();
            $aux = explode('|', $this->data['MovimientosEntradasDetalle']['producto_id2']);
            $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.codigo_producto' => trim($aux[0]))));
            if (count($aux) > 0) {
                $this->data['MovimientosEntradasDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                $this->data['MovimientosEntradasDetalle']['movimientos_entrada_id'] = $this->Session->read('MovimientosEntrada.movimiento_id');
                $this->data['MovimientosEntradasDetalle']['user_id'] = $this->Session->read('Auth.User.id');
                $this->data['MovimientosEntradasDetalle']['fecha_registro_entrada'] = date('Y-m-d H:i:s');
                $this->data['MovimientosEntradasDetalle']['precio_producto'] = (is_float($this->data['MovimientosEntradasDetalle']['precio_producto']) ? $this->data['MovimientosEntradasDetalle']['precio_producto'] : sprintf("%.8f", $this->data['MovimientosEntradasDetalle']['precio_producto']));

                if ($this->MovimientosEntradasDetalle->save($this->data)) {
                    $this->MovimientosEntrada->query('SELECT actualizar_totales_movimientos_e(' . $this->Session->read('MovimientosEntrada.movimiento_id') . ')');
                    $this->redirect(array('action' => 'detalle_movimiento'));
                } else {
                    $this->Session->setFlash(__('Por favor verifique los datos ingresados para el movimiento.', true));
                }
            } else {
                $this->Session->setFlash(__('El producto ingresado (' . $this->data['MovimientosEntradasDetalle']['producto_id2'] . ') no esta registrado en el sistema.', true));
            }
        }
        // Si viene desde URL
        if (!empty($id)) {
            $movimiento = $this->MovimientosEntrada->find('first', array('conditions' => array('MovimientosEntrada.id' => $id)));
            // print_r($movimiento);
            $this->Session->write('MovimientosEntrada.movimiento_id', $id);
            $this->Session->write('MovimientosEntrada.tipo_categoria_id', $movimiento['MovimientosEntrada']['tipo_categoria_id']);
            $this->Session->write('MovimientosEntrada.proveedor_id', $movimiento['MovimientosEntrada']['proveedor_id']);
            $this->Session->write('MovimientosEntrada.orden_compra_id', $movimiento['MovimientosEntrada']['orden_compra_id']);
            $this->redirect(array('action' => 'detalle_movimiento'));
        }

        $movimiento = $this->MovimientosEntrada->find('first', array('conditions' => array('MovimientosEntrada.id' => $this->Session->read('MovimientosEntrada.movimiento_id'))));
        $this->set('movimiento', $movimiento);
        $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array('order' => 'MovimientosEntradasDetalle.producto_id', 'conditions' => array('MovimientosEntradasDetalle.estado_entrada' => false, 'MovimientosEntradasDetalle.movimientos_entrada_id' => $this->Session->read('MovimientosEntrada.movimiento_id'))));
        // print_r($movimientosEntradas);
        $this->set('movimientosEntradas', $movimientosEntradas);

        /* Consultar Orden de Compra Detalle */
        $ordenCompras = $this->OrdenComprasDetalle->find('all', array('order' => 'OrdenComprasDetalle.producto_id', 'conditions' => array('OrdenComprasDetalle.orden_compra_id' => $movimiento['MovimientosEntrada']['orden_compra_id'])));
        $this->set('ordenCompras', $ordenCompras);
        // print_r($orden_compra);

        $productos_agregados = array();
        foreach ($movimientosEntradas as $value) {
            array_push($productos_agregados, $value['MovimientosEntradasDetalle']['producto_id']);
        }

        $this->set('productos', $this->Producto->find('all', array(
                    'conditions' => array(
                        "NOT" => array("Producto.id" => $productos_agregados), 'Producto.estado' => true, 'Producto.mostrar_producto' => true /* , 'Producto.tipo_categoria_id' => $this->Session->read('MovimientosEntrada.tipo_categoria_id') */
                    )
        )));
    }

    function agregar_movimiento() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (is_numeric($_REQUEST['cantidadOrden']) && is_numeric($_REQUEST['precioProducto']) && is_numeric($_REQUEST['productoId']) && is_numeric($_REQUEST['ordenId'])) {
                $aux = $this->Producto->find('all', array('conditions' => array('Producto.estado' => true, 'Producto.id' => $_REQUEST['productoId'])));
                if (count($aux) > 0) {
                    $this->data['MovimientosEntradasDetalle']['producto_id'] = $aux[0]['Producto']['id'];
                    $this->data['MovimientosEntradasDetalle']['cantidad_entrada'] = (is_numeric($_REQUEST['cantidadOrden']) ? $_REQUEST['cantidadOrden'] : '0');
                    // $this->data['MovimientosEntradasDetalle']['precio_producto'] = (is_numeric($_REQUEST['precioProducto']) ? $_REQUEST['precioProducto'] : $aux[0]['Producto']['precio_producto']);
                    $this->data['MovimientosEntradasDetalle']['movimientos_entrada_id'] = $this->Session->read('MovimientosEntrada.movimiento_id');
                    $this->data['MovimientosEntradasDetalle']['user_id'] = $this->Session->read('Auth.User.id');
                    $this->data['MovimientosEntradasDetalle']['fecha_registro_entrada'] = date('Y-m-d H:i:s');
                    $this->data['MovimientosEntradasDetalle']['precio_producto'] = (is_float($_REQUEST['precioProducto']) ? $_REQUEST['precioProducto'] : sprintf("%.8f", $_REQUEST['precioProducto']));

                    if ($this->MovimientosEntradasDetalle->save($this->data)) {
                        // Actualizar ordenes de compra con entradas parciales
                        if (!empty($_REQUEST['ordenId']) && is_numeric($_REQUEST['cantidadOrden'])) {
                            // Calcular las cantidades desde movimientos de entrada para actualizar orden de compra parcial
                            $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array(
                                'fields' => array('SUM(MovimientosEntradasDetalle.cantidad_entrada)'),
                                // 'group' => 'MovimientosEntradasDetalle.cantidad_entrada',
                                //'order' => 'MovimientosEntradasDetalle.producto_id',
                                'conditions' => array(//'MovimientosEntradasDetalle.estado_entrada' => false,
                                    'MovimientosEntradasDetalle.producto_id' => $_REQUEST['productoId'],
                                    'MovimientosEntrada.orden_compra_id' => $this->Session->read('MovimientosEntrada.orden_compra_id'))));
                            $parcial = 0;
                            if (!empty($movimientosEntradas['0']['0']['sum'])) {
                                $parcial = $movimientosEntradas['0']['0']['sum'];
                                if (!empty($_REQUEST['cantidadOrdenOriginal']) && $parcial > $_REQUEST['cantidadOrdenOriginal']) {
                                    $parcial = $_REQUEST['cantidadOrdenOriginal']; 
                                }
                            }
                            $this->OrdenComprasDetalle->updateAll(array("OrdenComprasDetalle.cantidad_orden_parcial" => $parcial), array("OrdenComprasDetalle.orden_compra_id" => $_REQUEST['ordenId'], "OrdenComprasDetalle.producto_id" => $_REQUEST['productoId']));
                            $this->MovimientosEntrada->query('SELECT actualizar_totales_movimientos_e(' . $this->Session->read('MovimientosEntrada.movimiento_id') . ')');
                        }

                        $this->Session->setFlash(__('Se agregó el producto al movimiento correctamente.', true));
                        $this->redirect(array('action' => 'detalle_movimiento'));
                    } else {
                        $this->Session->setFlash(__('Por favor verifique los datos ingresados para el movimiento.', true));
                    }
                }
            }
            exit;
        }
    }

    function quitar_entrada() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['MovimientosEntradasDetalleId'])) {
                $this->MovimientosEntradasDetalle->id = $_REQUEST['MovimientosEntradasDetalleId'];
                if ($this->MovimientosEntradasDetalle->delete()) {

                    // Calcular las cantidades desde movimientos de entrada para actualizar orden de compra parcial
                    if (!empty($_REQUEST['productoId'])) {
                        $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array(
                            'fields' => array('SUM(MovimientosEntradasDetalle.cantidad_entrada)'),
                            'conditions' => array(//'MovimientosEntradasDetalle.estado_entrada' => false,
                                'MovimientosEntradasDetalle.producto_id' => $_REQUEST['productoId'],
                                'MovimientosEntrada.orden_compra_id' => $this->Session->read('MovimientosEntrada.orden_compra_id'))));
                        $parcial = $movimientosEntradas['0']['0']['sum'];
                        $this->OrdenComprasDetalle->updateAll(array("OrdenComprasDetalle.cantidad_orden_parcial" => $parcial), array("OrdenComprasDetalle.orden_compra_id" => $this->Session->read('MovimientosEntrada.orden_compra_id'), "OrdenComprasDetalle.producto_id" => $_REQUEST['productoId']));
                    }
                    $this->MovimientosEntrada->query('SELECT actualizar_totales_movimientos_e(' . $this->Session->read('MovimientosEntrada.movimiento_id') . ')');

                    echo true;
                    $this->Session->setFlash(__('El producto se ha quitado del movimiento de entrada exitosamente.', true));
                } else {
                    echo false;
                    $this->Session->setFlash(__('No se logró quitar el producto del movimiento de entrada. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function terminar_entrada() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['MovimientosEntradasId'])) {
                // $this->MovimientosEntradasDetalle->movimientos_entrada_id = $_REQUEST['MovimientosEntradasId'];
                if ($this->MovimientosEntradasDetalle->updateAll(array("MovimientosEntradasDetalle.estado_entrada" => 'true'), array("MovimientosEntradasDetalle.movimientos_entrada_id" => $_REQUEST['MovimientosEntradasId'], "MovimientosEntradasDetalle.estado_entrada" => false))) {
                    if ($this->MovimientosEntrada->updateAll(array("MovimientosEntrada.estado_movimiento" => 'true'), array("MovimientosEntrada.id" => $_REQUEST['MovimientosEntradasId'], "MovimientosEntrada.estado_movimiento" => false))) {
                        $this->MovimientosEntrada->query('SELECT proveedor_productos()');
                        $this->MovimientosEntrada->query('SELECT ordenes_compra_parciales(' . $_REQUEST['MovimientosEntradasId'] . ')');
                        echo true;
                        $this->Session->setFlash(__('El movimiento de entrada ha ingresado al inventario exitosamente.', true));
                    } else {
                        echo false;
                        $this->Session->setFlash(__('No se logró ingresar al inventario el movimiento de entrada actual. Por favor intente de nuevo.', true));
                    }
                }
            }
        }
    }

    function ultimo_precio_proveedor() {
        if (!empty($_REQUEST['MovimientosEntradasDetalleProductoId2'])) {
            $producto_tmp = explode(' ', $_REQUEST['MovimientosEntradasDetalleProductoId2']);
            $producto = $this->Producto->find('first', array('conditions' => array('codigo_producto' => $producto_tmp[0])));
            $movimientoEntrada = $this->MovimientosEntrada->find('all', array(
                'fields' => array('MAX(MovimientosEntrada.fecha_registro_movimiento) AS created', 'MovimientosEntrada.id'),
                'conditions' => array('MovimientosEntrada.proveedor_id' => $this->Session->read('MovimientosEntrada.proveedor_id'),
                    'MovimientosEntrada.estado_movimiento' => true),
                'group' => 'MovimientosEntrada.id',
                'order' => 'MovimientosEntrada.fecha_registro_movimiento DESC'
                    )
            );

            if (count($movimientoEntrada) > 0) {
                $precioProducto = $this->MovimientosEntradasDetalle->find('first', array(
                    'conditions' => array('MovimientosEntradasDetalle.movimientos_entrada_id' => $movimientoEntrada['0']['MovimientosEntrada']['id'], 'MovimientosEntradasDetalle.producto_id' => $producto['Producto']['id'])));
                echo $precioProducto['MovimientosEntradasDetalle']['precio_producto'];
            }
        }
    }

    function edit($id = null) {

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid movimientos entrada', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['MovimientosEntrada']['factura_subtotal'] = (is_float($this->data['MovimientosEntrada']['factura_subtotal']) ? $this->data['MovimientosEntrada']['factura_subtotal'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_subtotal']));
            $this->data['MovimientosEntrada']['factura_iva'] = (is_float($this->data['MovimientosEntrada']['factura_iva']) ? $this->data['MovimientosEntrada']['factura_iva'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_iva']));
            $this->data['MovimientosEntrada']['factura_total'] = (is_float($this->data['MovimientosEntrada']['factura_total']) ? $this->data['MovimientosEntrada']['factura_total'] : sprintf("%.8f", $this->data['MovimientosEntrada']['factura_total']));
//            print_r($this->data);
            if ($this->MovimientosEntrada->save($this->data)) {
                $this->Session->setFlash(__('El movimiento de entrada ha sido modificado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El movimientos entrada no puede ser guardardo. Intente de nuevo', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->MovimientosEntrada->read(null, $id);
        }
        $tipoMovimientos = $this->MovimientosEntrada->TipoMovimiento->find('list', array('fields' => 'TipoMovimiento.nombre_tipo_movimiento', 'order' => 'TipoMovimiento.nombre_tipo_movimiento', 'conditions' => array('TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'E')));
        $proveedores = $this->MovimientosEntrada->Proveedore->find('list', array('fields' => 'Proveedore.nombre_proveedor', 'order' => 'Proveedore.nombre_proveedor', 'conditions' => array('Proveedore.estado' => true)));
        $bodegas = $this->MovimientosEntrada->Bodega->find('list', array('fields' => 'Bodega.nombre_bodega', 'order' => 'Bodega.nombre_bodega', 'conditions' => array('Bodega.estado_bodega' => true)));
        $tipoFormasPagos = $this->MovimientosEntrada->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        // $users = $this->MovimientosEntrada->User->find('list');
        $this->set(compact('tipoMovimientos', /* 'empresas', */ 'proveedores', 'tipoCategorias', 'bodegas', 'tipoFormasPagos'/* , 'users' */));
        $this->set('id', $id);
    }

    function delete($id = null) {
        if (!empty($id)) {
            $movimientos_entradas = "UPDATE movimientos_entradas SET estado_movimiento = false WHERE id = " . $id . "; UPDATE movimientos_entradas_detalles SET estado_entrada = false WHERE movimientos_entrada_id = " . $id . ";";
            $this->MovimientosEntrada->query($movimientos_entradas);
            $this->Session->setFlash(__('El movimiento de entrada ha sido activado de nuevo', true));
            $this->redirect(array('action' => 'index'));
        }
        /* if (!$id) {
          $this->Session->setFlash(__('Invalid id for movimientos entrada', true));
          $this->redirect(array('action' => 'index'));
          }
          if ($this->MovimientosEntrada->delete($id)) {
          $this->Session->setFlash(__('Movimientos entrada deleted', true));
          $this->redirect(array('action' => 'index'));
          }
          $this->Session->setFlash(__('Movimientos entrada was not deleted', true));
          $this->redirect(array('action' => 'index')); */
    }

}
