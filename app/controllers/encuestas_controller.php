<?php

class EncuestasController extends AppController {

    var $name = 'Encuestas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Encuesta', 'Empresa', 'EncuestasPregunta', 'EncuestasDiligenciada');

    function index() {
        $this->Encuesta->set($this->data);
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Encuesta']['empresa_id'])) {
                $where = "+Encuesta+.+empresa_id+ = '" . $this->data['Encuesta']['empresa_id'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }


            $this->Encuesta->recursive = 0;
            $this->paginate = array('limit' => 50, 'order' => array('Encuesta.estado_encuesta' => 'desc'));
            $this->set('encuestas', $this->paginate($conditions));
        } else {
            $conditions = array();
            $this->paginate = array('limit' => 50, 'order' => array('Encuesta.estado_encuesta, empresa_id' => 'desc'));
            $this->Encuesta->recursive = 0;
            $this->set('encuestas', $this->paginate($conditions));
        }

        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $this->set(compact('empresas', 'tipo_pedido', 'estados'));
    }

    function preguntas($id) {
        if (!empty($id)) {
            $conditions = array('encuesta_id' => $id);
            $preguntas = $this->EncuestasPregunta->find('all', array('conditions' => $conditions, 'order' => 'EncuestasPregunta.orden_pregunta'));
            $this->set('preguntas', $preguntas);
        }
    }

    function diligenciar($id) {
        if (!empty($id)) {
            $encuestaDiligenciada = $this->EncuestasDiligenciada->find('first', array('fields' => 'id, encuesta_id', 'conditions' => array('EncuestasDiligenciada.pedido_id' => $id, 'EncuestasDiligenciada.estado_diligenciado' => '0')));
            if (!empty($encuestaDiligenciada['EncuestasDiligenciada']['encuesta_id'])) {
                $this->Session->write('Pedido.pedido_id', $id);
                $encuestaPreguntas = $this->EncuestasPregunta->find('all', array('order' => 'EncuestasPregunta.orden_pregunta', 'conditions' => array('EncuestasPregunta.encuesta_id' => $encuestaDiligenciada['EncuestasDiligenciada']['encuesta_id'])));
                $this->set('preguntas', $encuestaPreguntas);

                // ENVIO FORMULARIO
                if (!empty($this->data)) {
                    // encuestas_respuestas_id_1
                    foreach ($encuestaPreguntas as $value) {
                        if (!empty($_POST['encuestas_respuestas_id_' . $value['EncuestasPregunta']['id']])) {
                            $query = "INSERT INTO encuestas_respuestas (encuestas_preguntas_id, encuestas_respuestas_id, fecha_respuesta,encuestas_diligenciadas_id) VALUES (" . $value['EncuestasPregunta']['id'] . "," . $_POST['encuestas_respuestas_id_' . $value['EncuestasPregunta']['id']] . ",NOW()," . $encuestaDiligenciada['EncuestasDiligenciada']['id'] . ");";
                            $this->EncuestasDiligenciada->query($query);
                        }
                    }
                    $this->Session->setFlash(__('Se ha diligenciado completamente la encuesta.', true));
                    $this->EncuestasDiligenciada->updateAll(array("EncuestasDiligenciada.estado_diligenciado" => "1"), array("EncuestasDiligenciada.pedido_id" => $id));
                    $this->redirect(array('action' => '../pedidos/detalle_pedido/' . $id));
                }
            }else{
                $this->redirect(array('action' => '../pedidos/detalle_pedido/' . $id));
            }
        }

        $puntajes = array(
            '5' => 'EXCELENTE <span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>',
            '4' => 'BUENO <span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>',
            '3' => 'REGULAR <span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span> ',
            '2' => 'MALO <span class="glyphicon glyphicon-star"></span>',
            '1' => 'NO APLICA');
        $this->set('puntajes', $puntajes);
    }

}
