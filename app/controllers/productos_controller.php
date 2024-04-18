<?php

class ProductosController extends AppController {

    var $name = 'Productos';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Producto', 'TipoCategoria', 'EstadoPedido', 'MovimientosEntradasDetalle', 'PlantillasDetalle');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Productos', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Productos', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function copiar($id = null) {

//        // Configure::write('debug', 2);
//        if (!$id && empty($this->data)) {
//            $this->Session->setFlash(__('No se encontró el producto', true));
//            // $this->redirect(array('action' => 'index'));
//            header("Location: ../index");
//        }

        if (!empty($this->data)) {
            // Configuración de la carga
            $dir_img = 'img/productos/';
            $max_file = 3145728; // 3 MB = 3145728 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['imagen_producto_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['imagen_producto_2']['type'] == 'image/jpeg' || $this->data['Producto']['imagen_producto_2']['type'] == 'image/png') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['imagen_producto_2']['size'] < $max_file) {
                        move_uploaded_file($this->data['Producto']['imagen_producto_2']['tmp_name'], $dir_img . '/' . $this->data['Producto']['imagen_producto_2']['name']);
                        $aux = explode('.', $this->data['Producto']['imagen_producto_2']['name']);

                        rename($dir_img . $this->data['Producto']['imagen_producto_2']['name'], $dir_img . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['imagen_producto'] = $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Configuración de la carga de brochure
            $dir_arc = 'img/productos/archivos/';
            $max_file2 = 10485760; // 10 MB = 10485760 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_2_1']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_2_1']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_2_1']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_2_1']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_2_1']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_2_1']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_2_1']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_2_1']['name'], $dir_arc . 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto'] = 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_2_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_2_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_2_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_2_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_2_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_2_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_2_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_2_2']['name'], $dir_arc . 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_2'] = 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_3_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_3_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_3_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_3_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_3_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_3_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_3_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_3_2']['name'], $dir_arc . 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_3'] = 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_4_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_4_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_4_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_4_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_4_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_4_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_4_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_4_2']['name'], $dir_arc . 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_4'] = 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            } else {
                $this->data['Producto']['archivo_adjunto_4'] = null;
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_5_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_5_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_5_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_5_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_5_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_5_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_5_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_5_2']['name'], $dir_arc . 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_5'] = 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            if ($this->Producto->save($this->data)) {
                $sql_plantillas_productos = "UPDATE plantillas_detalles 
                SET tipo_categoria_id = productos.tipo_categoria_id, iva_producto = productos.iva_producto, medida_producto = productos.medida_producto 
                FROM productos
                WHERE productos.id = " . $id . " AND 
                productos.id = plantillas_detalles.producto_id;";
                $this->Producto->query($sql_plantillas_productos);

                // Actualizar productos de movimientos
                $sql_movimientos_productos = "UPDATE movimientos_productos 
                SET estado = productos.estado
                FROM productos
                WHERE productos.id = movimientos_productos.producto_id;";
                $this->Producto->query($sql_movimientos_productos);

                $this->Session->setFlash(__('El producto se ha actualizado exitosamente', true));
                // $this->redirect(array('action' => 'index'));
                header("Location: ../index");
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Producto->read(null, $id);
            
        }

        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'conditions'=>array('TipoCategoria.estado'=>true),'order' => 'TipoCategoria.id'));
        $unidadMedida = array(
            'UNI' => 'UNIDAD',
            'GAL' => 'GAL�N',
            'LIT' => 'LITRO',
            'CU�' => 'CUÑETE',
            'PAQ X 6' => 'PAQUETE X 6',
            'PAQ' => 'PAQUETE',
            'MTS' => 'METROS',
            'KG' => 'KILOS',
            'CAJ' => 'CAJAS',
            'PAR' => 'PAR',
            'ROLLO' => 'ROLLO');
        $this->set(compact('tipoCategoria', 'unidadMedida'));

        $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array(
            // 'fields' => array('Producto.codigo_producto', 'MovimientosEntradasDetalle.precio_producto', 'MovimientosEntradasDetalle.cantidad_entrada', 'AVG(MovimientosEntradasDetalle.precio_producto)'),
            'conditions' => array('MovimientosEntradasDetalle.estado_entrada' => true, 'MovimientosEntradasDetalle.producto_id' => $id),
            // 'group' =>'Producto.codigo_producto, MovimientosEntradasDetalle.precio_producto, MovimientosEntradasDetalle.cantidad_entrada, producto_id',
            'order' => 'MovimientosEntradasDetalle.fecha_registro_entrada',
            'limit' => '10'
        ));
        $this->set('movimientosEntradas', $movimientosEntradas);
    }

    function index() {
        $conditions = array('Producto.estado' => true, 'Producto.mostrar_producto' => true);

        $this->Producto->set($this->data);

        if (!empty($this->data)) {
            if (!empty($this->data['Producto']['empresa1_codigo'])) {
                $where = "+Producto+.+empresa1_codigo+ LIKE '" . $this->data['Producto']['empresa1_codigo'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Producto']['codigo_producto'])) {
                $where = "+Producto+.+codigo_producto+ LIKE '" . $this->data['Producto']['codigo_producto'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Producto']['nombre_producto'])) {
                $where = "+Producto+.+nombre_producto+ ILIKE '%" . strtoupper($this->data['Producto']['nombre_producto']) . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Producto']['tipo_categoria_id'])) {
                $where = "+Producto+.+tipo_categoria_id+ = " . $this->data['Producto']['tipo_categoria_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['Producto']['estado'] == 'false' || $this->data['Producto']['estado'] == 'true') {
                $where = "+Producto+.+estado+ = " . $this->data['Producto']['estado'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
//            if ($this->data['Producto']['proveedor_producto'] == '0' || $this->data['Producto']['proveedor_producto'] == '1') {
//                $where = "+Producto+.+proveedor_producto+ = '" . $this->data['Producto']['proveedor_producto'] . "'";
//                $where = str_replace('+', '"', $where);
//                array_push($conditions, $where);
//            }

            $this->paginate = array('limit' => 300);
            $this->set('productos', $this->paginate($conditions));

//            $this->Session->write('Paginator.index', $conditions);
//            $conditions = $this->Session->read('Paginator.index');
        } else {

            $this->Producto->recursive = 0;
            $this->paginate = array('limit' => 300);
            $this->helpers['Paginator'] = array('ajax' => 'Ajax');
            $this->set('productos', $this->paginate($conditions));
        }

//        $conditions = array('Producto.estado' => true);
//        $this->paginate = array('limit' => 100);
//        $this->Producto->recursive = 0;
//        $this->set('productos', $this->paginate($conditions));
        $estado = array('false' => 'Inactivo', 'true' => 'Activo');
        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'conditions'=>array('TipoCategoria.estado'=>true),'order' => 'TipoCategoria.id'));
        $estadoPedido = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.nombre_estado'));
        $this->set(compact('tipoCategoria', 'estadoPedido', 'estado'));
    }

    function add() {
//	Configure::write('debug', 2);
        if (!empty($this->data)) {
            $existe = $this->Producto->find('count', array('conditions' => array('Producto.codigo_producto' => $this->data['Producto']['codigo_producto'])));
            if ($existe > 0) {
                $this->Session->setFlash(__('El producto no puede ser salvado ya que el código del producto ' . $this->data['Producto']['codigo_producto'] . ' ya existe.', true));
            } else {

                // Configuración de la carga
                $dir_img = 'img/productos/';
                $max_file = 3145728; // 3 MB = 3145728 byte
                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['imagen_producto']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['imagen_producto']['type'] == 'image/jpeg' || $this->data['Producto']['imagen_producto']['type'] == 'image/png') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['imagen_producto']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['imagen_producto']['tmp_name'], $dir_img . '/' . $this->data['Producto']['imagen_producto']['name']);
                            $aux = explode('.', $this->data['Producto']['imagen_producto']['name']);

                            rename($dir_img . $this->data['Producto']['imagen_producto']['name'], $dir_img . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['imagen_producto'] = $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        } else {
                            $this->data['Producto']['imagen_producto'] = 'def.jpg';
                        }
                    } else {
                        $this->data['Producto']['imagen_producto'] = 'def.jpg';
                    }
                } else {
                    $this->data['Producto']['imagen_producto'] = 'def.jpg';
                }

                // Configuración de la carga de brochure
                $dir_arc = 'img/productos/archivos/';
                $max_file = 10485760; // 10 MB = 10485760 byte
                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['archivo_adjunto']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['archivo_adjunto']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto']['type'] == 'application/octet-stream') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['archivo_adjunto']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['archivo_adjunto']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto']['name']);
                            $aux = explode('.', $this->data['Producto']['archivo_adjunto']['name']);

                            rename($dir_arc . $this->data['Producto']['archivo_adjunto']['name'], $dir_arc . 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['archivo_adjunto'] = 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]; // $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        }
                    }
                } else {
                    $this->data['Producto']['archivo_adjunto'] = null;
                }

                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['archivo_adjunto_2']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['archivo_adjunto_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_2']['type'] == 'application/octet-stream') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['archivo_adjunto_2']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['archivo_adjunto_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_2']['name']);
                            $aux = explode('.', $this->data['Producto']['archivo_adjunto_2']['name']);

                            rename($dir_arc . $this->data['Producto']['archivo_adjunto_2']['name'], $dir_arc . 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['archivo_adjunto_2'] = 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]; // $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        }
                    }
                } else {
                    $this->data['Producto']['archivo_adjunto_2'] = null;
                }


                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['archivo_adjunto_3']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['archivo_adjunto_3']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_3']['type'] == 'application/octet-stream') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['archivo_adjunto_3']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['archivo_adjunto_3']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_3']['name']);
                            $aux = explode('.', $this->data['Producto']['archivo_adjunto_3']['name']);

                            rename($dir_arc . $this->data['Producto']['archivo_adjunto_3']['name'], $dir_arc . 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['archivo_adjunto_3'] = 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]; // $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        }
                    }
                } else {
                    $this->data['Producto']['archivo_adjunto_3'] = null;
                }

                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['archivo_adjunto_4']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['archivo_adjunto_4']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_4']['type'] == 'application/octet-stream') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['archivo_adjunto_4']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['archivo_adjunto_4']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_4']['name']);
                            $aux = explode('.', $this->data['Producto']['archivo_adjunto_4']['name']);

                            rename($dir_arc . $this->data['Producto']['archivo_adjunto_4']['name'], $dir_arc . 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['archivo_adjunto_4'] = 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]; // $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        }
                    }
                } else {
                    $this->data['Producto']['archivo_adjunto_4'] = null;
                }

                // Verificar que se haya cargado un archivo
                if (!empty($this->data['Producto']['archivo_adjunto_5']['name'])) {
                    // Verificar si el archivo tiene formato .txt
                    if ($this->data['Producto']['archivo_adjunto_5']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_5']['type'] == 'application/octet-stream') {
                        // Verificar el tamaño del archivo
                        if ($this->data['Producto']['archivo_adjunto_5']['size'] < $max_file) {
                            move_uploaded_file($this->data['Producto']['archivo_adjunto_5']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_5']['name']);
                            $aux = explode('.', $this->data['Producto']['archivo_adjunto_5']['name']);

                            rename($dir_arc . $this->data['Producto']['archivo_adjunto_5']['name'], $dir_arc . 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                            $this->data['Producto']['archivo_adjunto_5'] = 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]; // $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                        }
                    }
                } else {
                    $this->data['Producto']['archivo_adjunto_5'] = null;
                }

                $this->Producto->create();
                if ($this->Producto->save($this->data)) {
                    $this->Session->setFlash(__('Se ha creado el producto ' . $this->data['Producto']['nombre_producto'] . '. ', true));
                    // $this->redirect(array('action' => '/index'));
                    header("Location: index");
                } else {
                    $this->Session->setFlash(__('El producto no puede ser salvado. Por favor intente de nuevo.', true));
                }
            }
        }

        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'conditions'=>array('TipoCategoria.estado'=>true),'order' => 'TipoCategoria.id'));
        $estadoPedido = $this->EstadoPedido->find('list', array('fields' => 'EstadoPedido.nombre_estado', 'order' => 'EstadoPedido.nombre_estado'));
        $unidadMedida = array(
            'UNI' => 'UNIDAD',
            'GAL' => 'GAL�N',
            'LIT' => 'LITRO',
            'CUN' => 'CUÑETE',
            'PAQ X 6' => 'PAQUETE X 6',
            'PAQ' => 'PAQUETE',
            'MTS' => 'METROS',
            'KG' => 'KILOS',
            'CAJ' => 'CAJAS',
            'PAR' => 'PAR',
            'ROLLO' => 'ROLLO');
        $this->set(compact('tipoCategoria', 'estadoPedido', 'unidadMedida'));
    }

    function edit($id = null) {
        // Configure::write('debug', 2);
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró el producto', true));
            // $this->redirect(array('action' => 'index'));
            header("Location: ../index");
        }

        if (!empty($this->data)) {
            // Configuración de la carga
            $dir_img = 'img/productos/';
            $max_file = 3145728; // 3 MB = 3145728 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['imagen_producto_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['imagen_producto_2']['type'] == 'image/jpeg' || $this->data['Producto']['imagen_producto_2']['type'] == 'image/png') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['imagen_producto_2']['size'] < $max_file) {
                        move_uploaded_file($this->data['Producto']['imagen_producto_2']['tmp_name'], $dir_img . '/' . $this->data['Producto']['imagen_producto_2']['name']);
                        $aux = explode('.', $this->data['Producto']['imagen_producto_2']['name']);

                        rename($dir_img . $this->data['Producto']['imagen_producto_2']['name'], $dir_img . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['imagen_producto'] = $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Configuración de la carga de brochure
            $dir_arc = 'img/productos/archivos/';
            $max_file2 = 10485760; // 10 MB = 10485760 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_2_1']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_2_1']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_2_1']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_2_1']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_2_1']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_2_1']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_2_1']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_2_1']['name'], $dir_arc . 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto'] = 'FT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_2_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_2_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_2_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_2_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_2_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_2_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_2_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_2_2']['name'], $dir_arc . 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_2'] = 'RI_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_3_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_3_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_3_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_3_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_3_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_3_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_3_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_3_2']['name'], $dir_arc . 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_3'] = 'HS_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_4_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_4_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_4_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_4_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_4_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_4_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_4_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_4_2']['name'], $dir_arc . 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_4'] = 'RB_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            } else {
                $this->data['Producto']['archivo_adjunto_4'] = null;
            }

            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Producto']['archivo_adjunto_5_2']['name'])) {
                // Verificar si el archivo tiene formato .txt
                if ($this->data['Producto']['archivo_adjunto_5_2']['type'] == 'application/pdf' || $this->data['Producto']['archivo_adjunto_5_2']['type'] == 'application/octet-stream') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Producto']['archivo_adjunto_5_2']['size'] < $max_file2) {
                        move_uploaded_file($this->data['Producto']['archivo_adjunto_5_2']['tmp_name'], $dir_arc . '/' . $this->data['Producto']['archivo_adjunto_5_2']['name']);
                        $aux = explode('.', $this->data['Producto']['archivo_adjunto_5_2']['name']);

                        rename($dir_arc . $this->data['Producto']['archivo_adjunto_5_2']['name'], $dir_arc . 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1]);
                        $this->data['Producto']['archivo_adjunto_5'] = 'OT_' . $this->data['Producto']['codigo_producto'] . '.' . $aux[1];
                    }
                }
            }

            if ($this->Producto->save($this->data)) {
                $sql_plantillas_productos = "UPDATE plantillas_detalles 
                SET tipo_categoria_id = productos.tipo_categoria_id, iva_producto = productos.iva_producto, medida_producto = productos.medida_producto 
                FROM productos
                WHERE productos.id = " . $id . " AND 
                productos.id = plantillas_detalles.producto_id;";
                $this->Producto->query($sql_plantillas_productos);

                // Actualizar productos de movimientos
                $sql_movimientos_productos = "UPDATE movimientos_productos 
                SET estado = productos.estado
                FROM productos
                WHERE productos.id = movimientos_productos.producto_id;";
                $this->Producto->query($sql_movimientos_productos);

                $this->Session->setFlash(__('El producto se ha actualizado exitosamente', true));
                // $this->redirect(array('action' => 'index'));
                header("Location: ../index");
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Producto->read(null, $id);
        }

        $tipoCategoria = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'conditions'=>array('TipoCategoria.estado'=>true),'order' => 'TipoCategoria.id'));
        $unidadMedida = array(
            'UNI' => 'UNIDAD',
            'GAL' => 'GAL�N',
            'LIT' => 'LITRO',
            'CU�' => 'CUÑETE',
            'PAQ X 6' => 'PAQUETE X 6',
            'PAQ' => 'PAQUETE',
            'MTS' => 'METROS',
            'KG' => 'KILOS',
            'CAJ' => 'CAJAS',
            'PAR' => 'PAR',
            'ROLLO' => 'ROLLO');
        $this->set(compact('tipoCategoria', 'unidadMedida'));

        $movimientosEntradas = $this->MovimientosEntradasDetalle->find('all', array(
            // 'fields' => array('Producto.codigo_producto', 'MovimientosEntradasDetalle.precio_producto', 'MovimientosEntradasDetalle.cantidad_entrada', 'AVG(MovimientosEntradasDetalle.precio_producto)'),
            'conditions' => array('MovimientosEntradasDetalle.estado_entrada' => true, 'MovimientosEntradasDetalle.producto_id' => $id),
            // 'group' =>'Producto.codigo_producto, MovimientosEntradasDetalle.precio_producto, MovimientosEntradasDetalle.cantidad_entrada, producto_id',
            'order' => 'MovimientosEntradasDetalle.fecha_registro_entrada',
            'limit' => '10'
        ));
        $this->set('movimientosEntradas', $movimientosEntradas);
    }

    function edit_porcentaje($id = null) {
        if (!empty($this->data)) {
            if (is_numeric($this->data['Producto']['porcentaje'])) {
                $plantillas_detalles = $this->PlantillasDetalle->find('all', array('conditions' => array('PlantillasDetalle.producto_id' => $this->data['Producto']['id'])));

                // print_r($plantillas_detalles);
                // print_r($this->data);
                // Porcentaje
                $porcentaje = $this->data['Producto']['porcentaje'] / 100;

                // Actualizar productos
                $sql_productos = "UPDATE productos 
                SET precio_producto = precio_producto + (precio_producto * " . $porcentaje . "),
                fecha_actualizacion = now()
                WHERE id = " . $this->data['Producto']['id'] . ";";
                // echo $sql_productos;
                $this->Producto->query($sql_productos);

                // Actualizar plantillas
                $sql_plantillas = "UPDATE plantillas_detalles 
                SET precio_producto = precio_producto + (precio_producto * " . $porcentaje . "),
                fecha_actualizacion_timestamp = now()
                WHERE producto_id = " . $this->data['Producto']['id'] . ";";
                $this->Producto->query($sql_plantillas);
                // echo $sql_plantillas;

                $this->set('plantillas_detalles', $plantillas_detalles);
                $this->set('porcentaje', $porcentaje);
                $this->Session->setFlash(__('Se han actualizado las plantillas exitosamente.', true));
            } else {
                $this->Session->setFlash(__('Se ha producido un error en la actualizació de precios. Intente de Nuevo.', true));
            }
        } else {
            header("Location: ../index");
        }
    }

    /* function delete($id = null) {
      if (!$id) {
      $this->Session->setFlash(__('Producto invalido.', true));
      // $this->redirect(array('action' => 'index'));
      header("Location: ../index");
      }
      if ($id > 0) {
      $estado = $this->Producto->find('first', array('fields' => 'Producto.estado', 'conditions' => array('Producto.id' => $id)));
      if ($estado['Producto']['estado']) {
      $this->Producto->updateAll(array("Producto.estado" => 'false'), array("Producto.id" => $id));

      $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO el Producto.', true));
      // $this->redirect(array('action' => 'index'));
      header("Location: ../index");
      } else {
      $this->Producto->updateAll(array("Producto.estado" => 'true'), array("Producto.id" => $id));

      $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO el Producto.', true));
      // $this->redirect(array('action' => 'index'));
      header("Location: ../index");
      }
      }
      $this->Session->setFlash(__('El producto indicado no fue encontrado.', true));
      // $this->redirect(array('action' => 'index'));
      header("Location: ../index");
      } */

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Producto invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->Producto->find('first', array('fields' => 'Producto.estado', 'conditions' => array('Producto.id' => $id)));
            if ($estado['Producto']['estado']) {
                $this->Producto->updateAll(array("Producto.estado" => 'false'), array("Producto.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO el Producto.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Producto->updateAll(array("Producto.estado" => 'true'), array("Producto.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO el Producto.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('El producto indicado no fue encontrado.', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
