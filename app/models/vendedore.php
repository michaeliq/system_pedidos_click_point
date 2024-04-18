<?php

class Vendedore extends AppModel {

    var $name = 'Vendedore';
    var $validate = array(
        'nombre_vendedor' => array(
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
        'no_identificacion' => array(
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
        'direccion_vendedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'telefono_vendedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'correo_vendedor' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
    );

}
