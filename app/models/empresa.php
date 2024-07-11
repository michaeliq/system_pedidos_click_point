<?php

class Empresa extends AppModel {

    var $name = 'Empresa';
    var $validate = array(
        'contrato_empresa' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'nit_empresa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'nombre_empresa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'departamento_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'municipio_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'direccion_empresa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'telefono_empresa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'email_empresa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
        'nombre_contacto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),

        'telefono_contacto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'email_contacto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
        'restore_pass' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );
    var $belongsTo = array(
        'Sectore' => array(
            'className' => 'Sectore',
            'foreignKey' => 'sector_id',
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
        'Vendedore' => array(
            'className' => 'Vendedore',
            'foreignKey' => 'vendedor_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => 'municipio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => '',
            'conditions' => 'Municipio.departamento_id = Departamento.id',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>
