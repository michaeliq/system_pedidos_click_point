<?php

class TipoSolicitudesController extends AppController {

    var $name = 'TipoSolicitudes';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('TipoSolicitude');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('TipoSolicitudes', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('TipoSolicitudes', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->TipoSolicitude->recursive = 0;
        $this->set('tipoSolicitudes', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid tipo solicitud', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('tipoMovimiento', $this->TipoSolicitude->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->TipoSolicitude->create();
            if ($this->TipoSolicitude->save($this->data)) {
                $this->Session->setFlash(__('El tipo de solicitud ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El tipo de solicitud no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid tipo solicitud', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->TipoSolicitude->save($this->data)) {
                $this->Session->setFlash(__('El tipo de solicitud ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El tipo de solicitud no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->TipoSolicitude->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for tipo solicitud', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->TipoSolicitude->find('first', array('fields' => 'TipoSolicitude.estado', 'conditions' => array('TipoSolicitude.id' => $id)));
            if ($estado['TipoSolicitude']['estado']) {
                $this->TipoSolicitude->updateAll(array("TipoSolicitude.estado" => 'false'), array("TipoSolicitude.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO al tipo de solicitud.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->TipoSolicitude->updateAll(array("TipoSolicitude.estado" => 'true'), array("TipoSolicitude.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO al tipo de solicitud.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('Tipo solicitud was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}
