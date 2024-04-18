<?php

class EncuestasPregunta extends AppModel {

    var $name = 'EncuestasPregunta';
    var $belongsTo = array(
        'Encuesta' => array(
            'className' => 'Encuesta',
            'foreignKey' => 'encuesta_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
