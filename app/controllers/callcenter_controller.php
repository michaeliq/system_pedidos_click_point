<?php

class CallcenterController extends AppController {

    var $name = "Callcenter";
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('ListadoLlamada', 'ListadoLlamadasDetalle');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Callcenter', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Callcenter', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $conditions = array();
        $where = "+ListadoLlamada+.+fecha_registro+::date BETWEEN now()::date - interval '10 day' AND now()::date AND +ListadoLlamada+.+user_id+ = " . $this->Session->read('Auth.User.id') . " AND +ListadoLlamadasDetalle+.+click_cotizacion+ = true";
        $where = str_replace('+', '"', $where);
        array_push($conditions, $where);
        $pendiente_cotizacion_cliente = $this->ListadoLlamadasDetalle->find('all', array('conditions' => $conditions));
        $this->set('cotizacions', $pendiente_cotizacion_cliente);

        $conditions = array();
        $where = "+ListadoLlamada+.+fecha_registro+::date BETWEEN now()::date - interval '5 day' AND now()::date AND +ListadoLlamada+.+user_id+ = " . $this->Session->read('Auth.User.id') . " AND +ListadoLlamada+.+agendada+ = true";
        $where = str_replace('+', '"', $where);
        array_push($conditions, $where);
        $pendiente_agenda_cliente = $this->ListadoLlamada->find('all', array('conditions' => $conditions,'order'=>'ListadoLlamada.fecha_registro asc'));
        $this->set('agendas', $pendiente_agenda_cliente);
    }

    function completar_click_cotizacion($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Incorrecto id para listado llamada', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.click_cotizacion" => "false"), array("ListadoLlamadasDetalle.id" => base64_decode($id)))) {
            $this->Session->setFlash(__('Envío de información gestionado y completado', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Listado llamada error', true));
        $this->redirect(array('action' => 'index'));
    }

    function completar_click_agendar($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Incorrecto id para listado llamada', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->ListadoLlamadasDetalle->updateAll(array("ListadoLlamadasDetalle.click_agendar" => "false"), array("ListadoLlamadasDetalle.id" => base64_decode($id)))) {
            $this->Session->setFlash(__('Agenda gestionada y completada', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Listado llamada error', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
