<?php

class RegionalesController extends AppController {

    var $name = 'Regionales';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Regionale', 'TipoPedido', 'Empresa', 'User', 'EmpresasAprobadore', 'Sucursale');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';
        $authorize = $this->Permisos->Allow('Regionale', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Regionale', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index($id = null) {
        if ($id) {
            $this->Session->write('Regional.empresa_id', $id);
            $empresa = $this->Empresa->find('all', array('fields' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => $id)));
            $this->Session->write('Empresa.nombre_empresa', $empresa['0']['Empresa']['nombre_empresa']);
            $this->redirect(array('action' => 'index'));
        }
        $this->Regionale->set($this->data);
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Regionale']['nombre_regional'])) {
                $where = "+Regionale+.+nombre_regional+ ILIKE '%" . $this->data['Regionale']['nombre_regional'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['Regionale']['estado_regional']) {
                $where = "+Regionale+.+estado_regional+ = " . $this->data['Regionale']['estado_regional'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->Regionale->recursive = 0;
            $this->paginate = array('limit' => 50, 'order' => array('Regionale.estado_regional' => 'desc'));
            $this->set('regionals', $this->paginate($conditions));
        } else {
            $conditions = array('Regionale.empresa_id' => $this->Session->read('Regional.empresa_id'));
            $this->paginate = array('limit' => 50, 'order' => array('Regionale.nombre_regional' => 'asc'));
            $this->Regionale->recursive = 0;
            $this->set('regionals', $this->paginate($conditions));
        }
        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $empresa = $this->Session->read('Empresa.nombre_empresa');
        $this->set('empresa', $empresa);
        $this->set(compact('estados'));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Regionale->create();
            if ($this->Regionale->save($this->data)) {
                $this->Session->setFlash(__('Se ha creado la regional ' . $this->data['Regionale']['nombre_regional'] . '. ', true));
                $this->redirect(array('action' => 'edit/' . $this->Regionale->getInsertID())); /* header("Location: index"); */
                // $this->redirect(array('action' => 'index/' . $this->Session->read('Regional.empresa_id'))); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('La regional no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró la regional', true));
            $this->redirect(array('action' => 'index')); /* header("Location: index"); */
        } else {
            $regional = $this->Regionale->read(null, $id);
        }
   //     $users = $this->User->find('all', array('order' => 'User.nombres_persona', 'conditions' => array('User.empresa_id' => $regional['Empresa']['id'], 'estado' => true)));
   //     $empresasAprobadores = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.*', 'conditions' => array('EmpresasAprobadore.regional_id' => $regional['Regionale']['id'], 'EmpresasAprobadore.empresa_id' => $regional['Empresa']['id'])));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'id', 'conditions' => array('regional_id' => $id)));
        if (count($sucursales) == 0) {
            $this->Session->setFlash(__('Es necesario que primero cree las sucursales asociadas a esta regional para asignar los permisos correctamente', true));
        }
        /* print_r($sucursales);
          echo count($sucursales); */
   //     $this->set('users', $users);
   //     $this->set('empresasAprobadores', $empresasAprobadores);

        if (!empty($this->data)) {
            /* 
            $delete = "DELETE FROM empresas_aprobadores WHERE regional_id = " . $id . ";";
            $this->EmpresasAprobadore->query($delete);

            foreach ($users as $value) {
                if ($this->data['Regionale']['apc_' . $value['User']['id']] > 0) {
                    foreach ($sucursales as $sucursal) {
                        $this->EmpresasAprobadore->create();
                        $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                            'user_id' => $this->data['Regionale']['apc_' . $value['User']['id']],
                            'tipo_pedido_id' => '1',
                            'sucursal_id' => $sucursal,
                            'regional_id' => $id);
                        $this->EmpresasAprobadore->save($datosAprobador, false);
                    }
                }
                if ($this->data['Regionale']['io_' . $value['User']['id']] > 0) {
                    foreach ($sucursales as $sucursal) {
                        $this->EmpresasAprobadore->create();
                        $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                            'user_id' => $this->data['Regionale']['io_' . $value['User']['id']],
                            'tipo_pedido_id' => '2',
                            'sucursal_id' => $sucursal,
                            'regional_id' => $id);
                        $this->EmpresasAprobadore->save($datosAprobador, false);
                    }
                }
                if ($this->data['Regionale']['drrhh_' . $value['User']['id']] > 0) {
                    foreach ($sucursales as $sucursal) {
                        $this->EmpresasAprobadore->create();
                        $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                            'user_id' => $this->data['Regionale']['drrhh_' . $value['User']['id']],
                            'tipo_pedido_id' => '3',
                            'sucursal_id' => $sucursal,
                            'regional_id' => $id);
                        $this->EmpresasAprobadore->save($datosAprobador, false);
                    }
                }
            }
            */
            /*
            $users2 = $this->User->find('all', array('order' => 'User.nombres_persona', 'conditions' => array('empresa_id' => '1', 'estado' => true)));
            foreach ($users2 as $value) {
                foreach ($sucursales as $sucursal) {

                    // PARA TODOS LOS DE CISE 
                    $this->EmpresasAprobadore->create();
                    $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                        'user_id' => $value['User']['id'],
                        'tipo_pedido_id' => '1',
                        'sucursal_id' => $sucursal,
                        'regional_id' => $id);
                    $this->EmpresasAprobadore->save($datosAprobador, false);
                }
                foreach ($sucursales as $sucursal) {

                    // PARA TODOS LOS DE CISE 
                    $this->EmpresasAprobadore->create();
                    $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                        'user_id' => $value['User']['id'],
                        'tipo_pedido_id' => '2',
                        'sucursal_id' => $sucursal,
                        'regional_id' => $id);
                    $this->EmpresasAprobadore->save($datosAprobador, false);
                }
                foreach ($sucursales as $sucursal) {

                    // PARA TODOS LOS DE CISE 
                    $this->EmpresasAprobadore->create();
                    $datosAprobador = array('empresa_id' => $regional['Empresa']['id'],
                        'user_id' => $value['User']['id'],
                        'tipo_pedido_id' => '3',
                        'sucursal_id' => $sucursal,
                        'regional_id' => $id);
                    $this->EmpresasAprobadore->save($datosAprobador, false);
                }
            } */

            //**//
            if ($this->Regionale->save($this->data)) {
                $this->Session->setFlash(__('La regional se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index/' . $this->Session->read('Regional.empresa_id'))); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Regionale->read(null, $id);
        }

        // $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        // $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        // $this->set(compact('empresas', 'tipo_pedido'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Regional invalida.', true));
            $this->redirect(array('action' => 'index')); /* /header("Location: index"); */
        }
        if ($id > 0) {
            $estado = $this->Regionale->find('first', array('fields' => 'Regionale.estado_regional', 'conditions' => array('Regionale.id' => $id)));
            if ($estado['Regionale']['estado_regional']) {
                $this->Regionale->updateAll(array("Regionale.estado_regional" => 'false'), array("Regionale.id" => $id));
                $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'false'), array("Sucursale.regional_id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO de la regional. Las sucursales asociadas a esta regional se han inactivado', true));
                $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
            } else {
                $this->Regionale->updateAll(array("Regionale.estado_regional" => 'true'), array("Regionale.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO de la regional.', true));
                /* $this->redirect(array('action' => 'index')); */header("Location: index");
            }
        }
        $this->Session->setFlash(__('La regional no fue encontrada.', true));
        $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
    }

}

?>
