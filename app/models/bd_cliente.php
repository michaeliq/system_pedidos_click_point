<?php

class BdCliente extends AppModel {

    var $name = 'BdCliente';
    var $validate = array(
        'bd_razon_social' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'bd_email' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
        'bd_identificacion' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'bd_telefonos' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
    );

}
