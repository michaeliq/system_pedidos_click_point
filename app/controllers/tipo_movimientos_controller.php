<?php

class TipoMovimientosController extends AppController {

    var $name = 'TipoMovimientos';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('TipoMovimiento');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('TipoMovimientos', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('TipoMovimientos', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->TipoMovimiento->recursive = 0;
        $this->set('tipoMovimientos', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid tipo movimiento', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('tipoMovimiento', $this->TipoMovimiento->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->TipoMovimiento->create();
            if ($this->TipoMovimiento->save($this->data)) {
                $this->Session->setFlash(__('El tipo de movimiento ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El tipo de movimiento no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
        $flujos = array('Interno' => 'Interno', 'Cliente' => 'Cliente', 'Proveedor' => 'Proveedor');
        $tipo_movimiento = array('E' => 'Entrada', 'S' => 'Salida');
        $this->set(compact('flujos', 'tipo_movimiento'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid tipo movimiento', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->TipoMovimiento->save($this->data)) {
                $this->Session->setFlash(__('El tipo de movimiento ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El tipo de movimiento no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->TipoMovimiento->read(null, $id);
        }
        $flujos = array('Interno' => 'Interno', 'Cliente' => 'Cliente', 'Proveedor' => 'Proveedor');
        $tipo_movimiento = array('E' => 'Entrada', 'S' => 'Salida');
        $this->set(compact('flujos', 'tipo_movimiento'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for tipo movimiento', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->TipoMovimiento->find('first', array('fields' => 'TipoMovimiento.estado_tipo_movimiento', 'conditions' => array('TipoMovimiento.id' => $id)));
            if ($estado['TipoMovimiento']['estado_tipo_movimiento']) {
                $this->TipoMovimiento->updateAll(array("TipoMovimiento.estado_tipo_movimiento" => 'false'), array("TipoMovimiento.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO al tipo de movimiento.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->TipoMovimiento->updateAll(array("TipoMovimiento.estado_tipo_movimiento" => 'true'), array("TipoMovimiento.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO al tipo de movimiento.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('Tipo movimiento was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}
