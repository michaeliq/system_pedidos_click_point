<?php

class RutasController extends AppController
{
    var $name = "Rutas";
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $helpers = array('Ajax');
    var $uses = array('Ruta', 'TempRuta', 'Departamento', 'Municipio', 'Sucursale', 'MenuAction');

    function isAuthorized()
    {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Rutas', $this->Session->read('Auth.User.rol_id'));

        $this->set('authorize', $authorize);

        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Rutas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index()
    {
        $this->Ruta->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->set('rutas', $this->paginate());
    }

    function edit($ruta_id)
    {
        if (!empty($this->data)) { # Post Request
            if ($this->Ruta->save($this->data)) {
                $this->Session->setFlash('Ruta ' . $this->data["Ruta"]["nombre"] . ' Actualizada.', 'flash_info');
                $this->redirect(array('controller' => 'rutas', 'action' => 'index'));
            } else {
                $this->Session->setFlash('La Ruta no se pudo actualizar, verifique e intente de nuevo.', 'flash_warning');
            }
        } else if (!$ruta_id) { # GET Request
            $this->Session->setFlash('ID de Ruta faltante', 'flash_failure');
            $this->redirect(array('action' => 'index'));
        } else {
            $ruta = $this->Ruta->find("first", array("conditions" => array("Ruta.ruta_id" => $ruta_id)));
            if ($ruta) {
                $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
                $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));

                $sucursales = $this->Sucursale->find("all", array(
                    'conditions' => array(
                        'Sucursale.departamento_id' => $this->Ruta->read(null, $ruta_id)["Ruta"]["departamento_id"],
                        'Sucursale.municipio_id' => $this->Ruta->read(null, $ruta_id)["Ruta"]["municipio_id"],
                    ),
                    'fields' => array("Sucursale.nombre_sucursal", "Empresa.nombre_empresa", "Departamento.nombre_departamento", "Municipio.nombre_municipio", "Sucursale.ruta_id", "Sucursale.id")
                ));
                $this->set("ruta", $ruta);
                $this->set('departamentos', $departamentos);
                $this->set('municipios', $municipios);
                $this->set('sucursales', $sucursales);
            } else {
                $this->Session->setFlash('ID de Ruta no encontrado', 'flash_failure');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function delete($ruta_id = null)
    {
        if (!$ruta_id) {
            $this->Session->setFlash('ID de Ruta faltante', 'flash_failure');
            $this->redirect(array('action' => 'index'));
        } else {
            $ruta = $this->Ruta->find("first", array("conditions" => array("Ruta.ruta_id" => $ruta_id)));
            if ($ruta) {
                $this->Ruta->delete($ruta_id, false);
                $this->Session->setFlash('Ruta eliminada', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('ID de Ruta no encontrado', 'flash_failure');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function view($ruta_id = null)
    {
        $options = $this->MenuAction->find('all', array('conditions' => array('MenuAction.menus_actions_ajax' => false)));
        $this->set('options', $options);

        if (!$ruta_id) {
            $this->Session->setFlash(__('ID de Ruta invalido', true));
            $this->redirect(array('action' => 'index'));
        }

        $departamento = $this->Departamento->find('first', array('conditions' => array('Departamento.id' => $this->Ruta->read(null, $ruta_id)['Ruta']['departamento_id']), 'fields' => 'Departamento.nombre_departamento'));

        $municipio = $this->Municipio->find('first', array('conditions' => array('Municipio.id' => $this->Ruta->read(null, $ruta_id)["Ruta"]["municipio_id"]), 'fields' => 'Municipio.nombre_municipio'));

        $sucursales = $this->Sucursale->find("all", array(
            'conditions' => array(
                'Sucursale.departamento_id' => $this->Ruta->read(null, $ruta_id)["Ruta"]["departamento_id"],
                'Sucursale.municipio_id' => $this->Ruta->read(null, $ruta_id)["Ruta"]["municipio_id"],
            ),
            'fields' => array("Sucursale.nombre_sucursal", "Empresa.nombre_empresa", "Departamento.nombre_departamento", "Municipio.nombre_municipio", "Sucursale.ruta_id", "Sucursale.id")
        ));
        #debug(count($sucursales));
        $this->set('ruta', $this->Ruta->read(null, $ruta_id));
        $this->set('departamento', $departamento["Departamento"]["nombre_departamento"]);
        $this->set('municipio', $municipio["Municipio"]["nombre_municipio"]);
        $this->set('sucursales', $sucursales);
    }

    function add()
    {
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $sucursales = [];
        if (!empty($this->data)) {
            $this->Ruta->create();
            if ($this->Ruta->save($this->data)) {
                $this->Session->setFlash('Ruta ' . $this->data["Ruta"]["nombre"] . ' Agregada', 'flash_success');
                $this->redirect(array('controller' => 'rutas', 'action' => 'index'));
            } else {
                $this->Session->setFlash('La Ruta no se puede guardar, verifique los campos obligatorios e intente de nuevo.', 'flash_success');
            }
        }
        $this->set(compact("departamentos", "municipios"));
    }

    function upload_file_routes()
    {
        date_default_timezone_set('America/Bogota');
        $dir_file = 'rutas/masivos/';
        $max_file = 20145728;
        $rutas_validas = array();
        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }

        if ($this->RequestHandler->isPost()) {

            # Reset de tabla temporal de rutas
            $sql_truncate = "TRUNCATE TABLE temp_rutas;";
            $this->TempRuta->query($sql_truncate);

            if ($this->data['Ruta']['archivo_csv']['name']) {
                if (($this->data['Ruta']['archivo_csv']['type'] == 'text/csv') || ($this->data['Ruta']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    if ($this->data['Ruta']['archivo_csv']['size'] < $max_file) {
                        move_uploaded_file($this->data['Ruta']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Ruta']['archivo_csv']['name']);
                        $file = fopen($dir_file . '/' . $this->data['Ruta']['archivo_csv']['name'], 'r');
                        if ($file) {
                            $row = 0;
                            $headers = [];
                            while (($data = fgetcsv($file, null, ";")) !== FALSE) {
                                #echo '<pre>'; print_r($data); echo '</pre>';
                                if ($row == 0) {
                                    $headers = $data;
                                } else {
                                    $data_ruta = array();
                                    for ($i = 0; $i < count($headers); $i++) {
                                        $data_ruta[$headers[$i]] = $data[$i];
                                    }
                                    $ruta_from_db = $this->Ruta->find("first", array(
                                        'conditions' => array(
                                            'Ruta.codigo_sirbe' => $data_ruta["CODIGO_SIRBE"]
                                        )
                                    ));
                                    $this->TempRuta->create();
                                    $this->TempRuta->save(array(
                                        "TempRuta" => array(
                                            "codigo_sirbe" => $data_ruta["CODIGO_SIRBE"],
                                            "nombre" => $data_ruta["RUTA"],
                                            "departamento_id" => $data_ruta["DEPARTAMENTO"],
                                            "municipio_id" => $data_ruta["MUNICIPIO"],
                                            "ruta_id" => $ruta_from_db["Ruta"]["ruta_id"],
                                            "to_create" => $ruta_from_db ? false : true,
                                        )
                                    ));
                                    if ($ruta_from_db) {
                                        $data_ruta["existe"] = true;
                                    } else {
                                        $data_ruta["existe"] = false;
                                    }
                                    array_push($rutas_validas, $data_ruta);
                                }
                                $row++;
                            }
                        }

                        $this->set("rutas_validas", $rutas_validas);
                        fclose($file);
                        unlink($dir_file . '/' . $this->data['Ruta']['archivo_csv']['name']);
                        
                    } else {
                        $this->Session->setFlash('El tamaño del archivo supera el maximo establecido (20MB).', 'flash_failure');
                    }
                } else {
                    $this->Session->setFlash('El tipo de archivo no es el admitido para este proceso.', 'flash_failure');
                }
            } else {
                $this->Session->setFlash('Hubo un error al cargar el archivo. Verifique y vuelva a intentar.', 'flash_failure');
            }
        } else {
            $this->set("rutas_validas", $rutas_validas);
        }
    }

    function create_many_routes()
    {
        $rutas_validas = [];
        $data = array();
        $rutas_temporales = $this->TempRuta->find("all", array("fields" => array("TempRuta.nombre", "TempRuta.codigo_sirbe", "TempRuta.departamento_id", "TempRuta.municipio_id", "TempRuta.ruta_id", "TempRuta.to_create")));

        foreach ($rutas_temporales as $ruta_temp) {
            if ($ruta_temp["TempRuta"]["to_create"]) {

                array_push(
                    $data,
                    array("Ruta" => array(
                        "codigo_sirbe" => $ruta_temp["TempRuta"]["codigo_sirbe"],
                        "nombre" => $ruta_temp["TempRuta"]["nombre"],
                        "departamento_id" => $ruta_temp["TempRuta"]["departamento_id"],
                        "municipio_id" => $ruta_temp["TempRuta"]["municipio_id"],
                    ))
                );
            } else {
                $this->Ruta->save(array(
                    "Ruta" => array(
                        "codigo_sirbe" => $ruta_temp["TempRuta"]["codigo_sirbe"],
                        "nombre" => $ruta_temp["TempRuta"]["nombre"],
                        "departamento_id" => $ruta_temp["TempRuta"]["departamento_id"],
                        "municipio_id" => $ruta_temp["TempRuta"]["municipio_id"],
                        "ruta_id" => $ruta_temp["TempRuta"]["ruta_id"],
                    )
                ));
            }
        };
        $this->Ruta->saveAll($data);
        $sql_truncate = "TRUNCATE TABLE temp_rutas;";
        $this->TempRuta->query($sql_truncate);

        $this->Session->setFlash('Se han añadido las rutas validas', 'flash_success');
        $this->set("rutas_validas", $rutas_validas);
        $this->redirect(array('controller' => 'rutas', 'action' => 'upload_file_routes'));
    }

    /** Ajax requests */

    function update_sucursale_en_ruta()
    {

        if ($this->RequestHandler->isAjax()) { //condición que pregunta si la petición es AJAX
            $sucursal = $this->Sucursale->read(null, $_REQUEST['sucursal_id']);
            if ($sucursal["Sucursale"]["ruta_id"] == $_REQUEST['ruta_id']) {
                $this->Sucursale->save(array(
                    "Sucursale" => array(
                        "id" => $_REQUEST['sucursal_id'],
                        "ruta_id" => null,
                    )
                ));
                echo json_encode(array(
                    "status" => true,
                    "code" => 200,
                    "ruta_id" => null
                ));
            } else {
                $this->Sucursale->save(array(
                    "Sucursale" => array(
                        "id" => $_REQUEST['sucursal_id'],
                        "ruta_id" => $_REQUEST['ruta_id'],
                    )
                ));
                echo json_encode(array(
                    "status" => true,
                    "code" => 200,
                    "ruta_id" => $_REQUEST['ruta_id']
                ));
            }
        }
    }
}
