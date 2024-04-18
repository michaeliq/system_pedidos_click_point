<?php

class ListadoLlamadasDetalle extends AppModel {

    var $name = 'ListadoLlamadasDetalle';
    var $virtualFields = array(
        'duracion' => "fecha_fin - fecha_inicio",
        'duracion_actual' => "fecha_fin - fecha_inicio"
    );
    var $validate = array(
        'listado_llamada_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'bd_cliente_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'ListadoLlamada' => array(
            'className' => 'ListadoLlamada',
            'foreignKey' => 'listado_llamada_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'BdCliente' => array(
            'className' => 'BdCliente',
            'foreignKey' => 'bd_cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cotizacion' => array(
            'className' => 'Cotizacion',
            'foreignKey' => '',
            'conditions' => 'ListadoLlamadasDetalle.listado_llamada_id = Cotizacion.listado_llamada_id',
            'fields' => '',
            'order' => ''
        )
    );

}
