<?php

class Sucursale extends AppModel {

    var $name = 'Sucursale';
    var $virtualFields = array(
        'v_regional_sucursal' => "Sucursale.regional_sucursal||' - '||Sucursale.nombre_sucursal"
    );
    var $validate = array(
        'nombre_sucursal' => array(
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
        'direccion_sucursal' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'telefono_sucursal' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'email_sucursal' => array(
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
    );
    var $belongsTo = array(
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'id_empresa',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Regionale' => array(
            'className' => 'Regionale',
            'foreignKey' => 'regional_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Ruta' => array(
            'className' => 'Ruta',
            'foreignKey' => 'ruta_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}

?>
