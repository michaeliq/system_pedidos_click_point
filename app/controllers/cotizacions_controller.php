<?php

class CotizacionsController extends AppController {

    var $name = 'Cotizacions';

    function index() {
        $this->Cotizacion->recursive = 0;
        $this->paginate = array('limit' => 100, 'order' => array('Cotizacion.fecha_cotizacion' => 'desc'));
        $conditions = array();

        if (!empty($this->data)) {
            if (!empty($this->data['Cotizacion']['bd_razon_social'])) {
                $where = "+BdCliente+.+bd_razon_social+ ILIKE '%" . $this->data['Cotizacion']['bd_razon_social'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Cotizacion']['bd_email'])) {
                $where = "+BdCliente+.+bd_email+ ILIKE '%" . $this->data['Cotizacion']['bd_email'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Cotizacion']['bd_telefonos'])) {
                $where = "+BdCliente+.+bd_telefonos+ ILIKE '%" . $this->data['Cotizacion']['bd_telefonos'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Cotizacion']['fecha_cotizacion'])) {
                $where = "+Cotizacion+.+fecha_inicio+::date = '" . $this->data['Cotizacion']['fecha_inicio'] . "'::date";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
        }
        $this->set('cotizacions', $this->paginate($conditions));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid cotizacion', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('cotizacion', $this->Cotizacion->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Cotizacion->create();
            if ($this->Cotizacion->save($this->data)) {
                $this->Session->setFlash(__('The cotizacion has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The cotizacion could not be saved. Please, try again.', true));
            }
        }
        $listadoLlamadasDetalles = $this->Cotizacion->ListadoLlamadasDetalle->find('list');
        $users = $this->Cotizacion->User->find('list');
        $estadoPedidos = $this->Cotizacion->EstadoPedido->find('list');
        $tipoPedidos = $this->Cotizacion->TipoPedido->find('list');
        $this->set(compact('listadoLlamadasDetalles', 'users', 'estadoPedidos', 'tipoPedidos'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid cotizacion', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Cotizacion->save($this->data)) {
                $this->Session->setFlash(__('The cotizacion has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The cotizacion could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Cotizacion->read(null, $id);
        }
        $listadoLlamadasDetalles = $this->Cotizacion->ListadoLlamadasDetalle->find('list');
        $users = $this->Cotizacion->User->find('list');
        $estadoPedidos = $this->Cotizacion->EstadoPedido->find('list');
        $tipoPedidos = $this->Cotizacion->TipoPedido->find('list');
        $this->set(compact('listadoLlamadasDetalles', 'users', 'estadoPedidos', 'tipoPedidos'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for cotizacion', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Cotizacion->delete($id)) {
            $this->Session->setFlash(__('Cotizacion deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Cotizacion was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}
