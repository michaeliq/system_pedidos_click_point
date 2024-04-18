<?php

class BodegasController extends AppController {

    var $name = 'Bodegas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Bodega', 'Municipio', 'Departamento');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Bodegas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Bodegas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->Bodega->recursive = 0;
        $this->set('bodegas', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Bodega incorrecta', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('bodega', $this->Bodega->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Bodega->create();
            if ($this->Bodega->save($this->data)) {
                $this->Session->setFlash(__('La bodega ha sido guardada correctamente.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('La bodega no pudo ser guardada. Por favor intente de nuevo.', true));
            }
        }
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Bodega->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $this->set(compact('municipios', 'departamentos'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Bodega incorrecta', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Bodega->save($this->data)) {
                $this->Session->setFlash(__('La bodega ha sido guardada correctamente.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('La bodega no pudo ser guardada. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Bodega->read(null, $id);
        }
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Bodega->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $this->set(compact('municipios', 'departamentos'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('ID incorrecto para la bodega', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->Bodega->find('first', array('fields' => 'Bodega.estado_bodega', 'conditions' => array('Bodega.id' => $id)));
            if ($estado['Bodega']['estado_bodega']) {
                $this->Bodega->updateAll(array("Bodega.estado_bodega" => 'false'), array("Bodega.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO la bodega.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Bodega->updateAll(array("Bodega.estado_bodega" => 'true'), array("Bodega.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO la bodega.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('La Bodega no pudo ser actualizada', true));
        $this->redirect(array('action' => 'index'));
    }

}
