<?php

/**
 * Parse data to make Reports. Extends from AppController Cake Class
 * @method void isAuthorized() isAuthorized() validate user's priviliges
 * @method array info_general_pedidos() info_general_pedidos() parse data by pedidos model to generate report
 * @method array info_detallado_pedidos() info_detallado_pedidos() parse data by pedidos model to generate report with extra info
 * @method array info_productos() info_productos() parse data by productos model to generate report
 * @method array info_pedidos_aprobados() info_pedidos_aprobados() parse data by pedidos approved to generate report
 */

class InformesController extends AppController {

    var $name = "Informes";
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Empresa', 'Sucursale', 'PedidosDetalle', 'Producto', 'EstadoPedido', 'VInformeGeneral', 'VInformeDetallado',
        'TipoPedido', 'Plantilla', 'PlantillasDetalle', 'VCantidadProducto', 'VConsolidadoFacturado', 'VAcumuladoProducto');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Informes', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Informes', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        
    }

    function info_general_pedidos() {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('pedidos', array());
        $this->VInformeGeneral->set($this->data);
        if (!empty($this->data)) {

            if (strtotime($this->data['PedidosDetalle']['pedido_fecha_inicio']) > strtotime($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $this->Session->setFlash(__('La fecha de inicio (' . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . ') es mayor a la fecha de corte (' . $this->data['PedidosDetalle']['pedido_fecha_corte'] . ').', true));
            } else {

                // print_r($this->data);
                $conditions = array();
                if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                    $where = "+VInformeGeneral+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                } else {
                    if (count($conditions_empresa) == 0) {
                        
                    } else {
                        $where = "+VInformeGeneral+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                        $where = str_replace('+', '"', $where);
                        array_push($conditions, $where);
                    }
                }
                if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                    $where = "+VInformeGeneral+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
                if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                    $where = "+VInformeGeneral+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
                if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                    $where = "+VInformeGeneral+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
                if (($this->data['PedidosDetalle']['tipo_pedido_id']) > 0) {
                    $where = "+VInformeGeneral+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
                // print_r($conditions);
                $pedidos = $this->VInformeGeneral->find('all', array('conditions' => $conditions));
                $this->set('pedidos', $pedidos);
            }
        }

      $this->set('pedidos', $pedidos);

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array()));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipo_pedido'));
    }

    function info_detallado_pedidos() {
        ini_set('memory_limit', '256M');
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('detalles', array());
        $this->set('data_estados', array());

        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            //  print_r($this->data);
            $conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VInformeDetallado+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            } else {
                if (count($conditions_empresa) == 0) {
                    
                } else {
                    $where = "+VInformeDetallado+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VInformeDetallado+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+VInformeDetallado+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VInformeDetallado+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);

                if (!empty($this->data['PedidosDetalle']['empresa_id'])) {
                    $sql = "SELECT count(tb.cantidad) as cantidad, tb.nombre_estado, tb.estado_pedidos
                            FROM (SELECT pedidos.id AS cantidad, estado_pedidos.id as estado_pedidos, estado_pedidos.nombre_estado
                                    FROM pedidos_detalles, pedidos, estado_pedidos 
                                    WHERE pedido_fecha BETWEEN '" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "' AND '" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'
                                    AND empresa_id = " . $this->data['PedidosDetalle']['empresa_id'] . "
                                    AND pedidos.id = pedidos_detalles.pedido_id
                                    AND pedidos.pedido_estado_pedido = estado_pedidos.id
                                    
                                    AND pedidos.tipo_pedido_id = " . $this->data['PedidosDetalle']['tipo_pedido_id'] . "
                                    GROUP BY pedidos.id, estado_pedidos.nombre_estado, estado_pedidos.id) tb
                            GROUP BY tb.nombre_estado, tb.estado_pedidos
                            ORDER BY tb.estado_pedidos;
                            "; // AND pedidos.pedido_estado_pedido = " . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "
                    $data_estados = $this->PedidosDetalle->query($sql);
                    $this->set('data_estados', $data_estados);
                } else {
                    $this->set('data_estados', array());
                }
            }
            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                $where = "+VInformeDetallado+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+VInformeDetallado+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $detalles = $this->VInformeDetallado->find('all', array('conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.id'));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function info_productos() {
        $this->layout = 'none';
        $this->set('productos', $this->Producto->find('all', array(
                    'conditions' => array('Producto.estado' => true),
                    'order' => 'Producto.codigo_producto')));
    }

    function info_pedidos_aprobados() {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('detalles', array());
        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['PedidosDetalle']['fecha_aprobado_pedido'])) {
                //  print_r($this->data);
                $conditions = array('Pedido.pedido_estado_pedido' => array('4', '5'));
                if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                    $where = "+Pedido+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                } else {
                    if (count($conditions_empresa) == 0) {
                        
                    } else {
                        $where = "+Pedido+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                        $where = str_replace('+', '"', $where);
                        array_push($conditions, $where);
                    }
                }
                if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                    $where = "+Pedido+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
                if (!empty($this->data['PedidosDetalle']['fecha_aprobado_pedido'])) {
                    $where = "+Pedido+.+fecha_aprobado_pedido+::date ='" . $this->data['PedidosDetalle']['fecha_aprobado_pedido'] . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);

                    if (!empty($this->data['PedidosDetalle']['empresa_id'])) {
                        $sql = "SELECT count(tb.cantidad) as cantidad, tb.nombre_estado, tb.estado_pedidos
                            FROM (SELECT pedidos.id AS cantidad, estado_pedidos.id as estado_pedidos, estado_pedidos.nombre_estado
                                    FROM pedidos_detalles, pedidos, estado_pedidos 
                                    WHERE fecha_aprobado_pedido::date = '" . $this->data['PedidosDetalle']['fecha_aprobado_pedido'] . "'
                                    AND empresa_id = " . $this->data['PedidosDetalle']['empresa_id'] . "
                                    AND pedidos.id = pedidos_detalles.pedido_id
                                    AND pedidos.pedido_estado_pedido = estado_pedidos.id
                                    -- AND pedido_estado_pedido = 4
                                    AND pedido_estado_pedido in (4,5)
                                    GROUP BY pedidos.id, estado_pedidos.nombre_estado, estado_pedidos.id) tb
                            GROUP BY tb.nombre_estado, tb.estado_pedidos
                            ORDER BY tb.estado_pedidos;
                            ";
                        $data_estados = $this->PedidosDetalle->query($sql);
                        $this->set('data_estados', $data_estados);
                    } else {
                        $this->set('data_estados', array());
                    }
                }

                $detalles = $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.pedido_id', 'conditions' => $conditions));
                $this->set('detalles', $detalles);

                $pedidos = $this->PedidosDetalle->find('all', array('fields' => 'DISTINCT PedidosDetalle.pedido_id', 'order' => 'PedidosDetalle.pedido_id', 'conditions' => $conditions));
                $this->set('pedidos', $pedidos);
            } else {
                $this->Session->setFlash(__('Debe seleccionar como mínimo un fecha de aprobación de pedido.', true));
            }
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $this->set(compact('empresas', 'sucursales', 'estados'));
    }

    function info_estados_pedido() {


        /* SELECT count(*) AS cantidad, estado_pedidos.nombre_estado
          FROM pedidos
          RIGHT JOIN estado_pedidos ON pedidos.pedido_estado_pedido = estado_pedidos.id
          GROUP BY estado_pedidos.nombre_estado, pedidos.pedido_estado_pedido */
    }

    function info_plantillas($id = null) {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions = array(); //     
        } else {
            $conditions = array('Plantilla.empresa_id' => $this->Session->read('Auth.User.empresa_id')); //     
        }


        /*  $this->paginate = array('limit' => 100);
          $this->helpers['Paginator'] = array('ajax' => 'Ajax'); */
        $plantilla_detalles = array();
        $this->set('plantillas', $this->Plantilla->find('all', array('conditions' => $conditions, 'order' => 'Plantilla.nombre_plantilla')));


        if (!empty($id)) {
            $plantilla_detalles = $this->PlantillasDetalle->find('all', array('order' => 'Producto.nombre_producto', 'conditions' => array('PlantillasDetalle.plantilla_id' => $id)));
            $this->set('id', $id);
        }
        $this->set('plantilla_detalles', $plantilla_detalles);
    }

    function info_cantidad_productos() {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('detalles', array());
        $this->VCantidadProducto->set($this->data);
        if (!empty($this->data)) {
            //  print_r($this->data);
            $conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VCantidadProducto+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            } else {
                if (count($conditions_empresa) == 0) {
                    
                } else {
                    $where = "+VCantidadProducto+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VCantidadProducto+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VCantidadProducto+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                $where = "+VCantidadProducto+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+VCantidadProducto+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+VCantidadProducto+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            $detalles = $this->VCantidadProducto->find('all', array('fields' => 'VCantidadProducto.codigo_producto, VCantidadProducto.nombre_producto, SUM(VCantidadProducto.cantidad_pedido) as cantidad', 'order' => 'VCantidadProducto.nombre_producto', 'group' => 'VCantidadProducto.codigo_producto, VCantidadProducto.nombre_producto', 'conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.id'));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
    }

    function info_consolidado_facturado() {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('detalles', array());
        $this->VConsolidadoFacturado->set($this->data);
        if (!empty($this->data)) {
            //  print_r($this->data);
            $conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VConsolidadoFacturado+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            } else {
                if (count($conditions_empresa) == 0) {
                    
                } else {
                    $where = "+VConsolidadoFacturado+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VConsolidadoFacturado+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+VConsolidadoFacturado+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VConsolidadoFacturado+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                $where = "+VConsolidadoFacturado+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+VConsolidadoFacturado+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            $detalles = $this->VConsolidadoFacturado->find('all', array('fields' => 'VConsolidadoFacturado.empresa,
                            VConsolidadoFacturado.tipo_doc,
                            VConsolidadoFacturado.prefijo,
                            VConsolidadoFacturado.no_doc,
                            VConsolidadoFacturado.fecha,
                            VConsolidadoFacturado.benefic,
                            VConsolidadoFacturado.nota_concepto,
                            VConsolidadoFacturado.bloq_act,
                            VConsolidadoFacturado.forma_pago,
                            VConsolidadoFacturado.verificado,
                            VConsolidadoFacturado.producto,
                            VConsolidadoFacturado.bodega,
                            VConsolidadoFacturado.u_medida,
                            SUM(VConsolidadoFacturado.cantidad) as cantidad,
                            VConsolidadoFacturado.valor_unit,
                            VConsolidadoFacturado.iva,
                            VConsolidadoFacturado.centro_costos,
                            VConsolidadoFacturado.vencimiento,
                            SUM(VConsolidadoFacturado.cantidad_conver) as cantidad_conver,
                            VConsolidadoFacturado.valor_conver',
                'group' => 'VConsolidadoFacturado.empresa,
                            VConsolidadoFacturado.tipo_doc,
                            VConsolidadoFacturado.prefijo,
                            VConsolidadoFacturado.no_doc,
                            VConsolidadoFacturado.fecha,
                            VConsolidadoFacturado.benefic,
                            VConsolidadoFacturado.nota_concepto,
                            VConsolidadoFacturado.bloq_act,
                            VConsolidadoFacturado.forma_pago,
                            VConsolidadoFacturado.verificado,
                            VConsolidadoFacturado.producto,
                            VConsolidadoFacturado.bodega,
                            VConsolidadoFacturado.u_medida,
                            VConsolidadoFacturado.valor_unit,
                            VConsolidadoFacturado.iva,
                            VConsolidadoFacturado.centro_costos,
                            VConsolidadoFacturado.vencimiento,
                            VConsolidadoFacturado.valor_conver',
                'order' => 'VConsolidadoFacturado.producto',
                'conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.id'));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function info_acumulado_productos() {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions_empresa = array(); // Muestra todas las empresas
        } else {
            $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario          
        }

        $this->set('detalles', array());
        $this->VAcumuladoProducto->set($this->data);
        if (!empty($this->data)) {
            //  print_r($this->data);
            $conditions = array();
            $conditions_sucursal = array('Sucursale.estado_sucursal' => true);
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VAcumuladoProducto+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);

                $where_sucursal = "+Sucursale+.+id_empresa+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where_sucursal = str_replace('+', '"', $where_sucursal);
                array_push($conditions_sucursal, $where_sucursal);
            } else {
                if (count($conditions_empresa) == 0) {
                    
                } else {
                    $where = "+VAcumuladoProducto+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
            }

            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VAcumuladoProducto+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VAcumuladoProducto+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+VAcumuladoProducto+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            /* if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
              $where = "+VAcumuladoProducto+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
              $where = str_replace('+', '"', $where);
              array_push($conditions, $where);
              }

              if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
              $where = "+VAcumuladoProducto+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
              $where = str_replace('+', '"', $where);
              array_push($conditions, $where);
              } */

            $detalles = $this->VAcumuladoProducto->find('all', array('fields' => 'VAcumuladoProducto.nombre_empresa, VAcumuladoProducto.nombre_sucursal, VAcumuladoProducto.regional_sucursal, VAcumuladoProducto.nombre_producto, VAcumuladoProducto.tipo_categoria_descripcion, SUM(VAcumuladoProducto.cantidad_pedido) as cantidad, MAX(precio_producto) as precio_producto', 'order' => 'VAcumuladoProducto.nombre_sucursal', 'group' => 'VAcumuladoProducto.nombre_empresa, VAcumuladoProducto.nombre_sucursal, VAcumuladoProducto.regional_sucursal, VAcumuladoProducto.nombre_producto, VAcumuladoProducto.tipo_categoria_descripcion', 'conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursal));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.id'));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        /* $regional = array('ANT' => 'ANT',
          'BUC' => 'BUC',
          'CEN' => 'CEN',
          'NOR' => 'NOR',
          'OCC' => 'OCC',
          'SAB' => 'SAB',
          'SUR' => 'SUR'); */
        $this->set('regional', $regional);
    }

}

?>
