<?php

/**
 * Register Calendar by Request Seller Operations. Extends from AppController Cake Class
 * @method void isAuthorized() isAuthorized() validate user's priviliges
 * @method void index() index() show Calendar records
 * @method void add() add() allow to save new Calendar record
 * @method void edit() edit(int id) allow to edit Calendar data
 * @method void delete() delete(int id) change available status on Calendar record 
 */

class CronogramasController extends AppController {

    var $name = 'Cronogramas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Cronograma', 'Empresa', 'TipoPedido');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Cronogramas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Cronogramas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    /**
     * Show Calendar records
     * @return void
     */
    function index() {
        if ($this->Session->read('Auth.User.id') == '97')
            phpinfo();
        /* if ($this->Session->read('Auth.User.empresa_id') != '1') {
          $conditions = array('Cronograma.id' => $this->Session->read('Auth.User.empresa_id'), 'Cronograma.estado_cronograma' => true);
          } else {
          $conditions = array('Cronograma.estado_cronograma' => true);
          } */
        $this->Cronograma->set($this->data);
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Cronograma']['empresa_id'])) {
                $where = "+Cronograma+.+empresa_id+ = '" . $this->data['Cronograma']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Cronograma']['tipo_pedido_id'])) {
                $where = "+Cronograma+.+tipo_pedido_id+ = '" . $this->data['Cronograma']['tipo_pedido_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['Cronograma']['estado_cronograma']) {
                $where = "+Cronograma+.+estado_cronograma+ = " . $this->data['Cronograma']['estado_cronograma'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->Cronograma->recursive = 0;
            $this->paginate = array('limit' => 50, 'order' => array('Cronograma.estado_cronograma' => 'desc'));
            $this->set('cronogramas', $this->paginate($conditions));
        } else {
            $conditions = array('Cronograma.estado_cronograma' => true);
            $this->paginate = array('limit' => 50, 'order' => array('Cronograma.estado_cronograma, empresa_id' => 'desc'));
            $this->Cronograma->recursive = 0;
            $this->set('cronogramas', $this->paginate($conditions));
        }

        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true, 'Empresa.parametro_cronograma' => true)));
        $this->set(compact('empresas', 'tipo_pedido', 'estados'));
    }

    /**
     * Add Calendar record
     * @return void
     */
    function add() {
        if (!empty($this->data)) {

            $this->data['Cronograma']['tipo_pedido_id'] = 1;
            $this->data['Cronograma']['tipo_pedido_id_2'] = implode(",", $this->data['Cronograma']['tipo_pedido_id_2']);
            $this->Cronograma->create();
            if ($this->Cronograma->save($this->data)) {
                $this->Session->setFlash(__('Se ha creado el cronograma ' . $this->data['Cronograma']['nombre_cronograma'] . '. ', true));
                $this->redirect(array('action' => 'index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('El cronograma no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true, 'Empresa.parametro_cronograma' => true)));
        $this->set(compact('empresas', 'tipo_pedido'));
    }
    function add_masive_schedule() {
        if (!empty($this->data)) {

            /* debug($this->data);
            return; */

            $valid_insertion = 0;
            $error_insertion = 0;
            $empresas_validas = "Se agrego un cronograma de pedidos a las Empresas con IDs: ";
            $empresas_error = "No se agrego cronograma de pedidos a las Empresas con IDs: ";

            foreach ($this->data['Cronograma']['empresa_id'] as $empresa):
                $this->Cronograma->create();
                $new_schedule = array(
                    "Cronograma" => array(
                        "nombre_cronograma" => $this->data["Cronograma"]["nombre_cronograma"],
                        "empresa_id" => $empresa,
                        "tipo_pedido_id" => 1,
                        "tipo_pedido_id_2" => implode(",", $this->data['Cronograma']['tipo_pedido_id_2']),
                        "fecha_inicio" => $this->data["Cronograma"]["fecha_inicio"],
                        "fecha_fin" => $this->data["Cronograma"]["fecha_fin"],
                        "estado_cronograma" => true,
                    )
                    );
                if($this->Cronograma->save($new_schedule)){
                    $empresas_validas .= $empresa . ", ";
                    $valid_insertion += 1; 
                }else{
                    $empresas_error .= $empresa . ", ";
                    $error_insertion += 1; 
                }

            endforeach;

            
            if($error_insertion <= 0 && $valid_insertion > 0) {
                $this->Session->setFlash(__($empresas_validas, true));
                $this->redirect(array('action' => 'index')); 
            } else if($error_insertion > 0 && $valid_insertion > 0){
                $this->Session->setFlash(__($empresas_validas . "<br/>" . $empresas_error, true));
                 $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El cronograma no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true, 'Empresa.parametro_cronograma' => true)));
        $this->set(compact('empresas', 'tipo_pedido'));
    }

    /**
     * Edit Calendar record by Id
     * @return void
     */
    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró el cronograma', true));
            $this->redirect(array('action' => 'index')); /* header("Location: index"); */
        }
        if (!empty($this->data)) {
            $this->data['Cronograma']['tipo_pedido_id_2'] = implode(",", $this->data['Cronograma']['tipo_pedido_id_2']);
            if ($this->Cronograma->save($this->data)) {
                $this->Session->setFlash(__('El cronograma se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Cronograma->read(null, $id);
            $selected = explode(',', $this->data['Cronograma']['tipo_pedido_id_2']);
            $this->set('selected', $selected);
        }

        $tipo_pedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $this->set(compact('empresas', 'tipo_pedido'));
    }

    /**
     * Change Calendar Status records by Id
     * @return void
     */
    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Cronograma invalida.', true));
            $this->redirect(array('action' => 'index')); /* /header("Location: index"); */
        }
        if ($id > 0) {
            $estado = $this->Cronograma->find('first', array('fields' => 'Cronograma.estado_cronograma', 'conditions' => array('Cronograma.id' => $id)));
            if ($estado['Cronograma']['estado_cronograma']) {
                $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'false'), array("Cronograma.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO del cronograma.', true));
                $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
            } else {
                $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'true'), array("Cronograma.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO del cronograma.', true));
                /* $this->redirect(array('action' => 'index')); */header("Location: index");
            }
        }
        $this->Session->setFlash(__('El cronograma no fue encontrada.', true));
        $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
    }

}

?>
