<?php

class Solicitud extends AppModel {

    var $name = 'Solicitud';
    var $useTable = 'solicitudes';
    var $validate = array(
        'tipo_solicitud_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'detalles_solicitud' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'pedido_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Esta campo debe contener un número de pedido',
                'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'motivo_asunto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '100'),
                'message' => 'Este campo tiene un limite de caracteres (100).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    var $belongsTo = array(
        'TipoSolicitude' => array(
            'className' => 'TipoSolicitude',
            'foreignKey' => 'tipo_solicitud_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoEstadoSolicitud' => array(
            'className' => 'TipoEstadoSolicitud',
            'foreignKey' => 'tipo_estado_solicitud_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoMotivosSolicitud' => array(
            'className' => 'TipoMotivosSolicitud',
            'foreignKey' => 'tipo_motivo_solicitud_id',
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
        'UserAsignado' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_asignado',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
