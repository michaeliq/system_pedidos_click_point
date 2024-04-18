<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MasivosController extends AppController {

    var $name = "Masivos";
    var $uses = array('Pedido', 'TipoPedido', 'Cronograma');
    var $components = array('RequestHandler', 'Auth', 'Permisos');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Administracion', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Administracion', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {

        $this->set('errores', array());
        $this->set('pedidos_creados', array());

        // Consultar cronogramas
        $cronograma = $this->Cronograma->find('list', array('fields' => 'tipo_pedido_id', 'conditions' =>
            array('Cronograma.empresa_id' => $this->Session->read('Auth.User.empresa_id'),
                'Cronograma.estado_cronograma' => true)));

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.id' => $cronograma)));
        $this->set(compact('tipo_pedido'));

        if (!empty($this->data)) {
            // print_r($this->data);
            if (!empty($this->data['Masivo']['tipo_pedido_id'])) {
                // Configuración de la carga
                $dir_file = 'pedidos/masivos/';
                $max_file = 20145728; // 20,14 MB = 20145728 byte
                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Masivo']['archivo_csv']['name'])) {
                    // Verificar si el archivo tiene formato .csv
                    if ($this->data['Masivo']['archivo_csv']['type'] == 'text/csv' || $this->data['Masivo']['archivo_csv']['type'] == 'application/vnd.ms-excel') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Masivo']['archivo_csv']['size'] < $max_file) {
                            move_uploaded_file($this->data['Masivo']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Masivo']['archivo_csv']['name']);
                            // $aux = explode('.', $this->data['Masivo']['archivo_csv']['name']);
                            // rename($dir_file . $this->data['Masivo']['archivo_csv']['name'], $dir_file . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Masivo']['archivo_csv'] = $this->data['Masivo']['archivo_csv']['name'];

                            // Vaciar la tabla de pedidos masivos
                            $sql_truncate = "TRUNCATE TABLE pedidos_masivos;";
                            $this->Pedido->query($sql_truncate);

                            $fila = 1;
                            if (($gestor = fopen($dir_file . $this->data['Masivo']['archivo_csv'], "r")) !== FALSE) {
                                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                                    $numero = count($datos);

                                    if ($numero > 1) {
                                        $datos[0] = $datos[0] . $datos[1];
                                        $datos[1] = null;
                                        unset($datos[1]);
                                    }

                                    /* $datos = str_replace(',',';',$datos);
                                      print_r($datos); */

                                    /* echo "<p> $numero de campos en la línea $fila: <br /></p>\n"; */
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
                                /* echo "archivo cargado";
                                  exit; */

                                // Actualizar productos
                                $sql_pedidos_productos = "UPDATE pedidos_masivos SET producto_id = productos.id /*, tipo_categoria_id = productos.tipo_categoria_id, precio_producto = productos.precio_producto, iva_producto = productos.iva_producto, medida_producto = productos.medida_producto, nombre_producto = productos.nombre_producto */ FROM productos WHERE pedidos_masivos.codigo_producto = productos.codigo_producto;";
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
                                $sql_pedidos_errores = "UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'La sucursal con c�digo '||oi_sucursal||' no se encuentra registrada.<br>') WHERE char_length(nombre_sucursal) < 3;
                                            UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'El producto con c�digo '||codigo_producto||' no se encuentra registrado.<br>') WHERE producto_id = 0;
                                            UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'La cantidad del produto con c�digo '||codigo_producto||' es igual a 0.<br>') WHERE cantidad_pedido = 0;
                                            UPDATE pedidos_masivos SET pedido_estado = false, error_generado = concat(error_generado,'El producto '||codigo_producto||' no se encuentra en la plantilla relacionada a la sucursal '||nombre_sucursal||'.<br>') WHERE plantilla_id is null OR producto_id = 0 OR tipo_categoria_id = 0; ";
                                $this->Pedido->query($sql_pedidos_errores);

                                // Consultar si todos los registros estan correctos para realizar la carga masiva
                                $sql_pedidos_correctos = "SELECT * FROM pedidos_masivos WHERE pedido_estado = false;";
                                $errores = $this->Pedido->query($sql_pedidos_correctos);

                                if (count($errores) > 0) {
                                    $this->set('errores', $errores);
                                    $this->Session->setFlash(__('El archivo contiene ' . count($errores) . ' errores.', true));
                                } else {

                                    // Crear pedidos a partir de los datos cargados
                                    $sql_pedidos = "INSERT INTO pedidos (empresa_id, sucursal_id, pedido_direccion, pedido_telefono, pedido_oi_masivo, pedido_fecha, pedido_hora, pedido_estado, pedido_estado_pedido, user_id, departamento_id, municipio_id, observaciones, fecha_orden_pedido, tipo_pedido_id) (SELECT id_empresa, id, direccion_sucursal, telefono_sucursal, oi_sucursal as valor_total, now() as pedido_fecha, now() as pedido_hora, true as pedido_estado, '3' as pedido_estado_pedido, '" . $this->Session->read('Auth.User.id') . "' as user_id, departamento_id, municipio_id, 'Masivo' as observaciones, now() fecha_orden_pedido, '" . $tipo_pedido_id . "' as tipo_pedido_id FROM sucursales WHERE estado_sucursal = TRUE AND oi_sucursal IN (SELECT oi_sucursal FROM pedidos_masivos GROUP BY oi_sucursal));";
                                    $this->Pedido->query($sql_pedidos);

                                    // Actualizar los datos masivos marcandolos con los pedidos creados
                                    $sql_pedidos_marcar = "UPDATE pedidos_masivos SET pedido_id = pedidos.id FROM pedidos WHERE pedidos.pedido_oi_masivo = pedidos_masivos.oi_sucursal AND pedidos.observaciones = 'Masivo' AND pedidos.pedido_fecha = now()::date AND pedido_estado_pedido = 3;";
                                    $this->Pedido->query($sql_pedidos_marcar);

                                    // Insertar detalles del pedido
                                    $sql_pedidos_detalles = "INSERT INTO pedidos_detalles (producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, fecha_pedido_detalle) (SELECT producto_id, tipo_categoria_id, cantidad_pedido, pedido_id, precio_producto, iva_producto, medida_producto, now() fecha_pedido_detalle FROM pedidos_masivos WHERE pedido_id > 0 AND producto_id > 0 AND iva_producto is not null);";
                                    $this->Pedido->query($sql_pedidos_detalles);

                                    $sql_pedidos_creados = "SELECT DISTINCT pedido_id, nombre_sucursal, SUM(cantidad_pedido) FROM pedidos_masivos GROUP BY pedido_id, nombre_sucursal ORDER BY pedido_id;";
                                    $pedidos_creados = $this->Pedido->query($sql_pedidos_creados);
                                    $this->set('pedidos_creados', $pedidos_creados);

                                    $this->Session->setFlash(__('Las ordenes de pedido se crearon exitosamente.', true));
                                }
                                fclose($gestor);
                            }
                        } else {
                            $this->Session->setFlash(__('El tamaño del archivo supera las 20 MB. Verifique el tamaño del archivo.', true));
                        }
                    } else {
                        $this->Session->setFlash(__('El archivo NO es del tipo CSV. Verifique el tipo del archivo y realice nuevamente el proceso.', true));
                    }
                } else {
                    $this->Session->setFlash(__('No se seleccionó un archivo para cargar. Seleccione un archivo y realice nuevamente el proceso.', true));
                }
            } else {
                $this->Session->setFlash(__('No se seleccionó un tipo de pedido para cargar. Seleccione un tipo de pedido y realice nuevamente el proceso.', true));
            }
        }
    }

}

?>
