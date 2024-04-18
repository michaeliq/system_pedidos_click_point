<?php

class Proveedore extends AppModel {

    var $name = 'Proveedore';
    var $validate = array(
        'nombre_proveedor' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'nit_proveedor' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'persona_contacto' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'direccion_proveedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'telefono_proveedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '30'),
                'message' => 'Este campo tiene un limite de caracteres (30).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email_proveedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Este campo no debe estar vacio.',
                'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'estado' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                'message' => 'Este campo no debe estar vacio.',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => 'municipio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoRegimene' => array(
            'className' => 'TipoRegimene',
            'foreignKey' => 'tipo_regimene_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoFormasPago' => array(
            'className' => 'TipoFormasPago',
            'foreignKey' => 'tipo_formas_pago_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
