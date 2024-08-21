<?php

class LocalidadesController extends AppController
{

    var $name = "Localidades";
    var $helpers = array('Ajax', 'Html', 'Javascript');
    var $uses = array("Localidad", "Sucursale", "Ruta", "TempLocalidad", "LocalidadRelRuta", "Empresa");
    var $components = array("RequestHandler", "Auth", "Permisos");

    function isAuthorized()
    {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Localidades', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Localidades', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index()
    {
        $this->Localidad->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->set('localidades', $this->paginate());
    }

    function view()
    {
        /**No es necesario de momento */
    }

    function add()
    {
        $rutas = $this->Ruta->find('list', array('fields' => 'Ruta.nombre', 'order' => 'Ruta.nombre'));
        $sucursales = [];
        if (!empty($this->data)) {
            $this->Localidad->create();
            if ($this->Localidad->save(array(
                "Localidad" => array(
                    "nombre_localidad" => $this->data["Localidades"]["nombre_localidad"],
                )
            ))) {
                $this->Session->setFlash('Localidad ' . $this->data["Localidad"]["nombre_localidad"] . ' Agregada', 'flash_success');
                $this->redirect("/localidades/index");
            } else {
                $this->Session->setFlash('La Localidad no se puede guardar, verifique los campos obligatorios e intente de nuevo.', 'flash_failure');
            }
        }
        $this->set(compact("rutas"));
    }

    function edit($location_id = null)
    {
        $rutas = $this->Ruta->find('list', array('fields' => 'Ruta.nombre', 'order' => 'Ruta.nombre'));
        $sucursales = [];
        if (!empty($this->data)) {
            if ($this->Localidad->save(array(
                "Localidad" => array(
                    "localidad_id" => $this->data["Localidades"]["localidad_id"],
                    "nombre_localidad" => $this->data["Localidades"]["nombre_localidad"],
                )
            ))) {
                $this->Session->setFlash('Localidad ' . $this->data["Localidad"]["nombre_localidad"] . ' ha sido actualizada', 'flash_success');
                $this->redirect("/localidades/index");
            } else {
                $this->Session->setFlash('La Localidad no se puedo actualizar, verifique e intente de nuevo.', 'flash_failure');
            }
        }
        $this->set("localidad", $this->Localidad->read(null, $location_id));
        $this->set(compact("rutas"));
    }

    function delete($location_id = null)
    {
        if (!$location_id) {
            $this->Session->setFlash('ID de Ruta faltante', 'flash_failure');
            $this->redirect("/localidades/index");
        } else {
            $ruta = $this->Localidad->find("first", array("conditions" => array("Localidad.localidad_id" => $location_id)));
            if ($ruta) {
                $this->Localidad->delete($location_id, false);
                $this->Session->setFlash('Ruta eliminada', 'flash_success');
                $this->redirect("/localidades/index");
            } else {
                $this->Session->setFlash('ID de Ruta no encontrado', 'flash_failure');
                $this->redirect("/localidades/index");
            }
        }
    }

    function upload_file_locations()
    {
        date_default_timezone_set('America/Bogota');
        $dir_file = 'localidades/masivos/';
        $max_file = 20145728;
        $localidades_validas = array();
        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }

        if ($this->RequestHandler->isPost()) {

            if ($this->data['Localidades']['archivo_csv']['name']) {
                if (($this->data['Localidades']['archivo_csv']['type'] == 'text/csv') || ($this->data['Localidades']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    if ($this->data['Localidades']['archivo_csv']['size'] < $max_file) {
                        move_uploaded_file($this->data['Localidades']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Localidades']['archivo_csv']['name']);
                        $file = fopen($dir_file . '/' . $this->data['Localidades']['archivo_csv']['name'], 'r');
                        if ($file) {
                            $row = 0;
                            $headers = [];
                            while (($data = fgetcsv($file, null, ";")) !== FALSE) {
                                #echo '<pre>'; print_r($data); echo '</pre>';
                                if ($row == 0) {
                                    $headers = $data;
                                } else {
                                    $data_localidades = array();
                                    for ($i = 0; $i < count($headers); $i++) {
                                        $data_localidades[$headers[$i]] = utf8_encode($data[$i]);
                                    }
                                    $localidad_from_db = $this->Localidad->find("first", array(
                                        'conditions' => array(
                                            'Localidad.localidad_id' => $data_localidades["ID"]
                                        )
                                    ));
                                    $sql_truncate = "DELETE FROM temp_localidades where localidad_id = " . $data_localidades["ID"] . ";";
                                    $this->TempLocalidad->query($sql_truncate);

                                    $this->TempLocalidad->create();
                                    $this->TempLocalidad->save(array(
                                        "TempLocalidad" => array(
                                            "nombre_localidad" => $data_localidades["LOCALIDAD"],
                                            "localidad_id" => $data_localidades["ID"],
                                            "to_create" => $localidad_from_db ? false : true,
                                        )
                                    ));
                                    if ($localidad_from_db) {
                                        $data_localidades["existe"] = true;
                                    } else {
                                        $data_localidades["existe"] = false;
                                    }
                                    array_push($localidades_validas, $data_localidades);
                                }
                                $row++;
                            }
                        }

                        $this->set("localidades_validas", $localidades_validas);
                        fclose($file);
                        unlink($dir_file . '/' . $this->data['Localidades']['archivo_csv']['name']);
                    } else {
                        $this->set("localidades_validas", $localidades_validas);
                        $this->Session->setFlash('El tamaño del archivo supera el maximo establecido (20MB).', 'flash_failure');
                    }
                } else {
                    $this->set("localidades_validas", $localidades_validas);
                    $this->Session->setFlash('El tipo de archivo no es el admitido para este proceso.', 'flash_failure');
                }
            } else {
                $this->set("localidades_validas", $localidades_validas);
                $this->Session->setFlash('Hubo un error al cargar el archivo. Verifique y vuelva a intentar.', 'flash_failure');
            }
        } else {
            $this->set("localidades_validas", $localidades_validas);
        }
    }

    function add_many_locations()
    {
        $localidades_validas = [];
        $data = array();
        $id_localidades = $this->data["Localidades"]["id_localidades"];
        $localidades_temporales = $this->TempLocalidad->find("all", array("conditions" => array("TempLocalidad.localidad_id" => explode(",", $id_localidades)), "fields" => array("TempLocalidad.nombre_localidad", "TempLocalidad.localidad_id", "TempLocalidad.to_create")));

        foreach ($localidades_temporales as $localidad_temp) {
            if ($localidad_temp["TempLocalidad"]["to_create"]) {

                array_push(
                    $data,
                    array("Localidad" => array(
                        "nombre_localidad" => $localidad_temp["TempLocalidad"]["nombre_localidad"],
                    ))
                );
            } else {
                $this->Localidad->save(array(
                    "Localidad" => array(
                        "nombre_localidad" => $localidad_temp["TempLocalidad"]["nombre_localidad"],
                        "localidad_id" => $localidad_temp["TempLocalidad"]["localidad_id"],
                    )
                ));
            }
        };
        $this->Localidad->saveAll($data);
        $sql_truncate = "DELETE FROM temp_localidades WHERE localidad_id IN (" . $id_localidades . ");";
        $this->Localidad->query($sql_truncate);

        $this->Session->setFlash('Se han añadido las localidades validas', 'flash_success');
        $this->set("localidades_validas", $localidades_validas);
        $this->redirect(array('controller' => 'localidades', 'action' => 'upload_file_locations'));
    }

    function add_location_routes_relations()
    {
        date_default_timezone_set('America/Bogota');
        $dir_file = 'localidades/masivos/';
        $max_file = 20145728;
        $localidades_validas = array();

        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }

        if ($this->RequestHandler->isPost()) {

            if ($this->data['Localidades']['archivo_csv']['name']) {
                if (($this->data['Localidades']['archivo_csv']['type'] == 'text/csv') || ($this->data['Localidades']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    if ($this->data['Localidades']['archivo_csv']['size'] < $max_file) {
                        move_uploaded_file($this->data['Localidades']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Localidades']['archivo_csv']['name']);
                        $file = fopen($dir_file . '/' . $this->data['Localidades']['archivo_csv']['name'], 'r');
                        if ($file) {
                            $row = 0;
                            $headers = [];
                            $sql_query_instert_array = array();
                            while (($data = fgetcsv($file, null, ";")) !== FALSE) {
                                #echo '<pre>'; print_r($data); echo '</pre>';
                                if ($row == 0) {
                                    $headers = $data;
                                } else {
                                    $data_localidades = array();
                                    for ($i = 0; $i < count($headers); $i++) {
                                        $data_localidades[$headers[$i]] = utf8_encode($data[$i]);
                                    }
                                    $localidad_from_db = $this->LocalidadRelRuta->find("first", array(
                                        'conditions' => array(
                                            'LocalidadRelRuta.codigo_sirbe' => $data_localidades["CODIGO_SIRBE"]
                                        ),
                                        'fields' => array("LocalidadRelRuta.codigo_sirbe"),
                                    ));

                                    if ($localidad_from_db) {
                                        $data_localidades["existe"] = true;
                                    } else {
                                        $data_localidades["existe"] = false;
                                    }

                                    array_push($sql_query_instert_array, array(
                                        "LocalidadRelRuta" => array(
                                            "localidad_id" => $data_localidades["LOCALIDAD"],
                                            "ruta_id" => $data_localidades["RUTA"],
                                            "codigo_sirbe" => $data_localidades["CODIGO_SIRBE"],
                                            "nombre_rel" => $data_localidades["LOCALIDAD_N"] . " - " . $data_localidades["RUTA_N"],
                                        )
                                    ));

                                    array_push($localidades_validas, $data_localidades);
                                }
                                $row++;
                            }

                            $this->LocalidadRelRuta->create();
                            $this->LocalidadRelRuta->saveAll($sql_query_instert_array);
                        }


                        $this->set("localidades_validas", $localidades_validas);
                        fclose($file);
                        unlink($dir_file . '/' . $this->data['Localidades']['archivo_csv']['name']);
                    } else {
                        $this->set("localidades_validas", $localidades_validas);
                        $this->Session->setFlash('El tamaño del archivo supera el maximo establecido (20MB).', 'flash_failure');
                    }
                } else {
                    $this->set("localidades_validas", $localidades_validas);
                    $this->Session->setFlash('El tipo de archivo no es el admitido para este proceso.', 'flash_failure');
                }
            } else {
                $this->set("localidades_validas", $localidades_validas);
                $this->Session->setFlash('Hubo un error al cargar el archivo. Verifique y vuelva a intentar.', 'flash_failure');
            }
        }
        $empresas = $this->Empresa->find("list", array("fields" => "nombre_empresa", "order" => "nombre_empresa"));
        $localidades = $this->Localidad->find("list", array("fields" => "nombre_localidad"));
        $rutas = $this->Ruta->find("list", array("fields" => "nombre"));

        $this->set(compact("rutas", "localidades", "localidades_validas", "empresas"));
    }

    function add_location_route_relation()
    {
        $ruta = $this->Ruta->find("first", ["conditions" => [
            "ruta_id" => $this->data["Localidades"]["ruta_id"]
        ]]);

        $localidad = $this->Localidad->find("first", ["conditions" => [
            "localidad_id" => $this->data["Localidades"]["localidad_id"]
        ]]);

        $sucursal = $this->Sucursale->find("first", [
            "conditions" => [
                "Sucursale.id" => $this->data["Localidades"]["sucursal_id"]
            ],
            "fields" => ["Sucursale.oi_sucursal","Sucursale.nombre_sucursal","Sucursale.id"]
        ]);

        $datos = array(
            "LocalidadRelRuta" => array(
                "localidad_id" => $this->data["Localidades"]["localidad_id"],
                "ruta_id" => $this->data["Localidades"]["ruta_id"],
                "codigo_sirbe" => $sucursal["Sucursale"]["id"],
                "nombre_rel" => $localidad["Localidad"]["nombre_localidad"] . " - " . $ruta["Ruta"]["nombre"],
            )
        );

        $this->LocalidadRelRuta->create();
        if ($this->LocalidadRelRuta->save($datos)) {
            $this->Session->setFlash('Se ha añadido la Relación a la Sucursal ' . $sucursal["Sucursale"]["nombre_sucursal"] , 'flash_success');
        } else {
            $this->Session->setFlash('Hubo un error al crear la Relación, intente nuevamente.', 'flash_failure');
        }

        $this->redirect(array('controller' => 'localidades', 'action' => 'add_location_routes_relations'));
    }
}
