<?php

class InformesController extends AppController {

    var $name = "Informes";
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Pedido', 'Empresa', 'EmpresasAprobadore', 'Sucursale', 'PedidosDetalle', 'Producto', 'EstadoPedido', 'VInformeGeneral', 'VInformeDetallado',
        'TipoPedido', 'Plantilla', 'PlantillasDetalle', 'VCantidadProducto', 'VConsolidadoFacturado', 'VAcumuladoProducto',
        'MovimientosProducto', 'MovimientosEntrada', 'MovimientosEntradasDetalle', 'MovimientosSalidasDetalle', 'VFacturaCompra', 'VInformePlantillaPrecio',
        'ListadoLlamadasDetalle', 'User', 'TipoCategoria', 'VInformeCantidadesEp', 'OrdenComprasDetalle', 'VPedidoCabeceraSap', 'VPedidoDetallesSap',
        'Solicitud', 'SolicitudesDetalle', 'TipoSolicitude', 'TipoEstadoSolicitud', 'TipoMotivosSolicitud', 'Encuesta', 'EncuestasDiligenciada', 'EncuestasPregunta', 'EncuestasRespuesta');

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
        return true;
    }

    function info_plantilla_precio() {
        $detalles = array();
        $conditions = array();
        $this->Plantilla->set($this->data);
        if (!empty($this->data)) {

            if (($this->data['Plantilla']['id']) > 0) {
                $where = "+VInformePlantillaPrecio+.+id+ = '" . $this->data['Plantilla']['id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->set('detalles', $this->VInformePlantillaPrecio->find('all', array('conditions' => $conditions)));
        }

        //   


        $plantillas = $this->Plantilla->find('list', array('fields' => 'Plantilla.nombre_plantilla', 'order' => 'Plantilla.plantilla_base DESC, Plantilla.nombre_plantilla', 'conditions' => array('Plantilla.estado_plantilla' => true)));
        $this->set(compact('plantillas'));
    }

    function info_avance_pedidos() {
        $conditions = array('estado_empresa' => true);
        $data = array();
        $this->Empresa->set($this->data);
        if (!empty($this->data)) {
            if (($this->data['Empresa']['empresa_id']) > 0) {
                $empresa_id = $this->data['Empresa']['empresa_id'];
            }
            if (($this->data['Empresa']['vendedor_id']) > 0) { // anio
                $anio = $this->data['Empresa']['vendedor_id'];
            }
            if (($this->data['Empresa']['parametro_precio']) > 0) { // mes
                $mes = $this->data['Empresa']['parametro_precio'];
            }

            if (!empty($empresa_id) && !empty($anio) && !empty($mes)) {

                $data = $this->Empresa->query("SELECT 
                        '" . $anio . "' AS anio,
                        '" . $mes . "' AS mes,
                        (SELECT empresas.nombre_empresa FROM empresas WHERE empresas.id =  d1.empresa_id),
                        count(d1.sucursal_id) as total_pedidos,
                        (SELECT nombre_tipo_pedido FROM tipo_pedidos WHERE tipo_pedidos.id = d1.tipo_pedido_id),
                        d1.sucursales_activas,
                        d1.sucursales_inactivas,
                        ((count(d1.sucursal_id)*100)/d1.sucursales_activas) as porcentaje,
                        ( SELECT array_to_string(ARRAY(SELECT sucursales.nombre_sucursal FROM sucursales WHERE sucursales.id_empresa = " . $empresa_id . " AND sucursales.id NOT IN (SELECT pedidos.sucursal_id FROM pedidos 
                                WHERE DATE_PART('month', pedidos.fecha_aprobado_pedido)='" . $mes . "' 
                                AND DATE_PART('year', pedidos.fecha_aprobado_pedido)='" . $anio . "'
                                AND pedidos.empresa_id= " . $empresa_id . "
                                AND pedidos.tipo_pedido_id = d1.tipo_pedido_id
                                AND pedidos.pedido_estado_pedido > 3)), ', '::text) AS array_to_string) AS sucursales_sin_pedidos
                                FROM (
                                        -- CONSULTA INTERNA
                                SELECT pedidos.empresa_id as empresa_id, 
                                pedidos.tipo_pedido_id as tipo_pedido_id,
                                pedidos.sucursal_id as sucursal_id, 
                                count(pedidos.id) as pedidos_aprobados,
                                (SELECT count(sucursales.id) FROM sucursales WHERE sucursales.id_empresa = pedidos.empresa_id AND sucursales.estado_sucursal = true) as sucursales_activas, 
                                (SELECT count(sucursales.id) FROM sucursales WHERE sucursales.id_empresa = pedidos.empresa_id AND sucursales.estado_sucursal = false)  as sucursales_inactivas	 	
                                FROM pedidos 
                                WHERE DATE_PART('month', pedidos.fecha_aprobado_pedido)='" . $mes . "' 
                                AND DATE_PART('year', pedidos.fecha_aprobado_pedido)='" . $anio . "'
                                AND pedidos.empresa_id= " . $empresa_id . "
                                AND pedidos.pedido_estado_pedido > 3 
                                GROUP BY pedidos.empresa_id, pedidos.sucursal_id, pedidos.tipo_pedido_id
                                ORDER BY pedidos.empresa_id, pedidos.sucursal_id, pedidos.tipo_pedido_id
                                ) AS d1
                        GROUP BY d1.empresa_id, d1.sucursales_activas, d1.sucursales_inactivas, d1.tipo_pedido_id");


//                echo "SELECT 
//                        '" . $anio . "' AS anio,
//                        '" . $mes . "' AS mes,
//                        (SELECT empresas.nombre_empresa FROM empresas WHERE empresas.id =  d1.empresa_id),
//                        count(d1.sucursal_id) as total_pedidos,
//                        (SELECT nombre_tipo_pedido FROM tipo_pedidos WHERE tipo_pedidos.id = d1.tipo_pedido_id),
//                        d1.sucursales_activas,
//                        d1.sucursales_inactivas,
//                        ((count(d1.sucursal_id)*100)/d1.sucursales_activas) as porcentaje,
//                        ( SELECT array_to_string(ARRAY(SELECT sucursales.nombre_sucursal FROM sucursales WHERE sucursales.id_empresa = " . $empresa_id . " AND sucursales.id NOT IN (SELECT pedidos.sucursal_id FROM pedidos 
//                                WHERE DATE_PART('month', pedidos.fecha_aprobado_pedido)='" . $mes . "' 
//                                AND DATE_PART('year', pedidos.fecha_aprobado_pedido)='" . $anio . "'
//                                AND pedidos.empresa_id= " . $empresa_id . "
//                                AND pedidos.tipo_pedido_id = d1.tipo_pedido_id
//                                AND pedidos.pedido_estado_pedido > 3)), ', '::text) AS array_to_string) AS sucursales_sin_pedidos
//                        FROM (
//                                SELECT pedidos.empresa_id as empresa_id, 
//                                pedidos.tipo_pedido_id as tipo_pedido_id,
//                                pedidos.sucursal_id as sucursal_id, 
//                                count(pedidos.id) as pedidos_aprobados,
//                                (SELECT count(sucursales.id) FROM sucursales WHERE sucursales.id_empresa = pedidos.empresa_id AND sucursales.estado_sucursal = true) as sucursales_activas, 
//                                (SELECT count(sucursales.id) FROM sucursales WHERE sucursales.id_empresa = pedidos.empresa_id AND sucursales.estado_sucursal = false)  as sucursales_inactivas	 	
//                                FROM pedidos 
//                                WHERE DATE_PART('month', pedidos.fecha_aprobado_pedido)='" . $mes . "' 
//                                AND DATE_PART('year', pedidos.fecha_aprobado_pedido)='" . $anio . "'
//                                AND pedidos.empresa_id= " . $empresa_id . "
//                                AND pedidos.pedido_estado_pedido > 3 
//                                GROUP BY pedidos.empresa_id, pedidos.sucursal_id, pedidos.tipo_pedido_id
//                                ORDER BY pedidos.empresa_id, pedidos.sucursal_id, pedidos.tipo_pedido_id
//                                ) AS d1
//                        GROUP BY d1.empresa_id, d1.sucursales_activas, d1.sucursales_inactivas, d1.tipo_pedido_id";
            }
        }

        $this->set('data', $data);

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions));
        $anio = array('2022' => '2022');
        $mes = array('01' => 'Ene',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dic');
        $this->set(compact('empresas', 'anio', 'mes'));
    }

    function info_transportadora() {
        // Configure::write('debug', 2);
        // Crear Archivo Plano
        $file_name = 'informes/InformeTransportadora_' . date('his') . '.csv';
        $file = fopen($file_name, 'w');
        // Escribir encabezado del arhivo
        $data_csv = utf8_decode("NombreCliente;Regional;Direccion;Ciudad;Departamento\n");
        fwrite($file, $data_csv);
        $conditions = array('estado_empresa' => true);

        $this->Empresa->set($this->data);
        if (!empty($this->data)) {


            $whereEmpresa = '';
            if (($this->data['Empresa']['empresa_id']) > 0) {
                $whereEmpresa = "WHERE nit_empresa IN (SELECT nit_empresa FROM empresas WHERE id = " . $this->data['Empresa']['empresa_id'] . ")";
            }

            $data = $this->Empresa->query("SELECT nombre_sucursal, regional_sucursal, direccion_sucursal, municipio_sucursal, depto_sucursal 
            FROM v_empresas_sucursales
            " . $whereEmpresa . "
            ORDER BY nombre_sucursal, depto_sucursal, municipio_sucursal ASC;");
            // print_r($data);
            foreach ($data as $value) {
                $data_csv = utf8_decode($value['0']['nombre_sucursal']) . ';' .
                        utf8_decode($value['0']['regional_sucursal']) . ';' .
                        utf8_decode($value['0']['direccion_sucursal']) . ';' .
                        utf8_decode($value['0']['municipio_sucursal']) . ';' .
                        utf8_decode($value['0']['depto_sucursal']);
                fwrite($file, $data_csv);
                fwrite($file, chr(13) . chr(10));
            }
            fclose($file);
            $this->set('file_name', $file_name);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions));
        $this->set(compact('empresas'));
    }

    function info_encuesta() {
        // Configure::write('debug', 2);
        // Crear archivo plano csv

        $this->set('encuestaDiligenciada', array());
        $conditions = array('Encuesta.estado_encuesta' => true);
        $conditionsDiligenciada = array();
        $whereEmpresa = '';
        $file_name = '';
        $this->Encuesta->set($this->data);
        if (!empty($this->data)) {
            if (($this->data['Encuesta']['empresa_id']) > 0) {
                $where = "+Encuesta+.+empresa_id+ = '" . $this->data['Encuesta']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
                $whereEmpresa = "AND encuestas.empresa_id = " . $this->data['Encuesta']['empresa_id'] . "";
            }


            $encuesta = $this->Encuesta->find('first', array('order' => 'Encuesta.id', 'conditions' => $conditions));

            if (!empty($this->data['Encuesta']['encuesta_fecha_inicio']) && !empty($this->data['Encuesta']['encuesta_fecha_fin'])) {
                // Crear Archivo Plano
                $file_name = 'informes/InformeEncuestas_' . $this->data['Encuesta']['encuesta_fecha_inicio'] . '_' . $this->data['Encuesta']['encuesta_fecha_fin'] . '_' . date('his') . '.csv';
                $file = fopen($file_name, 'w');
                // Escribir encabezado del arhivo
                $data_csv = utf8_decode("Empresa;Regional;Sucursal;OrdenPedido;FechaDiligenciada;Pregunta;Respuesta\n"); // DeferredTax;
                fwrite($file, $data_csv);

                $data = $this->EncuestasRespuesta->query("SELECT
                (SELECT nombre_empresa FROM empresas WHERE encuestas_diligenciadas.empresa_id = empresas.id) AS empresa,
                (SELECT regional_sucursal FROM sucursales WHERE encuestas_diligenciadas.sucursal_id = sucursales.id) AS regional,
                (SELECT nombre_sucursal FROM sucursales WHERE encuestas_diligenciadas.sucursal_id = sucursales.id) AS sucursal,
                -- encuestas_diligenciadas.sucursal_id,
                '#000'||encuestas_diligenciadas.pedido_id AS pedido, 
                encuestas_diligenciadas.fecha_encuesta::date ,
                -- encuestas_preguntas.id,
                encuestas_preguntas.pregunta_encuesta,
                encuestas_respuestas.encuestas_respuestas_id,
                CASE
                 WHEN encuestas_respuestas.encuestas_respuestas_id = '1' THEN 'NO APLICA'
                 WHEN encuestas_respuestas.encuestas_respuestas_id = '2' THEN 'MALO'
                 WHEN encuestas_respuestas.encuestas_respuestas_id = '3' THEN 'REGULAR'
                 WHEN encuestas_respuestas.encuestas_respuestas_id = '4' THEN 'BUENO'
                 WHEN encuestas_respuestas.encuestas_respuestas_id = '5' THEN 'EXCELENTE'
                END AS puntaje
                FROM encuestas,
                encuestas_diligenciadas,
                encuestas_preguntas,
                encuestas_respuestas
                WHERE encuestas.id = encuestas_diligenciadas.encuesta_id
                AND encuestas.id = encuestas_preguntas.encuesta_id
                AND encuestas_preguntas.id = encuestas_respuestas.encuestas_preguntas_id
                " . $whereEmpresa . "
                AND encuestas.estado_encuesta = true
                AND encuestas_diligenciadas.fecha_encuesta::date BETWEEN '" . $this->data['Encuesta']['encuesta_fecha_inicio'] . "' AND '" . $this->data['Encuesta']['encuesta_fecha_fin'] . "'
                AND encuestas_diligenciadas.id = encuestas_respuestas.encuestas_diligenciadas_id");
                // print_r($data);
                foreach ($data as $value) {
                    $data_csv = utf8_decode($value['0']['empresa']) . ';' .
                            utf8_decode($value['0']['regional']) . ';' .
                            utf8_decode($value['0']['sucursal']) . ';' .
                            utf8_decode($value['0']['pedido']) . ';' .
                            utf8_decode($value['0']['fecha_encuesta']) . ';' .
                            utf8_decode($value['0']['pregunta_encuesta']) . ';' .
                            utf8_decode($value['0']['puntaje']);
                    fwrite($file, $data_csv);
                    fwrite($file, chr(13) . chr(10));
                }
                fclose($file);
                $this->set('file_name', $file_name);

                $conditionsDiligenciada = array('EncuestasDiligenciada.encuesta_id' => $encuesta['Encuesta']['id']);
                /* if (($this->data['Encuesta']['sucursal_id']) > 0) {
                  $whereDiligenciada = "+EncuestasDiligenciada+.+sucursal_id+ = '" . $this->data['Encuesta']['sucursal_id'] . "'";
                  $whereDiligenciada = str_replace('+', '"', $whereDiligenciada);
                  array_push($conditionsDiligenciada, $whereDiligenciada);
                  }
                 */
                $whereDiligenciada = "+EncuestasDiligenciada+.+fecha_encuesta::date+ BETWEEN +'" . $this->data['Encuesta']['encuesta_fecha_inicio'] . "'+  AND +'" . $this->data['Encuesta']['encuesta_fecha_fin'] . "'+";
                $whereDiligenciada = str_replace('+', '"', $whereDiligenciada);
                array_push($conditionsDiligenciada, $whereDiligenciada);

                $encuestaDiligenciada = $this->EncuestasDiligenciada->find('list', array('fields' => 'EncuestasDiligenciada.id', 'conditions' => $conditionsDiligenciada));
                $this->set('encuestaDiligenciada', count($encuestaDiligenciada));
                //$encuestasRespuestas = $this->EncuestasRespuesta->find('all',array('conditions'=>array('encuestas_diligenciadas_id'=>$encuestaDiligenciada)));
                //print_r($encuestasRespuestas);
                $resultados = array();
                if (count($encuestaDiligenciada) > 0) {
                    $resultados = $this->EncuestasRespuesta->query("SELECT 
                    id,
                    pregunta_encuesta,
                    (SELECT COUNT(encuestas_respuestas.encuestas_respuestas_id)
                    FROM encuestas_respuestas
                    WHERE encuestas_respuestas.encuestas_preguntas_id = encuestas_preguntas.id
                    AND encuestas_respuestas.encuestas_diligenciadas_id IN (" . implode(',', $encuestaDiligenciada) . ")
                    AND encuestas_respuestas.encuestas_respuestas_id = 1) AS NO_APLICA,
                    (SELECT COUNT(encuestas_respuestas.encuestas_respuestas_id)
                    FROM encuestas_respuestas
                    WHERE encuestas_respuestas.encuestas_preguntas_id = encuestas_preguntas.id
                    AND encuestas_respuestas.encuestas_diligenciadas_id IN (" . implode(',', $encuestaDiligenciada) . ")
                    AND encuestas_respuestas.encuestas_respuestas_id = 2) AS MALO,
                    (SELECT COUNT(encuestas_respuestas.encuestas_respuestas_id)
                    FROM encuestas_respuestas
                    WHERE encuestas_respuestas.encuestas_preguntas_id = encuestas_preguntas.id
                    AND encuestas_respuestas.encuestas_diligenciadas_id IN (" . implode(',', $encuestaDiligenciada) . ")
                    AND encuestas_respuestas.encuestas_respuestas_id = 3) AS REGULAR,
                    (SELECT COUNT(encuestas_respuestas.encuestas_respuestas_id)
                    FROM encuestas_respuestas
                    WHERE encuestas_respuestas.encuestas_preguntas_id = encuestas_preguntas.id
                    AND encuestas_respuestas.encuestas_diligenciadas_id IN (" . implode(',', $encuestaDiligenciada) . ")
                    AND encuestas_respuestas.encuestas_respuestas_id = 4) AS BUENO,
                    (SELECT COUNT(encuestas_respuestas.encuestas_respuestas_id)
                    FROM encuestas_respuestas
                    WHERE encuestas_respuestas.encuestas_preguntas_id = encuestas_preguntas.id
                    AND encuestas_respuestas.encuestas_diligenciadas_id IN (" . implode(',', $encuestaDiligenciada) . ")
                    AND encuestas_respuestas.encuestas_respuestas_id = 5) AS EXCELENTE
                    FROM encuestas_preguntas 
                    WHERE encuesta_id = " . $encuesta['Encuesta']['id'] . ";");
                }
                $this->set('resultados', $resultados);
            }
        }
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => array('104', '106', '107'))));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => array('id_empresa' => array('104', '106', '107'))));
        $this->set(compact('empresas', 'sucursales'));
    }

    /* INVENTARIOS */

    function inv_general() {
        $conditions = array('MovimientosProducto.estado' => true);
        $this->MovimientosProducto->set($this->data);
        if (!empty($this->data)) {
//            print_r($this->data['MovimientosProducto']['producto_id']);
//            echo count(implode(',', $this->data['MovimientosProducto']['producto_id']));
            if (count(implode(',', $this->data['MovimientosProducto']['producto_id'])) > 0) {
                if (count(implode(',', $this->data['MovimientosProducto']['producto_id'])) == 1 && implode(',', $this->data['MovimientosProducto']['producto_id']) == 0) {
                    $conditions = array();
                } else {
                    $where = "+MovimientosProducto+.+producto_id+ IN (" . implode(',', $this->data['MovimientosProducto']['producto_id']) . ")";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                }
            }
        }
        $movimientosEntrada = $this->MovimientosEntradasDetalle->find('all');
        $this->set('movimientosEntrada', $movimientosEntrada);

        $inventarios = $this->MovimientosProducto->find('all', array('conditions' => $conditions, 'order' => 'Producto.codigo_producto'));
        $this->set('inventarios', $inventarios);

        $productos_inventario = $this->MovimientosProducto->find('list', array('fields' => 'MovimientosProducto.producto_id', 'conditions' => array('MovimientosProducto.estado' => true)));
        $productos = $this->Producto->find('list', array('fields' => 'Producto.producto_completo', 'conditions' => array('Producto.id' => $productos_inventario), 'order' => 'Producto.codigo_producto'));
        $this->set(compact('productos'));
    }

    function inv_detalle_producto($id = null) {
        $producto_id = base64_decode($id);

        // Movimientos de Entrada
        $movimientosEntrada = $this->MovimientosEntradasDetalle->find('all', array('conditions' => array('estado_entrada' => true, 'producto_id' => $producto_id)));
        $dataMovimientosEntrada = array();
        foreach ($movimientosEntrada as $entrada):
            $dataMovimientosEntrada_tmp = array();
            $movimiento_entrada = $this->MovimientosEntrada->find('first', array('conditions' => array('MovimientosEntrada.id' => $entrada['MovimientosEntradasDetalle']['movimientos_entrada_id'])));

            $dataMovimientosEntrada_tmp['producto_completo'] = $entrada['Producto']['producto_completo'];
            $dataMovimientosEntrada_tmp['tipo_movimiento_id'] = 'E'; // $entrada['MovimientosEntrada']['tipo_movimiento_id'];
            $dataMovimientosEntrada_tmp['cantidad'] = $entrada['MovimientosEntradasDetalle']['cantidad_entrada'];
            $dataMovimientosEntrada_tmp['fecha_registro'] = $movimiento_entrada['MovimientosEntrada']['fecha_movimiento']; //$entrada['MovimientosEntradasDetalle']['fecha_registro_entrada'];
            $dataMovimientosEntrada_tmp['movimiento'] = $entrada['MovimientosEntradasDetalle']['movimientos_entrada_id'];
            $dataMovimientosEntrada_tmp['tipo_movimiento'] = $movimiento_entrada['TipoMovimiento']['nombre_tipo_movimiento'];
            $dataMovimientosEntrada_tmp['empresa_proveedor'] = (!empty($movimiento_entrada['Proveedore']['nombre_proveedor']) ? '<b>P: </b>' . $movimiento_entrada['Proveedore']['nombre_proveedor'] : '<b>E: </b>' . $movimiento_entrada['Empresa']['nombre_empresa']);
            $dataMovimientosEntrada_tmp['fecha_entero'] = strtotime($movimiento_entrada['MovimientosEntrada']['fecha_movimiento']); // strtotime($entrada['MovimientosEntradasDetalle']['fecha_registro_entrada']);
            array_push($dataMovimientosEntrada, $dataMovimientosEntrada_tmp);
        endforeach;

        // print_r($dataMovimientosEntrada);
        // Movimientos de Salida
        //$movimientosSalida = $this->MovimientosSalidasDetalle->find('all', array('conditions' => array('producto_id' => $producto_id, 'MovimientosSalidasDetalle.pedido_estado_pedido' => '4')));
        $movimientosSalida = $this->MovimientosSalidasDetalle->find('all', array('conditions' => array('producto_id' => $producto_id, 'MovimientosSalidasDetalle.pedido_estado_pedido' => '5')));
        $dataMovimientosSalida = array();
        foreach ($movimientosSalida as $salida):
            $dataMovimientosSalida_tmp = array();
            $dataMovimientosSalida_tmp['producto_completo'] = $salida['Producto']['producto_completo'];
            $dataMovimientosSalida_tmp['tipo_movimiento_id'] = 'S'; // $salida['MovimientosEntrada']['tipo_movimiento_id'];
            $dataMovimientosSalida_tmp['cantidad'] = $salida['MovimientosSalidasDetalle']['cantidad_pedido'];
            $dataMovimientosSalida_tmp['fecha_registro'] = $salida['MovimientosSalidasDetalle']['fecha_salida_detalle'];
            $pedidos = $this->Pedido->find('first', array('conditions' => array('Pedido.id' => $salida['MovimientosSalidasDetalle']['pedido_id'])));
            // print_r($pedidos);
            $dataMovimientosSalida_tmp['movimiento'] = $salida['MovimientosSalidasDetalle']['pedido_id'];
            $dataMovimientosSalida_tmp['tipo_movimiento'] = '';
            $dataMovimientosSalida_tmp['empresa_proveedor'] = $pedidos['Empresa']['nombre_empresa'];
            $dataMovimientosSalida_tmp['fecha_entero'] = strtotime($salida['MovimientosSalidasDetalle']['fecha_salida_detalle']);
            array_push($dataMovimientosSalida, $dataMovimientosSalida_tmp);
        endforeach;

        $movimientos = array_merge($dataMovimientosEntrada, $dataMovimientosSalida);

        usort($movimientos, function($a, $b) {
            return $a['fecha_entero'] - $b['fecha_entero'];
        });

        $this->set('movimientos', $movimientos);
    }

    function inv_observaciones_producto($id = null) {
        $producto_id = base64_decode($id);
        $this->set('detalles', $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => array('Pedido.pedido_estado_pedido' => '4', 'PedidosDetalle.producto_id' => $producto_id))));
    }

    /* FIN INVENTARIOS */

    /*  ORDENES DE COMPRA */

    function info_sugeridos_compra() {
        // SUGERIDO DE COMPRA  
        $conditions = array();
        $inventarios = $this->MovimientosProducto->find('all', array('conditions' => $conditions, 'order' => 'Producto.codigo_producto'));
        $this->set('inventarios', $inventarios);

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
    }

    /*  FIN ORDENES DE COMPRA */

    function index() {
        
    }

    function info_general_pedidos() {
        ini_set('memory_limit', '1024M');
        //Configure::write('debug', 2);
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VInformeGeneral.empresa_id' => array_unique($empresas_permisos), 'VInformeGeneral.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 

        /* if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muestra solo las sucursales del usuario
          } */

        $this->set('pedidos', array());
        $this->VInformeGeneral->set($this->data);
        if (!empty($this->data)) {

            if (strtotime($this->data['PedidosDetalle']['pedido_fecha_inicio']) > strtotime($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $this->Session->setFlash(__('La fecha de inicio (' . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . ') es mayor a la fecha de corte (' . $this->data['PedidosDetalle']['pedido_fecha_corte'] . ').', true));
            } else {

                // print_r($this->data);
                // $conditions = array();
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
                    // $where = "+VInformeGeneral+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                    $where = "+VInformeGeneral+.+fecha_aprobado_pedido::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
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

//
//        $pedidos = $this->VInformeGeneral->find('all', array());
//        $this->set('pedidos', $pedidos);

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
    }

    function info_detallado_pedidos_() {
        ini_set('memory_limit', '1024M');
//31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'), 'EmpresasAprobadore.empresa_id' => $empresas_permisos, 'EmpresasAprobadore.sucursal_id' => $sucursales_permisos);
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
//31052018 
        /*
          if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muetra solo las sucursales del usuario
          } */

        $this->set('detalles', array());
        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
//  print_r($this->data);
            // $conditions = array();
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
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+Pedido+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
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
                $where = "+Pedido+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            $detalles = $this->PedidosDetalle->find('all', array('order' => 'PedidosDetalle.producto_id', 'conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
    }

    function info_detallado_pedidos() {
        ini_set('memory_limit', '1024M');
        // Configure::write('debug', 2);
//31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }

        $conditions = array('VInformeDetallado.empresa_id' => array_unique($empresas_permisos), 'VInformeDetallado.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => array_unique($empresas_permisos));
        $conditions_sucursales = array('Sucursale.id' => array_unique($sucursales_permisos), 'Sucursale.estado_sucursal' => true);
//31052018 
        /* if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muetra solo las sucursales del usuario
          } */

        $this->set('detalles', array());
        $this->set('data_estados', array());

        $this->PedidosDetalle->set($this->data);
// print_r($this->data);
        if (!empty($this->data)) {
//  print_r($this->data);
            //$conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VInformeDetallado+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            } else {
                // ECHO count($conditions_empresa);
                if (count($conditions_empresa) == 0) {
                    $where = "+VInformeDetallado+.+empresa_id+ = '" . $this->Session->read('Auth.User.empresa_id') . "'";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                } else {
                    
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
                // $where = "+VInformeDetallado+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = "+VInformeDetallado+.+fecha_aprobado_pedido::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);

                if (!empty($this->data['PedidosDetalle']['empresa_id'])) {
                    $sql = "SELECT count(tb.cantidad) as cantidad, tb.nombre_estado, tb.estado_pedidos
                            FROM (SELECT pedidos.id AS cantidad, estado_pedidos.id as estado_pedidos, estado_pedidos.nombre_estado
                                    FROM pedidos_detalles, pedidos, estado_pedidos 
                                    WHERE fecha_aprobado_pedido::date BETWEEN '" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "' AND '" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'
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
            // print_r($conditions);
            $detalles = $this->VInformeDetallado->find('all', array('conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
        $this->set('empresa_logistica', $this->data['PedidosDetalle']['empresa_logistica']);
    }

    function info_detallado_despachos() {
        ini_set('memory_limit', '256M');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VInformeDetallado.empresa_id' => array_unique($empresas_permisos), 'VInformeDetallado.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /*
          if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('id_empresa !=' => '1', 'Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muestra solo las sucursales del usuario
          } */

        $this->set('detalles', array());
        $this->set('data_estados', array());

        $this->PedidosDetalle->set($this->data);
//print_r($this->data);
        if (!empty($this->data)) {
//  print_r($this->data);
            //$conditions = array();
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
                $where = "+VInformeDetallado+.+fecha_despacho+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . " 00:00:00'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . " 23:59:59'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+VInformeDetallado+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
// print_r($conditions);
            $detalles = $this->VInformeDetallado->find('all', array('conditions' => $conditions));
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'tipoPedido'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
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
        ini_set('memory_limit', '1024M'); // 2021/07/28
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('Pedido.empresa_id' => array_unique($empresas_permisos), 'Pedido.sucursal_id' => array_unique($sucursales_permisos), 'Pedido.pedido_estado_pedido' => array('4', '5'));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /*
          if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muestra solo las sucursales del usuario
          } */


        $this->set('detalles', array());
        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['PedidosDetalle']['fecha_aprobado_pedido'])) {
                //  print_r($this->data);
                // $conditions = array('Pedido.pedido_estado_pedido' => array('4', '5'));
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
                                    AND pedido_estado_pedido = 4
                                   -- AND pedido_estado_pedido in (4,5) -- 2021/07/28
                                    GROUP BY pedidos.id, estado_pedidos.nombre_estado, estado_pedidos.id) tb
                            GROUP BY tb.nombre_estado, tb.estado_pedidos
                            ORDER BY tb.estado_pedidos;";
                        $data_estados = $this->PedidosDetalle->query($sql);
                        $this->set('data_estados', $data_estados);
                    } else {
                        $this->set('data_estados', array());
                    }
                }


                $detalles = $this->PedidosDetalle->find('all', array('fields' => 'Empresa.nombre_empresa, Pedido.id,
                EstadoPedido.nombre_estado,
                Sucursale.regional_sucursal,
                Sucursale.nombre_sucursal,
                Sucursale.ceco_sucursal,
                Sucursale.oi_sucursal,
                TipoCategoria.tipo_categoria_descripcion,
                Producto.nombre_producto,
                Pedido.pedido_fecha,
                Pedido.fecha_aprobado_pedido,
                PedidosDetalle.cantidad_pedido,
                PedidosDetalle.medida_producto,
                PedidosDetalle.precio_producto,
                PedidosDetalle.iva_producto', 'order' => 'PedidosDetalle.pedido_id', 'conditions' => $conditions));
                $this->set('detalles', $detalles);

                $pedidos = $this->PedidosDetalle->find('all', array('fields' => 'DISTINCT PedidosDetalle.pedido_id', 'order' => 'PedidosDetalle.pedido_id', 'conditions' => $conditions));
                $this->set('pedidos', $pedidos);
            } else {
                $this->Session->setFlash(__('Debe seleccionar como mínimo un fecha de aprobación de pedido.', true));
            }
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
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
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        // Configure::write('debug', 2);
        /* if ($this->Session->read('Auth.User.rol_id') == '1' || $this->Session->read('Auth.User.rol_id') == '4') {
          $conditions = array(); //
          $array_empresa = array('Empresa.estado_empresa' => true);
          } else {
          $conditions = array('Plantilla.empresa_id' => $this->Session->read('Auth.User.empresa_id')); //
          $array_empresa = array('Empresa.estado_empresa' => true, 'Empresa.id' => $this->Session->read('Auth.User.empresa_id'));
          } */
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('Plantilla.empresa_id' => array_unique($empresas_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 

        $this->set('plantillas', array());
        $this->Plantilla->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Plantilla']['nombre_plantilla'])) {
                $where = "+Plantilla+.+nombre_plantilla+ ilike '%" . $this->data['Plantilla']['nombre_plantilla'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['Plantilla']['empresa_id']) > 0) {
                $where = "+Plantilla+.+empresa_id+ = '" . $this->data['Plantilla']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['Plantilla']['tipo_pedido_id']) > 0) {
                $where = "+Plantilla+.+tipo_pedido_id+ = '" . $this->data['Plantilla']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        /*  $this->paginate = array('limit' => 100);
          $this->helpers['Paginator'] = array('ajax' => 'Ajax'); */
        $plantilla_detalles = array();
        $this->set('plantillas', $this->Plantilla->find('all', array('conditions' => $conditions, 'order' => 'Plantilla.plantilla_base DESC, Plantilla.nombre_plantilla')));

        if (!empty($id)) {
            array_push($conditions, array('Plantilla.id' => base64_decode($id)));
            $plantillas = $this->Plantilla->find('all', array('conditions' => $conditions, 'order' => 'Plantilla.nombre_plantilla'));
            $this->set('plantillas', $plantillas);
            if (count($plantillas) > 0) {
                $plantilla_detalles = $this->PlantillasDetalle->find('all', array('order' => 'Producto.codigo_producto', 'conditions' => array('PlantillasDetalle.plantilla_id' => base64_decode($id))));
            } else {
                $plantilla_detalles = array();
            }

            $this->set('id', base64_decode($id));
        }
        $this->set('plantilla_detalles', $plantilla_detalles);

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'tipoPedido'));
    }

    function info_cantidad_productos() {
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VCantidadProducto.empresa_id' => array_unique($empresas_permisos), 'VCantidadProducto.sucursal_id' => array_unique($sucursales_permisos), 'VCantidadProducto.pedido_estado_pedido' => array('4', '5'));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /*
          if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muestra solo las sucursales del usuario
          } */

        $this->set('detalles', array());
        $this->VCantidadProducto->set($this->data);
        if (!empty($this->data)) {
            //  print_r($this->data);
            // $conditions = array();
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
                $where = "+VCantidadProducto+.+fecha_aprobado_pedido::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
//$where = "+VCantidadProducto+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
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

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal'), 'conditions' => array('Sucursale.id_empresa' => $this->Session->read('Auth.User.empresa_id')), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
// $regional = $regional . '"' . $value['Sucursale']['regional_sucursal'] . '",';
        }
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.v_regional_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido', 'regional'));
    }

    function info_consolidado_facturado() {
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VConsolidadoFacturado.empresa_id' => array_unique($empresas_permisos), 'VConsolidadoFacturado.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /*
          if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muestra solo las sucursales del usuario
          }
         */


        $this->set('detalles', array());
        $this->VConsolidadoFacturado->set($this->data);
        if (!empty($this->data)) {
//  print_r($this->data);
            //$conditions = array();
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

            if (!empty($this->data['PedidosDetalle']['tipo_categoria_id'])) {
                $where = "+VConsolidadoFacturado+.+tipo_categoria_id+ = " . $this->data['PedidosDetalle']['tipo_categoria_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_id'])) {
                $where = "+VConsolidadoFacturado+.+id+ = " . $this->data['PedidosDetalle']['pedido_id'] . "";
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
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_orden'));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido', 'tipoCategoria'));
        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function info_facturas_compra() {
        $movimientos_data = $this->MovimientosEntrada->find('all', array('conditions' => array('proveedor_id >' => '0'), 'order' => 'MovimientosEntrada.id desc'));
        $movimientos = array();
        foreach ($movimientos_data as $movimiento_data) {
            $movimientos[$movimiento_data['MovimientosEntrada']['id']] = $movimiento_data['MovimientosEntrada']['id'] . ' - ' . $movimiento_data['Proveedore']['nombre_proveedor'] . ' (' . $movimiento_data['Proveedore']['nit_proveedor'] . ') - F.Vencim: ' . $movimiento_data['MovimientosEntrada']['factura_fecha_vencimiento'];
        }
        $this->set('movimientos', $movimientos);

        // VFacturaCompra
        $conditions = array();
        $this->set('detalles', array());
        $this->VFacturaCompra->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['MovimientosEntrada']['id'])) {
                $where = "+VFacturaCompra+.+movimientos_entradas_id+ = '" . $this->data['MovimientosEntrada']['id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['MovimientosEntrada']['desde_id']) && !empty($this->data['MovimientosEntrada']['hasta_id'])) {
                $where = "+VFacturaCompra+.+movimientos_entradas_id+ >= '" . $this->data['MovimientosEntrada']['desde_id'] . "' AND +VFacturaCompra+.+movimientos_entradas_id+ <= '" . $this->data['MovimientosEntrada']['hasta_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            $detalles = $this->VFacturaCompra->find('all', array('conditions' => $conditions));
            $this->set('detalles', $detalles);
            $this->set('id', $this->data['MovimientosEntrada']['id']);
        }
    }

    function info_acumulado_productos() {
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VAcumuladoProducto.empresa_id' => array_unique($empresas_permisos), 'VAcumuladoProducto.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /* if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursal = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursal = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true);
          } */

        $this->set('detalles', array());
        $this->VAcumuladoProducto->set($this->data);
        if (!empty($this->data)) {
//  print_r($this->data);
            // $conditions = array();

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
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id'));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
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

    function info_general_call() {
        $conditions = array();


        if (!empty($this->data)) {
            if (!empty($this->data['ListadoLlamada']['bd_razon_social'])) {
                $where = "+BdCliente+.+bd_razon_social+ ILIKE '%" . $this->data['ListadoLlamada']['bd_razon_social'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['ListadoLlamada']['fecha_inicio'])) {
                $where = "+ListadoLlamadasDetalle+.+fecha_inicio+::date = '" . $this->data['ListadoLlamada']['fecha_inicio'] . "'::date";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
                $fecha = $this->data['ListadoLlamada']['fecha_inicio'];
            }
            if (!empty($this->data['ListadoLlamada']['user_id'])) {
                $where = "+ListadoLlamadasDetalle+.+user_id+ = " . $this->data['ListadoLlamada']['user_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        } else {
            if (!empty($this->data['ListadoLlamada']['fecha_inicio'])) {
                $where = "+ListadoLlamadasDetalle+.+fecha_inicio+::date = '" . $this->data['ListadoLlamada']['fecha_inicio'] . "'::date";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
                $fecha = $this->data['ListadoLlamada']['fecha_inicio'];
            } else {
                $where = "+ListadoLlamadasDetalle+.+fecha_inicio+::date = now()::date";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
                $fecha = date('Y-m-d');
            }
        }
        $this->set('fecha', $fecha);

        $detalle_general = $this->ListadoLlamadasDetalle->find('all', array(
            'fields' => array('ListadoLlamadasDetalle.user_id', 'User.nombres_persona', 'COUNT(*)', 'AVG(fecha_fin - fecha_inicio)', 'SUM(fecha_fin - fecha_inicio)', 'MIN(fecha_inicio)', 'MAX(fecha_fin)'),
            'conditions' => $conditions,
            'group' => 'ListadoLlamadasDetalle.user_id, User.nombres_persona'));
        $this->set('detalle_general', $detalle_general);
        //print_r($detalle_general);

        $detalle_llamada = $this->ListadoLlamadasDetalle->find('all', array('conditions' => $conditions, 'order' => 'fecha_inicio DESC'));
        $this->set('listadoLlamadas', $detalle_llamada);

        $users = $this->User->find('list', array('fields' => 'User.nombres_persona', 'order' => 'User.nombres_persona', 'conditions' => array('User.estado' => true, 'User.rol_id' => '4'))); // array('4','1')
        $this->set(compact('users'));
    }

    function info_pdf_masivo() {
        // Configure::write('debug', 2);
        // ini_set('memory_limit', '1024M');
        ini_set('memory_limit', '2048M');
        $this->Pedido->set($this->data);
        if (!empty($this->data['Pedido'])) {

            $pedidos = array();
            foreach ($this->data['Pedido'] as $key => $value) {
                array_push($pedidos, $value);
            }
            $this->Session->write('Pedido.pdf_masivos', $pedidos);
            $this->redirect(array('controller' => 'pedidos', 'action' => 'pedido_pdf_masivo'));
        }

        if (!empty($this->data['PedidoDespacho'])) {
            $conditions = array('Pedido.pedido_estado_pedido' => array('4', '5'), 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id') );
            if (!empty($this->data['PedidoDespacho']['id_inicial']) && empty($this->data['PedidoDespacho']['id_final'])) {
                $where = "+Pedido+.+id+ = " . $this->data['PedidoDespacho']['id_inicial'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['id_inicial']) && !empty($this->data['PedidoDespacho']['id_final'])) {
                if ($this->data['PedidoDespacho']['id_final'] > $this->data['PedidoDespacho']['id_inicial']) {
                    $where = "+Pedido+.+id+ BETWEEN +'" . $this->data['PedidoDespacho']['id_inicial'] . "'+  AND +'" . $this->data['PedidoDespacho']['id_final'] . "'+";
                    $where = str_replace('+', '"', $where);
                    array_push($conditions, $where);
                } else {
                    $this->Session->setFlash(__('El No. de Orden Final debe ser mayor al No. de Orden inicial.', true));
                }
            }
            if (!empty($this->data['PedidoDespacho']['fecha_aprobado_pedido'])) {
                $where = "+Pedido+.+fecha_aprobado_pedido+::date = '" . $this->data['PedidoDespacho']['fecha_aprobado_pedido'] . "'";
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
            if (!empty($this->data['PedidoDespacho']['id_ordenes'])) {
                $where = "+Pedido+.+id+ IN (" . $this->data['PedidoDespacho']['id_ordenes'] . ")";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidoDespacho']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = " . $this->data['PedidoDespacho']['tipo_pedido_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        } else {
            $conditions = array('Pedido.pedido_estado_pedido' => '4', 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'));
        }

        $pedidos = $this->Pedido->find('all', array('conditions' => $conditions, 'limit' => 2000, 'order' => array(
                'Pedido.id' => 'desc'
        )));
        $this->set('pedidos', $pedidos);

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

    function info_cantidades_ep() {

        ini_set('memory_limit', '256M');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VInformeCantidadesEp.empresa_id' => array_unique($empresas_permisos), 'VInformeCantidadesEp.sucursal_id' => array_unique($sucursales_permisos));
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        $this->set('detalles_ep', array());

        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            // $conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VInformeCantidadesEp+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['pedido_id']) > 0) {
                $where = "+VInformeCantidadesEp+.+pedido_entrega_parcial+ = '" . $this->data['PedidosDetalle']['pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['id']) > 0) {
                $where = "+VInformeCantidadesEp+.+id+ = '" . $this->data['PedidosDetalle']['id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VInformeCantidadesEp+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+VInformeCantidadesEp+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VInformeCantidadesEp+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
//            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
//                $where = "+VInformeCantidadesEp+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
//                $where = str_replace('+', '"', $where);
//                array_push($conditions, $where);
//            }
//
//            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
//                $where = "+VInformeCantidadesEp+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
//                $where = str_replace('+', '"', $where);
//                array_push($conditions, $where);
//            }
            $detalles_ep = $this->VInformeCantidadesEp->find('all', array('conditions' => $conditions));

            $this->set('detalles_ep', $detalles_ep);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array_unique($conditions_empresa)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        // $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('id' => array('4', '5'))));
        // $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales'/* , 'estados', 'tipoPedido' */));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function info_detallado_parciales() {
        $detalles_ep = $this->VInformeCantidadesEp->find('all', array('conditions' => array()));
        $this->set('detalles_ep', array());
        /*

          SELECT pedidos.id,
          pedidos.pedido_fecha,
          pedidos.fecha_aprobado_pedido,
          pedidos.fecha_despacho,
          pedidos.pedido_estado_pedido,
          pedidos.tipo_pedido_id,

          pedidos2.id as pedido_entrega_parcial,
          pedidos2.pedido_fecha as pedido_fecha2,
          pedidos2.fecha_aprobado_pedido as fecha_aprobado_pedido2,
          pedidos2.pedido_estado_pedido as pedido_estado_pedido2,
          pedidos2.tipo_pedido_id as tipo_pedido_id2,

          empresas.id AS empresa_id,
          empresas.nombre_empresa,

          sucursales.id AS sucursal_id,
          sucursales.nombre_sucursal,
          sucursales.regional_sucursal,
          sucursales.ceco_sucursal,
          sucursales.oi_sucursal,

          pedidos_detalles.cantidad_pedido + pedidos_detalles.cantidad_pedido_parcial as solicitada,
          pedidos_detalles.cantidad_pedido as entregada,
          pedidos_detalles.cantidad_pedido_parcial as faltante,

          productos.id as producto_id,
          productos.codigo_producto,
          productos.nombre_producto
          FROM pedidos-- , productos, empresas, sucursales
          JOIN pedidos as pedidos2
          ON pedidos.id = pedidos2.pedido_id
          JOIN pedidos_detalles
          ON pedidos.id = pedidos_detalles.pedido_id
          JOIN productos
          ON pedidos_detalles.producto_id = productos.id
          JOIN empresas
          ON pedidos.empresa_id = empresas.id
          JOIN sucursales
          ON  pedidos.sucursal_id = sucursales.id
          WHERE pedidos_detalles.pedido_entrega_parcial = true AND
          pedidos2.pedido_estado_pedido = 4 AND
          pedidos.pedido_estado_pedido = 5
          ORDER BY pedidos.id;
         */
        ini_set('memory_limit', '256M');
        //31052018
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('VInformeDetallado.empresa_id' => array_unique($empresas_permisos), 'VInformeDetallado.sucursal_id' => array_unique($sucursales_permisos), 'VInformeDetallado.pedido_id >' => '0');
        $conditions_empresa = array('id' => $empresas_permisos);
        $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true);
        //31052018 
        /* if ($this->Session->read('Auth.User.rol_id') == '1') {
          $conditions_empresa = array(); // Muestra todas las empresas
          $conditions_sucursales = array('Sucursale.estado_sucursal' => true);
          } else {
          $conditions_empresa = array('Empresa.id' => $this->Session->read('Auth.User.empresa_id')); // Muestra solo la empresa del usuario
          $conditions_sucursales = array('id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.estado_sucursal' => true); // Muetra solo las sucursales del usuario
          } */

        $this->set('detalles', array());

        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            // $conditions = array('VInformeDetallado.pedido_id >' => '0');
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
            if (($this->data['PedidosDetalle']['pedido_id']) > 0) {
                $where = "+VInformeDetallado+.+pedido_id+ = '" . $this->data['PedidosDetalle']['pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['id']) > 0) {
                $where = "+VInformeDetallado+.+id+ = '" . $this->data['PedidosDetalle']['id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
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
                // $where = "+VInformeDetallado+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = "+VInformeDetallado+.+fecha_aprobado_pedido::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
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
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('id' => array('4', '5'))));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));

        $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
        $regional = array();
        foreach ($regional_data as $value) {
            $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
        }
        $this->set('regional', $regional);
    }

    function info_pedidos_estados() {

        $sql_informe1 = "
          SELECT pedido_estado_pedido, user_id,  ( SELECT array_to_string(ARRAY( SELECT '000'::text || p2.id
          FROM pedidos p2
          WHERE p2.pedido_estado_pedido::text = pedidos.pedido_estado_pedido::text
          AND p2.user_id::text = pedidos.user_id::text
          ORDER BY p2.id), ', '::text) AS array_to_string) AS pedidos_id 
	  FROM pedidos
          group by pedido_estado_pedido, user_id";
        $datas1 = $this->Pedido->query($sql_informe1);
        $this->set('datas1', $datas1);


        $sql_informe = "SELECT tb.user_id , 
            tb.username,
            tb.nombres_persona, 
            SUM(tb.en_proceso) as en_proceso,
            SUM(tb.en_cancelado) as en_cancelado,
            SUM(tb.en_pendiente) as en_pendiente,
            SUM(tb.en_aprobado) as en_aprobado,
            SUM(tb.en_despachado) as en_despachado,
            SUM(tb.en_entregado) as en_entregado
        FROM (
        SELECT  
            case when pedido_estado_pedido = 1 then count(pedido_estado_pedido) else 0 end as en_proceso,
            case when pedido_estado_pedido = 2 then count(pedido_estado_pedido) else 0 end as en_cancelado,
            case when pedido_estado_pedido = 3 then count(pedido_estado_pedido) else 0 end as en_pendiente,
            case when pedido_estado_pedido = 4 then count(pedido_estado_pedido) else 0 end as en_aprobado,
            case when pedido_estado_pedido = 5 then count(pedido_estado_pedido) else 0 end as en_despachado,
            case when pedido_estado_pedido = 6 then count(pedido_estado_pedido) else 0 end as en_entregado,  
            users.username,
            users.nombres_persona,
            pedidos.user_id 
        FROM pedidos, users
        WHERE pedidos.user_id = users.id
        GROUP BY pedido_estado_pedido, nombres_persona, pedidos.user_id, users.username ) tb
        GROUP BY tb.nombres_persona, tb.user_id, tb.username
        ORDER BY tb.user_id";
        $datas = $this->Pedido->query($sql_informe);
        $this->set('datas', $datas);

        /*
          $pedidos_cantidades = $this->Pedido->find('all', array(
          'fields' => 'count(Pedido.pedido_estado_pedido), EstadoPedido.nombre_estado, TipoPedido.nombre_tipo_pedido, User.nombres_persona',
          'group' => 'EstadoPedido.id, EstadoPedido.nombre_estado, TipoPedido.nombre_tipo_pedido, User.nombres_persona',
          'order' => 'EstadoPedido.id'));
          $pedidos = $this->Pedido->find('all', array('fields' => 'Pedido.id, Pedido.pedido_fecha, EstadoPedido.nombre_estado, Empresa.nombre_empresa, Sucursale.nombre_sucursal, TipoPedido.nombre_tipo_pedido, User.nombres_persona', 'limit' => '500'));
          $this->set('pedidos', $pedidos);
          $this->set('pedidos_cantidades', $pedidos_cantidades); */
    }

    /*
      Crear menu
      Quitar filtro de fecha en conditions
     */

    function info_estado_pedido() {
        ini_set('memory_limit', '2048M');
        // Configure::write('debug', 2);
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        $sucursales_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
            array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
        }
        $conditions = array('Pedido.empresa_id' => array_unique($empresas_permisos), 'Pedido.sucursal_id' => array_unique($sucursales_permisos), 'Pedido.pedido_estado_pedido' => array('1', '3', '4', '5', '6'));
        $conditions_empresa = array('id' => array_unique($empresas_permisos));
        $conditions_sucursales = array('Sucursale.id' => array_unique($sucursales_permisos), 'Sucursale.estado_sucursal' => true);


        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            // $conditions = array();
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+Pedido+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
//            if (($this->data['PedidosDetalle']['pedido_id']) > 0) {
//                $where = "+Pedido+.+pedido_entrega_parcial+ = '" . $this->data['PedidosDetalle']['pedido_id'] . "'";
//                $where = str_replace('+', '"', $where);
//                array_push($conditions, $where);
//            }
//            if (($this->data['PedidosDetalle']['id']) > 0) {
//                $where = "+Pedido+.+id+ = '" . $this->data['PedidosDetalle']['id'] . "'";
//                $where = str_replace('+', '"', $where);
//                array_push($conditions, $where);
//            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+Pedido+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['regional_sucursal'])) {
                $where = "+Pedido+.+regional_sucursal+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                // $where = "+Pedido+.+pedido_fecha+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = "+Pedido+.+fecha_aprobado_pedido::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                $where = "+Pedido+.+pedido_estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+Pedido+.+tipo_pedido_id+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            $pedidos = $this->Pedido->find('all', array('fields' => 'DISTINCT Pedido.id,Sucursale.nombre_sucursal,Sucursale.regional_sucursal,Municipio2.nombre_municipio,Pedido.fecha_despacho,Pedido.fecha_entregado,Pedido.guia_despacho,EstadoPedido.nombre_estado,Pedido.fecha_entrega_1,Pedido.fecha_entrega_2', 'conditions' => $conditions));
            $this->set('pedidos', $pedidos);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal', 'conditions' => $conditions_sucursales));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('id' => array('1', '3', '4', '5', '6'))));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
    }

    function info_sap() {
        ini_set('memory_limit', '2048M');
//         Configure::write('debug', 2);
        $cabeceras = array();
        $conditions = array();
        $this->PedidosDetalle->set($this->data);
        if (!empty($this->data)) {
            if (($this->data['PedidosDetalle']['empresa_id']) > 0) {
                $where = "+VPedidoCabeceraSap+.+empresa_id+ = '" . $this->data['PedidosDetalle']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['pedido_fecha_inicio']) && !empty($this->data['PedidosDetalle']['pedido_fecha_corte'])) {
                $where = "+VPedidoCabeceraSap+.+docdate::date+ BETWEEN +'" . $this->data['PedidosDetalle']['pedido_fecha_inicio'] . "'+  AND +'" . $this->data['PedidosDetalle']['pedido_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['sucursal_id']) > 0) {
                $where = "+VPedidoCabeceraSap+.+sucursal_id+ = '" . $this->data['PedidosDetalle']['sucursal_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['regional_sucursal']) > 0) {
                $where = "+VPedidoCabeceraSap+.+regional_id+ = '" . $this->data['PedidosDetalle']['regional_sucursal'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (($this->data['PedidosDetalle']['pedido_estado_pedido']) > 0) {
                $where = "+VPedidoCabeceraSap+.+estado_pedido+ = '" . $this->data['PedidosDetalle']['pedido_estado_pedido'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['PedidosDetalle']['tipo_pedido_id'])) {
                $where = "+VPedidoCabeceraSap+.+tipo_pedido+ = '" . $this->data['PedidosDetalle']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $cabeceras = $this->VPedidoCabeceraSap->find('all', array('conditions' => $conditions));
            $detalles = array();
            if (count($cabeceras)) {
                $pedidos = array();

                /*
                  $sql_actualizar_totales = "ALTER TABLE pedidos DISABLE TRIGGER movimientos_salida_productos;
                  SELECT actualizar_totales_pedidos(" . $value . ");
                  ALTER TABLE pedidos ENABLE TRIGGER movimientos_salida_productos;";
                  $this->Pedido->query($sql_actualizar_totales);
                 */

//                $disable = "ALTER TABLE pedidos DISABLE TRIGGER movimientos_salida_productos;";
//                $this->Pedido->query($disable);

                foreach ($cabeceras as $cabecera) :
                    array_push($pedidos, $cabecera['VPedidoCabeceraSap']['pedido_id']);
//                    // Actualizar los valores del pedido
//                    $sql_actualizar_totales = "SELECT actualizar_totales_pedidos(" .  $cabecera['VPedidoCabeceraSap']['pedido_id'] . ");";
//                    $this->Pedido->query($sql_actualizar_totales);
                endforeach;

//                $enable = "ALTER TABLE pedidos ENABLE TRIGGER movimientos_salida_productos;";
//                $this->Pedido->query($enable);

                $conditions_detalles = array('VPedidoDetallesSap.pedido_id' => $pedidos);
                $detalles = $this->VPedidoDetallesSap->find('all', array('conditions' => $conditions_detalles));
            }
            $this->set('cabeceras', $cabeceras);
            $this->set('detalles', $detalles);
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa'));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.nombre_sucursal', 'order' => 'Sucursale.nombre_sucursal'));
        $estados = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.id', 'conditions' => array('id' => array('1', '3', '4', '5', '6'))));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('empresas', 'sucursales', 'estados', 'tipoPedido'));
    }

    function info_solicitudes() {
        ini_set('memory_limit', '2048M');
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
        }
        $conditions_empresa = array('id' => $empresas_permisos);

        if ($this->Session->read('Auth.User.rol_id') == 1) {
            $conditions = array();
        } else {
            // $conditions = array('Solicitud.user_id' => $this->Session->read('Auth.User.id'));
            $conditions = array(
                'OR' => array(
                    array('Solicitud.user_id' => $this->Session->read('Auth.User.id')),
                    array('Solicitud.user_id_asignado' => $this->Session->read('Auth.User.id')),
                )
            );
        }
        $this->set('solicitudes', array());
        $this->set('detalles', array());
        $this->Solicitud->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Solicitude']['tipo_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_solicitud_id+ = " . $this->data['Solicitude']['tipo_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['tipo_estado_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_estado_solicitud_id+ = " . $this->data['Solicitude']['tipo_estado_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['tipo_motivo_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_motivo_solicitud_id+ = " . $this->data['Solicitude']['tipo_motivo_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['empresa_id'])) {
                $where = "+Solicitud+.+empresa_id+ = " . $this->data['Solicitude']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['solicitud_fecha_inicio']) && !empty($this->data['Solicitude']['solicitud_fecha_corte'])) {
                $where = "+Solicitud+.+fecha_registro::date+ BETWEEN +'" . $this->data['Solicitude']['solicitud_fecha_inicio'] . "'+  AND +'" . $this->data['Solicitude']['solicitud_fecha_corte'] . "'+";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }

        $solicitudes = $this->Solicitud->find('all', array('conditions' => $conditions, 'order' => 'Solicitud.id DESC'));
        $this->set('solicitudes', $solicitudes);

        $empresa = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $tipoSolicitud = $this->TipoSolicitude->find('list', array('fields' => 'TipoSolicitude.nombre_tipo_solicitud', 'order' => 'TipoSolicitude.nombre_tipo_solicitud', 'conditions' => array('estado' => true)));
        $tipoMotivo = $this->TipoMotivosSolicitud->find('list', array('fields' => 'TipoMotivosSolicitud.nombre_tipo_motivo', 'conditions' => array('estado' => true), 'order' => 'TipoMotivosSolicitud.nombre_tipo_motivo'));
        $tipoEstadoSolicitud = $this->TipoEstadoSolicitud->find('list', array('fields' => 'TipoEstadoSolicitud.nombre_estado_solicitud', 'order' => 'TipoEstadoSolicitud.orden', 'conditions' => array('estado' => true)));
        $this->set(compact('tipoSolicitud', 'tipoEstadoSolicitud', 'tipoMotivo', 'empresa'));
    }

}

?>