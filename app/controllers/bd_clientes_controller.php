<?php

class BdClientesController extends AppController {

    var $name = 'BdClientes';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('BdCliente', 'ListadoLlamada');

    function index() {
        $this->BdCliente->recursive = 0;
        $this->set('bdClientes', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid bd cliente', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('bdCliente', $this->BdCliente->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->BdCliente->create();
            $clientes = $this->BdCliente->find('count', array('conditions' => array('BdCliente.bd_identificacion' => $this->data['BdCliente']['bd_identificacion'])));
            if ($clientes > 0) {
                $this->Session->setFlash(__('El cliente ya existe en la base de datos. Por favor intente de nuevo.', true));
            } else {
                if ($this->BdCliente->save($this->data)) {

                    $this->data['ListadoLlamada']['bd_cliente_id'] = $this->BdCliente->getInsertID();
                    $this->data['ListadoLlamada']['estado_llamada'] = true;
                    $this->data['ListadoLlamada']['fecha_registro'] = 'now()';
                    $this->data['ListadoLlamada']['user_id'] = $this->Session->read('Auth.User.id');
                    $this->ListadoLlamada->create();
                    if ($this->ListadoLlamada->save($this->data)) {
                        $id = $this->ListadoLlamada->getInsertID();
                        $this->Session->setFlash(__('El Cliente ha sido salvado exitosamente.', true));
                        $this->redirect(array('controller' => 'ListadoLlamadas', 'action' => 'cotizacion/' . base64_encode($id)));
                    }
                } else {
                    $this->Session->setFlash(__('El cliente no puede ser salvado. Por favor intente de nuevo.', true));
                }
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid bd cliente', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->BdCliente->save($this->data)) {
                $this->Session->setFlash(__('The bd cliente has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The bd cliente could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->BdCliente->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for bd cliente', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->BdCliente->delete($id)) {
            $this->Session->setFlash(__('Bd cliente deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Bd cliente was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}
