<?php

class EncuestasRespuesta extends AppModel {

    var $name = 'EncuestasRespuesta';
    var $belongsTo = array(
        'EncuestasPregunta' => array(
            'className' => 'EncuestasPregunta',
            'foreignKey' => 'encuestas_preguntas_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
