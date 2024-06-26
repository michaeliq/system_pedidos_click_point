<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
  ALTER TABLE public.pedidos   ADD COLUMN pedido_masivo integer;
  ALTER TABLE public.pedidos_masivos  ADD COLUMN pedido_masivo integer;
 */

class MasivosController extends AppController {

    var $name = "Masivos";
    var $uses = array('Pedido', 'TipoPedido', 'Cronograma', 'TipoMovimiento', 'Empresa', 'EmpresasAprobadore');
    var $components = array('RequestHandler', 'Auth', 'Permisos');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Masivos', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Masivos', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function despachos() {
        // ini_set('memory_limit', '4096M');
        date_default_timezone_set('America/Bogota');

        if (!empty($this->data)) {
            $despacho_masivo = rand(10000, 999999);

            // Configuración de la carga
            $dir_file = 'pedidos/masivos_despachos/';
            $max_file = 20145728; // 20,14 MB = 20145728 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Masivo']['archivo_csv']['name'])) {
                // echo "name<br>";
                // Verificar si el archivo tiene formato .csv

                if (($this->data['Masivo']['archivo_csv']['type'] == 'text/csv') || ($this->data['Masivo']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    // echo "excel<br>";
                    // Verificar el tamaño del archivo
                    if ($this->data['Masivo']['archivo_csv']['size'] < $max_file) {
                        // echo "tamaño<br>";
                        move_uploaded_file($this->data['Masivo']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Masivo']['archivo_csv']['name']);
                        // $aux = explode('.', $this->data['Masivo']['archivo_csv']['name']);
                        // rename($dir_file . $this->data['Masivo']['archivo_csv']['name'], $dir_file . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Masivo']['archivo_csv'] = $this->data['Masivo']['archivo_csv']['name'];

                        // Vaciar la tabla de pedidos masivos despachos
                        $sql_truncate = "TRUNCATE TABLE pedidos_masivos_despachos;";
                        $this->Pedido->query($sql_truncate);

                        $row = 0;
                        if (($handle = fopen($dir_file . $this->data['Masivo']['archivo_csv'], "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $num = count($data);
                                // echo "<p> $num fields in line $row: <br /></p>\n";
                                $row++;
                                $data_bd = null;
                                for ($c = 0; $c < $num; $c++) {
                                    // echo $data[$c] . "<br />\n";
                                    $data_bd = $data_bd . $data[$c];
                                }
                                // echo $data_bd . "<br />\n";
                                $array_datos = explode(';', $data_bd);
                                if (count($array_datos) > 1 && $row > 1) {
                                    $pedido_id = $array_datos[0];
                                    $guia_pedido = $array_datos[1];
                                    $transportadora = $array_datos[2];
                                    $fecha_despacho = date('Y-m-d H:i:s');

                                    $sql_cargas = "INSERT INTO pedidos_masivos_despachos (pedido_id, guia_pedido, fecha_despacho, estado, pedido_masivo, transportadora) VALUES ('" . $pedido_id . "', '" . $guia_pedido . "', '" . $fecha_despacho . "', true, '" . $despacho_masivo . "','" . $transportadora . "');";
                                    $this->Pedido->query($sql_cargas);
                                }
                            }
                            fclose($handle);
                        }
                        // Consultar si hay registros en la carga masiva
                        $sql_masivos = "SELECT * FROM pedidos_masivos_despachos WHERE estado = true AND pedido_masivo = " . $despacho_masivo . ";";
                        $cantidad = $this->Pedido->query($sql_masivos);

                        if (count($cantidad) > 0) {
                            // Validar data
                            $sql_pedidos_errores = "SELECT masivos_validacion_despachos();";
                            $this->Pedido->query($sql_pedidos_errores);

                            // Consultar si todos los registros estan correctos para realizar la carga masiva
                            $sql_pedidos_correctos = "SELECT * FROM pedidos_masivos_despachos WHERE estado = false AND pedido_masivo =" . $despacho_masivo . ";";
                            $errores = $this->Pedido->query($sql_pedidos_correctos);

                            $this->set('errores', $errores);
                            $this->Session->setFlash(__('El archivo contiene ' . count($errores) . ' errores.', true));

                            // Actualizar los datos masivos cambiando al estado despachado, acá se ejecuta un trigger que libera presupuesto
                            $sql_pedidos_despachos = "UPDATE pedidos SET pedido_estado = TRUE, pedido_estado_pedido = 5, transportadora = pedidos_masivos_despachos.transportadora, guia_despacho = pedidos_masivos_despachos.guia_pedido, fecha_despacho = pedidos_masivos_despachos.fecha_despacho FROM pedidos_masivos_despachos WHERE pedidos.id = pedidos_masivos_despachos.pedido_id::integer AND pedidos_masivos_despachos.estado = true AND pedidos_masivos_despachos.pedido_masivo = " . $despacho_masivo . ";";
                            $this->Pedido->query($sql_pedidos_despachos);

                            $sql_pedidos_actualizados = "SELECT pedido_id,guia_pedido, estado, error_generado, transportadora FROM pedidos_masivos_despachos WHERE pedido_masivo = " . $despacho_masivo . " GROUP BY pedido_id,guia_pedido, estado, error_generado, transportadora  ORDER BY pedido_id;";
                            $pedidos_actualizados = $this->Pedido->query($sql_pedidos_actualizados);
                            $this->set('pedidos_actualizados', $pedidos_actualizados);

                            $this->Session->setFlash(__('Las ordenes de pedido se actulizaron exitosamente.', true));
                        }
                    } else {
                        $this->Session->setFlash(__('El tamaño del archivo supera las 20 MB. Verifique el tamaño del archivo.', true));
                    }
                } else {
                    $this->Session->setFlash(__('El archivo NO es del tipo CSV. Verifique el tipo del archivo y realice nuevamente el proceso.', true));
                }
            } else {
                $this->Session->setFlash(__('No se selecciono un archivo para cargar. Seleccione un archivo y realice nuevamente el proceso.', true));
            }
        }
    }

    function index() {
        //  ini_set('memory_limit', '4096M');
        date_default_timezone_set('America/Bogota');
        $this->set('errores', array());
        $this->set('pedidos_creados', array());

        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
        }

        $conditions_empresa = array('id' => array_unique($empresas_permisos), 'Empresa.estado_empresa' => true);

        // Consultar si tiene pedidos pendientes de aprobaci�n para cargar
        // $pendiente_aprobacion = $this->Pedido->find('count', array('fields' => 'count(Pedido.id)', 'conditions' => array('Pedido.observaciones' => 'Masivo', 'Pedido.pedido_estado_pedido' => '3', 'Pedido.empresa_id' => $this->Session->read('Auth.User.empresa_id'))));
        $pendiente_aprobacion2 = "SELECT count(Pedido.id) FROM pedidos as Pedido WHERE Pedido.observaciones = 'Masivo' AND Pedido.pedido_estado_pedido = 3 AND Pedido.empresa_id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $cantidad = $this->Pedido->query($pendiente_aprobacion2);
        // print_r($cantidad);
        $pendiente_aprobacion = $cantidad[0][0]['count'];
        $pendiente_aprobacion = 0;
        // print_r($pendiente_aprobacion);
        // $a = array('Pedido.observaciones' => 'Masivo', 'Pedido.pedido_estado_pedido' => '3', 'Pedido.empresa_id' => $this->Session->read('Auth.User.empresa_id'));
        // print_r($a);

        if ($pendiente_aprobacion > 0) {
            $this->Session->setFlash(__('Tiene ' . $pendiente_aprobacion . ' ordenes cargadas masivamente en estado: pendientes de aprobacion.<br> Debe aprobar estas ordenes primero para luego realizar una nueva carga masiva.', true));
        }
        $this->set('pendiente_aprobacion', $pendiente_aprobacion);

        // Consultar cronogramas
        $cronograma = $this->Cronograma->find('list', array('fields' => 'tipo_pedido_id', 'conditions' =>
            array('Cronograma.empresa_id' => $this->Session->read('Auth.User.empresa_id'),
                'Cronograma.estado_cronograma' => true)));

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.id' => $cronograma, 'TipoPedido.estado' => true)));
        $empresa = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $this->set(compact('tipo_pedido', 'empresa'));

        if (!empty($this->data)) {
            $pedido_masivo = rand(10000, 999999);

            if (!empty($this->data['Masivo']['tipo_pedido_id'])) {
                // Configuración de la carga
                $dir_file = 'pedidos/masivos/';
                $max_file = 20145728; // 20,14 MB = 20145728 byte
                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Masivo']['archivo_csv']['name'])) {
                    // echo "name<br>";
                    // Verificar si el archivo tiene formato .csv
                    if ($this->data['Masivo']['archivo_csv']['type'] == 'text/csv' || $this->data['Masivo']['archivo_csv']['type'] == 'application/vnd.ms-excel') {
                        // echo "excel<br>";
                        // Verificar el tamaño del archivo
                        if ($this->data['Masivo']['archivo_csv']['size'] < $max_file) {
                            // echo "tamaño<br>";
                            move_uploaded_file($this->data['Masivo']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Masivo']['archivo_csv']['name']);
                            // $aux = explode('.', $this->data['Masivo']['archivo_csv']['name']);
                            // rename($dir_file . $this->data['Masivo']['archivo_csv']['name'], $dir_file . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Masivo']['archivo_csv'] = $this->data['Masivo']['archivo_csv']['name'];

                            // Vaciar la tabla de pedidos masivos
                            $sql_truncate = "TRUNCATE TABLE pedidos_masivos;";
                            $this->Pedido->query($sql_truncate);

                            $row = 0;
                            if (($handle = fopen($dir_file . $this->data['Masivo']['archivo_csv'], "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    $num = count($data);
                                    // echo "<p> $num fields in line $row: <br /></p>\n";
                                    $row++;
                                    $data_bd = null;
                                    for ($c = 0; $c < $num; $c++) {
                                        // echo $data[$c] . "<br />\n";
                                        $data_bd = $data_bd . $data[$c];
                                    }
                                    // echo $data_bd . "<br />\n";
                                    $array_datos = explode(';', $data_bd);
                                    if (count($array_datos) > 1 && $row > 1) {
                                        $nombre_empresa = $array_datos[0];
                                        $nombre_sucursal = null; //$array_datos[3];
                                        $oi_sucursal = $array_datos[1];
                                        $ceco_sucursal = $array_datos[2];
                                        $codigo_producto = $array_datos[4];
                                        $nombre_producto = null; // $array_datos[5];
                                        $cantidad_pedido = is_int($array_datos[6]) ? '0' : $array_datos[6];
                                        $fecha_pedido_masivo = date('Y-m-d H:i:s');
                                        $pedido_id = '0';
                                        $tipo_pedido_id = $this->data['Masivo']['tipo_pedido_id'];
                                        $fecha_entrega_1 = $this->data['Masivo']['fecha_entrega_1'];
                                        $fecha_entrega_2 = $this->data['Masivo']['fecha_entrega_2'];
                                        $empresa_id = $this->data['Masivo']['empresa_id'];
                                        $mes_pedido = $this->data['Masivo']['mes_pedido'];
                                        $clasificacion_pedido = $this->data['Masivo']['clasificacion_pedido'];
                                        $cadena_masivo = null; //$datos[$c];
                                        $observacion_producto = empty($array_datos[7]) ? '' : $array_datos[7];
                                        $sql_cargas = "INSERT INTO pedidos_masivos (empresa_id, mes_pedido, clasificacion_pedido, nombre_empresa, nombre_sucursal, oi_sucursal, ceco_sucursal, codigo_producto, nombre_producto, cantidad_pedido, fecha_pedido_masivo, pedido_id, tipo_pedido_id, cadena_masivo, pedido_masivo, fecha_entrega_1, fecha_entrega_2) VALUES ('" . $empresa_id . "', '" . $mes_pedido . "', '" . $clasificacion_pedido . "', '" . $nombre_empresa . "','" . $nombre_sucursal . "','" . $oi_sucursal . "','" . $ceco_sucursal . "','" . $codigo_producto . "','" . $nombre_producto . "','" . $cantidad_pedido . "','" . $fecha_pedido_masivo . "','" . $pedido_id . "','" . $tipo_pedido_id . "','" . $observacion_producto . "'," . $pedido_masivo . ",'" . $fecha_entrega_1 . "','" . $fecha_entrega_2 . "');";
                                        $this->Pedido->query($sql_cargas);
                                    }
                                }
                                fclose($handle);
                            }
                            // Consultar si hay registros en la carga masiva
                            $sql_masivos = "SELECT * FROM pedidos_masivos WHERE pedido_estado = true AND pedido_masivo = " . $pedido_masivo . ";";
                            $cantidad = $this->Pedido->query($sql_masivos);

                            /*

                              $fila = 1;
                              if (($gestor = fopen($dir_file . $this->data['Masivo']['archivo_csv'], "r")) !== FALSE) {
                              echo "openfile<br>";
                              while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                              $numero = count($datos);

                              if ($numero > 1) {
                              $datos[0] = $datos[0] . $datos[1];
                              $datos[1] = null;
                              unset($datos[1]);
                              }

                              // $datos = str_replace(',',';',$datos);                                      print_r($datos);

                              // echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                              $fila++;
                              for ($c = 0; $c < $numero; $c++) {
                              if ($fila > 2) {
                              $array_datos = explode(';', $datos[$c]);
                              if (count($array_datos) > 1) {
                              $nombre_empresa = $array_datos[0];
                              $nombre_sucursal = null; //$array_datos[3];
                              $oi_sucursal = $array_datos[1];
                              $ceco_sucursal = $array_datos[2];
                              $codigo_producto = $array_datos[4];
                              $nombre_producto = null; // $array_datos[5];
                              $cantidad_pedido = is_int($array_datos[6]) ? '0' : $array_datos[6];
                              $fecha_pedido_masivo = 'now()';
                              $pedido_id = '0';
                              $tipo_pedido_id = $this->data['Masivo']['tipo_pedido_id'];
                              $cadena_masivo = null; //$datos[$c];

                              $sql_cargas = "INSERT INTO pedidos_masivos (nombre_empresa, nombre_sucursal, oi_sucursal, ceco_sucursal, codigo_producto, nombre_producto, cantidad_pedido, fecha_pedido_masivo, pedido_id, tipo_pedido_id, cadena_masivo) VALUES ('" . $nombre_empresa . "','" . $nombre_sucursal . "','" . $oi_sucursal . "','" . $ceco_sucursal . "','" . $codigo_producto . "','" . $nombre_producto . "','" . $cantidad_pedido . "','" . $fecha_pedido_masivo . "','" . $pedido_id . "','" . $tipo_pedido_id . "','" . $cadena_masivo . "');";
                              $this->Pedido->query($sql_cargas);

                              // echo $datos[$c] . "<br />\n";
                              // print_r(explode(';', $datos[$c]));
                              }
                              }
                              }
                              }
                              echo "archivo cargado";
                              exit; */
                            if (count($cantidad) > 0) {

                                // Actualizar productos
                                /*
                                  $sql_pedidos_productos = "UPDATE pedidos_masivos SET producto_id = productos.id  FROM productos WHERE pedidos_masivos.codigo_producto = productos.codigo_producto;";
                                  $this->Pedido->query($sql_pedidos_productos);

                                  // Actualizar sucursales
                                  $sql_pedidos_sucursales = "UPDATE pedidos_masivos SET nombre_sucursal = sucursales.nombre_sucursal, sucursal_id = sucursales.id FROM sucursales WHERE sucursales.oi_sucursal = pedidos_masivos.oi_sucursal AND sucursales.estado_sucursal=TRUE;";
                                  $this->Pedido->query($sql_pedidos_sucursales);

                                  // Actualizar plantilla que se debe actualizar los productos
                                  $sql_pedidos_plantillas = "UPDATE pedidos_masivos SET plantilla_id = sucursales_plantillas.plantilla_id FROM sucursales_plantillas WHERE sucursales_plantillas.sucursale_id = pedidos_masivos.sucursal_id AND sucursales_plantillas.tipo_pedido_id = pedidos_masivos.tipo_pedido_id;";
                                  $this->Pedido->query($sql_pedidos_plantillas);

                                  // Actualizar precios indicados en la plantillas
                                  $sql_precios_plantillas = "UPDATE pedidos_masivos SET tipo_categoria_id = plantillas_detalles.tipo_categoria_id,
                                  precio_producto = plantillas_detalles.precio_producto,
                                  iva_producto = plantillas_detalles.iva_producto,
                                  medida_producto = plantillas_detalles.medida_producto
                                  FROM plantillas_detalles
                                  WHERE plantillas_detalles.plantilla_id = pedidos_masivos.plantilla_id
                                  AND plantillas_detalles.producto_id = pedidos_masivos.producto_id;";
                                  $this->Pedido->query($sql_precios_plantillas);

                                  // Marcar los registros con errores
                                  $sql_pedidos_errores = "UPDATE pedidos_masivos SET pedido_estado = false, oi_sucursal=null, error_generado = concat(error_generado,'Error en el dato OI_SUCURSAL del archivo<br>') WHERE oi_sucursal = '';
                                  UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'La sucursal con c�digo '||oi_sucursal||' no se encuentra registrada.<br>') WHERE char_length(nombre_sucursal) < 3;
                                  UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'El producto con c�digo '||codigo_producto||' no se encuentra registrado.<br>') WHERE producto_id = 0;
                                  UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'La cantidad del produto con c�digo '||codigo_producto||' es igual a 0.<br>') WHERE cantidad_pedido = 0;
                                  UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'El producto '||codigo_producto||' no se encuentra en la plantilla relacionada a la sucursal '||nombre_sucursal||'.<br>') WHERE plantilla_id is null OR producto_id = 0 OR tipo_categoria_id = 0;
                                  SELECT masivos_validacion_productos_sucursal();";
                                  $this->Pedido->query($sql_pedidos_errores); */
                                $sql_pedidos_errores = "SELECT masivos_validacion_productos_sucursal();";
                                $this->Pedido->query($sql_pedidos_errores);

                                // Validar presupuestos
                                $sql_presupuestos = "SELECT
                                (SELECT presupuesto_asignado FROM sucursales_presupuestos_pedidos WHERE tipo_pedido_id = pedidos_masivos.tipo_pedido_id AND sucursal_id = pedidos_masivos.sucursal_id) ,
                                (SELECT presupuesto_utilizado FROM sucursales_presupuestos_pedidos WHERE tipo_pedido_id = pedidos_masivos.tipo_pedido_id AND sucursal_id = pedidos_masivos.sucursal_id),
                                coalesce(SUM(pedidos_masivos.precio_producto * pedidos_masivos.cantidad_pedido),0) as valor_pedido,
                                (SELECT presupuesto_asignado FROM sucursales_presupuestos_pedidos WHERE tipo_pedido_id = pedidos_masivos.tipo_pedido_id AND sucursal_id = pedidos_masivos.sucursal_id) <
                                (SELECT presupuesto_utilizado + coalesce(SUM(pedidos_masivos.precio_producto * pedidos_masivos.cantidad_pedido),0) FROM sucursales_presupuestos_pedidos WHERE tipo_pedido_id = pedidos_masivos.tipo_pedido_id AND sucursal_id = pedidos_masivos.sucursal_id) as presupuesto,
                                -- coalesce(SUM((precio_producto * iva_producto) * cantidad_pedido),0), 
                                sucursal_id, 
                                tipo_pedido_id,
                                oi_sucursal
                                FROM pedidos_masivos 
                                WHERE pedido_masivo = " . $pedido_masivo . " AND 
                                pedido_estado = true AND plantilla_id > 0 
                                AND producto_id > 0 OR tipo_categoria_id > 0 
                                GROUP BY sucursal_id, tipo_pedido_id, oi_sucursal";
                                $presupuesto = $this->Pedido->query($sql_presupuestos);
                                // print_r($presupuesto);
                                foreach ($presupuesto as $value) {
                                    if ($value['0']['presupuesto']) {
                                        // echo $value['0']['sucursal_id'];
                                        // Marcar los registros con errores
                                        $sql_pedidos_errores = "UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'La sucursal " . $value['0']['oi_sucursal'] . " tiene un presupuesto de " . $value['0']['presupuesto_asignado'] . " y se esta usando un pedido por valor de " . $value['0']['valor_pedido'] . "<br>') WHERE oi_sucursal = '" . $value['0']['oi_sucursal'] . "';";
                                        // echo $sql_pedidos_errores;
                                        $this->Pedido->query($sql_pedidos_errores);
                                    }
                                }
                                // exit;
                               
                                // Consultar si todos los registros estan correctos para realizar la carga masiva
                                $sql_pedidos_correctos = "SELECT DISTINCT error_generado FROM pedidos_masivos WHERE pedido_estado = false AND pedido_masivo =" . $pedido_masivo . ";";
                                $errores = $this->Pedido->query($sql_pedidos_correctos);

                                if (count($errores) > 0) {
                                    $this->set('errores', $errores);
                                    $this->Session->setFlash(__('El archivo contiene ' . count($errores) . ' errores.', true));
                                } else {

                                    // Crear pedidos a partir de los datos cargados
                                    $inventarios_salida = array('IVS01');
                                    $tipoMovimientos = $this->TipoMovimiento->find('all', array('fields' => 'TipoMovimiento.id', 'conditions' => array('TipoMovimiento.codigo_tipo_movimiento' => $inventarios_salida, 'TipoMovimiento.estado_tipo_movimiento' => true, 'TipoMovimiento.tipo_movimiento' => 'S')));

                                    // $sql_pedidos = "INSERT INTO pedidos (empresa_id, sucursal_id, pedido_direccion, pedido_telefono, pedido_oi_masivo, pedido_fecha, pedido_hora, pedido_estado, pedido_estado_pedido, user_id, departamento_id, municipio_id, observaciones, fecha_orden_pedido, tipo_pedido_id, tipo_movimiento_id, pedido_masivo, fecha_entrega_1, fecha_entrega_2) (SELECT id_empresa, id, direccion_sucursal, telefono_sucursal, oi_sucursal as valor_total, now() as pedido_fecha, now() as pedido_hora, true as pedido_estado, '3' as pedido_estado_pedido, '" . $this->Session->read('Auth.User.id') . "' as user_id, departamento_id, municipio_id, 'Masivo' as observaciones, now() fecha_orden_pedido, '" . $tipo_pedido_id . "' as tipo_pedido_id, " . $tipoMovimientos[0]['TipoMovimiento']['id'] . " as tipo_movimiento_id, " . $pedido_masivo . ", '".$fecha_entrega_1."', '".$fecha_entrega_2."' FROM sucursales WHERE estado_sucursal = TRUE AND oi_sucursal IN (SELECT oi_sucursal FROM pedidos_masivos WHERE pedido_masivo = " . $pedido_masivo . " AND pedido_estado = true AND plantilla_id > 0 AND producto_id > 0 OR tipo_categoria_id > 0 GROUP BY oi_sucursal));";
                                    $sql_pedidos = "INSERT INTO pedidos (empresa_id, mes_pedido, clasificacion_pedido, sucursal_id, pedido_direccion, pedido_telefono, pedido_oi_masivo, pedido_fecha, pedido_hora, pedido_estado, pedido_estado_pedido, user_id, departamento_id, municipio_id, observaciones, fecha_orden_pedido, tipo_pedido_id, tipo_movimiento_id, pedido_masivo, fecha_entrega_1, fecha_entrega_2) (SELECT id_empresa, '" . $mes_pedido . "' as mes_pedido, '" . $clasificacion_pedido . "' as clasificacion_pedido,  id, direccion_sucursal, telefono_sucursal, oi_sucursal as valor_total, '" . date('Y-m-d H:i:s') . "' as pedido_fecha, '" . date('Y-m-d H:i:s') . "' as pedido_hora, true as pedido_estado, '3' as pedido_estado_pedido, '" . $this->Session->read('Auth.User.id') . "' as user_id, departamento_id, municipio_id, 'Masivo' as observaciones,'" . date('Y-m-d H:i:s') . "' fecha_orden_pedido, '" . $tipo_pedido_id . "' as tipo_pedido_id, " . $tipoMovimientos[0]['TipoMovimiento']['id'] . " as tipo_movimiento_id, " . $pedido_masivo . ", '" . $fecha_entrega_1 . "', '" . $fecha_entrega_2 . "' FROM sucursales WHERE estado_sucursal = TRUE AND oi_sucursal IN (SELECT oi_sucursal FROM pedidos_masivos WHERE pedido_masivo = " . $pedido_masivo . " AND pedido_estado = true AND plantilla_id > 0 AND producto_id > 0 OR tipo_categoria_id > 0 GROUP BY oi_sucursal));";
                                    $this->Pedido->query($sql_pedidos);

                                    // Actualizar los datos masivos marcandolos con los pedidos creados
                                    //$sql_pedidos_marcar = "UPDATE pedidos_masivos SET pedido_id = pedidos.id FROM pedidos WHERE pedidos.pedido_oi_masivo = pedidos_masivos.oi_sucursal AND pedidos.observaciones = 'Masivo' AND pedidos.pedido_fecha = now()::date AND pedido_estado_pedido = 3 AND pedidos.pedido_masivo = " . $pedido_masivo . ";";
                                    $sql_pedidos_marcar = "UPDATE pedidos_masivos SET pedido_id = pedidos.id FROM pedidos WHERE pedidos.pedido_oi_masivo = pedidos_masivos.oi_sucursal AND pedidos.observaciones = 'Masivo' AND pedidos.pedido_fecha = '" . date('Y-m-d') . "' AND pedido_estado_pedido = 3 AND pedidos.pedido_masivo = " . $pedido_masivo . ";";
                                    $this->Pedido->query($sql_pedidos_marcar);

                                    // Insertar detalles del pedido
                                    // $sql_pedidos_detalles = "INSERT INTO pedidos_detalles (producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, fecha_pedido_detalle, observacion_producto) (SELECT producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, now() fecha_pedido_detalle, cadena_masivo FROM pedidos_masivos WHERE pedido_masivo = " . $pedido_masivo . " AND pedido_id > 0 AND producto_id > 0 AND iva_producto is not null);";
                                    $sql_pedidos_detalles = "ALTER TABLE pedidos_detalles DISABLE TRIGGER sucursales_presupuesto; INSERT INTO pedidos_detalles (producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, fecha_pedido_detalle, observacion_producto) (SELECT producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, '" . date('Y-m-d H:i:s') . "' fecha_pedido_detalle, cadena_masivo FROM pedidos_masivos WHERE pedido_masivo = " . $pedido_masivo . " AND pedido_id > 0 AND producto_id > 0 AND iva_producto is not null); ALTER TABLE pedidos_detalles ENABLE TRIGGER sucursales_presupuesto;";
                                    $this->Pedido->query($sql_pedidos_detalles);

                                    $sql_pedidos_creados = "SELECT DISTINCT pedido_id, nombre_sucursal, SUM(cantidad_pedido) FROM pedidos_masivos WHERE pedido_masivo = " . $pedido_masivo . " GROUP BY pedido_id, nombre_sucursal ORDER BY pedido_id;";
                                    $pedidos_creados = $this->Pedido->query($sql_pedidos_creados);
                                    $this->set('pedidos_creados', $pedidos_creados);

                                    $this->Session->setFlash(__('Las ordenes de pedido se crearon exitosamente.', true));

                                    // Consultar si tiene pedidos pendientes de aprobaci�n para cargar
                                    $pendiente_aprobacion = $this->Pedido->find('count', array('fields' => 'count(Pedido.id)', 'conditions' => array('Pedido.pedido_masivo' => $pedido_masivo, 'Pedido.observaciones' => 'Masivo', 'Pedido.pedido_estado_pedido' => '3', 'Pedido.empresa_id' => $this->Session->read('Auth.User.empresa_id'))));
                                    if ($pendiente_aprobacion > 0) {
                                        $this->Session->setFlash(__('Tiene ' . $pendiente_aprobacion . ' ordenes cargadas masivamente en estado: pendientes de aprobacion.<br> Debe aprobar estas ordenes primero para luego realizar una nueva carga masiva.', true));
                                    }
                                    $this->set('pendiente_aprobacion', $pendiente_aprobacion);
                                }
                                // fclose($gestor);
                            }
                        } else {
                            $this->Session->setFlash(__('El tamaño del archivo supera las 20 MB. Verifique el tamaño del archivo.', true));
                        }
                    } else {
                        $this->Session->setFlash(__('El archivo NO es del tipo CSV. Verifique el tipo del archivo y realice nuevamente el proceso.', true));
                    }
                } else {
                    $this->Session->setFlash(__('No se selecciono un archivo para cargar. Seleccione un archivo y realice nuevamente el proceso.', true));
                }
            } else {
                $this->Session->setFlash(__('No se selecciono un tipo de pedido para cargar. Seleccione un tipo de pedido y realice nuevamente el proceso.', true));
            }
        }
    }

}

?>
