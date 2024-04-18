<?php

class CronogramasInventariosController extends AppController {

    var $name = 'CronogramasInventarios';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('CronogramasInventario', 'TipoCategoria', 'Bodega');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('CronogramasInventarios', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('CronogramasInventarios', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->CronogramasInventario->recursive = 0;
        $this->set('cronogramasInventarios', $this->paginate());

        $tipoCategorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.id, TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $this->set(compact('tipoCategorias'));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid cronogramas inventario', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('cronogramasInventario', $this->CronogramasInventario->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->CronogramasInventario->create();
            $this->data['CronogramasInventario']['tipo_categoria_id'] = json_encode($this->data['CronogramasInventario']['tipo_categoria_id']);
            if ($this->CronogramasInventario->save($this->data)) {
                $this->Session->setFlash(__('El cronograma de inventario ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El cronograma de inventarios no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
        $tipoCategorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $bodegas = $this->CronogramasInventario->Bodega->find('list', array('fields' => 'Bodega.nombre_bodega', 'order' => 'Bodega.nombre_bodega'));
        $this->set(compact('tipoCategorias', 'bodegas'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid cronogramas inventario', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['CronogramasInventario']['tipo_categoria_id'] = json_encode($this->data['CronogramasInventario']['tipo_categoria_id']);
            if ($this->CronogramasInventario->save($this->data)) {
                $this->Session->setFlash(__('El cronograma de inventario ha sido guardado', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El cronograma de inventarios no pudo ser guardado. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->CronogramasInventario->read(null, $id);
        }
        $tipoCategorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $bodegas = $this->CronogramasInventario->Bodega->find('list', array('fields' => 'Bodega.nombre_bodega', 'order' => 'Bodega.nombre_bodega'));
        $this->set(compact('tipoCategorias', 'bodegas'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for cronogramas inventario', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->CronogramasInventario->find('first', array('fields' => 'CronogramasInventario.estado_cronograma', 'conditions' => array('CronogramasInventario.id' => $id)));
            if ($estado['CronogramasInventario']['estado_cronograma']) {
                $this->CronogramasInventario->updateAll(array("CronogramasInventario.estado_cronograma" => 'false'), array("CronogramasInventario.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO al cronograma de inventarios.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->CronogramasInventario->updateAll(array("CronogramasInventario.estado_cronograma" => 'true'), array("CronogramasInventario.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO  al cronograma de inventarios.', true));
                $this->redirect(array('action' => 'index'));
            }
        }

        if ($this->CronogramasInventario->delete($id)) {
            $this->Session->setFlash(__('Cronogramas inventario deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Cronogramas inventario was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}
