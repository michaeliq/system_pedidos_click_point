<?php

class EncuestasDiligenciada extends AppModel {

    var $name = 'EncuestasDiligenciada';
    var $belongsTo = array(
        'Encuesta' => array(
            'className' => 'Encuesta',
            'foreignKey' => 'encuesta_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Pedido' => array(
            'className' => 'Pedido',
            'foreignKey' => 'pedido_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
