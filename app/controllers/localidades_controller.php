<?php

class LocalidadesController extends AppController
{

    var $name = "Localidades";
    var $uses = array("Localidad", "Sucursale", "Ruta");
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
                    "ruta_id" => $this->data["Localidades"]["ruta_id"],
                )
            ))) {
                $this->Session->setFlash('Localidad ' . $this->data["Localidad"]["nombre_localidad"] . ' Agregada', 'flash_success');
                $this->redirect(array('controller' => 'localidades', 'action' => 'index'));
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
                    "ruta_id" => $this->data["Localidades"]["ruta_id"],
                )
            ))) {
                $this->Session->setFlash('Localidad ' . $this->data["Localidad"]["nombre_localidad"] . ' ha sido actualizada', 'flash_success');
                $this->redirect(array('controller' => 'localidades', 'action' => 'index'));
            } else {
                $this->Session->setFlash('La Localidad no se puedo actualizar, verifique e intente de nuevo.', 'flash_failure');
            }
        }
        $this->set("localidad", $this->Localidad->read(null,$location_id));
        $this->set(compact("rutas"));
    }

    function delete($location_id = null)
    {
        if (!$location_id) {
            $this->Session->setFlash('ID de Ruta faltante', 'flash_failure');
            $this->redirect(array('action' => 'index'));
        } else {
            $ruta = $this->Localidad->find("first", array("conditions" => array("Localidad.localidad_id" => $location_id)));
            if ($ruta) {
                $this->Localidad->delete($location_id, false);
                $this->Session->setFlash('Ruta eliminada', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('ID de Ruta no encontrado', 'flash_failure');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function upload_file_locations()
    {
    }

    function add_many_locations()
    {
    }
}
