<?php

class VendedoresController extends AppController {

    var $name = 'Vendedores';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Vendedore');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';
        $authorize = $this->Permisos->Allow('Vendedore', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Vendedore', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $this->Vendedore->set($this->data);
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Vendedore']['nombre_vendedor'])) {
                $where = "+Vendedore+.+nombre_vendedor+ ILIKE '%" . $this->data['Vendedore']['nombre_vendedor'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['Vendedore']['no_identificacion']) {
                $where = "+Vendedore+.+no_identificacion+ = " . $this->data['Vendedore']['no_identificacion'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->Vendedore->recursive = 0;
            $this->paginate = array('limit' => 50, 'order' => array('Vendedore.estado_vendedor' => 'desc'));
            $this->set('vendedores', $this->paginate($conditions));
        } else {
            // $conditions = array('Vendedore.empresa_id' => $this->Session->read('Regional.empresa_id'));
            $conditions = array();
            $this->paginate = array('limit' => 50, 'order' => array('Vendedore.id' => 'asc'));
            $this->Vendedore->recursive = 0;
            $this->set('vendedores', $this->paginate($conditions));
        }
        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $this->set(compact('estados'));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Vendedore->create();
            if ($this->Vendedore->save($this->data)) {
                $this->Session->setFlash(__('Se ha creado el vendedor ' . $this->data['Vendedore']['nombre_vendedor'] . '. ', true));
                $this->redirect(array('action' => 'index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('El vendedor no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró el vendedor', true));
            $this->redirect(array('action' => 'index')); /* header("Location: index"); */
        }
        if (!empty($this->data)) {

            if ($this->Vendedore->save($this->data)) {
                $this->Session->setFlash(__('El vendedor se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Vendedore->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Vendedor incorrecto.', true));
            $this->redirect(array('action' => 'index')); /* /header("Location: index"); */
        }
        if ($id > 0) {
            $estado = $this->Vendedore->find('first', array('fields' => 'Vendedore.estado_vendedor', 'conditions' => array('Vendedore.id' => $id)));
            if ($estado['Vendedore']['estado_vendedor']) {
                $this->Vendedore->updateAll(array("Vendedore.estado_vendedor" => 'false'), array("Vendedore.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO de el vendedor.', true));
                $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
            } else {
                $this->Vendedore->updateAll(array("Vendedore.estado_vendedor" => 'true'), array("Vendedore.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO de el vendedor.', true));
                /* $this->redirect(array('action' => 'index')); */header("Location: index");
            }
        }
        $this->Session->setFlash(__('El vendedor no fue encontrada.', true));
        $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
    }

}

?>
