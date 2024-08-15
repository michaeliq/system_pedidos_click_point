<?php

class User extends AppModel {

    var $name = 'User';
    var $useTable = 'users';
    var $order = 'username';

    function getLastUser($fields = null) {
        $params = array(
              'fields' => $fields,
              'order' => "User.id desc"
        );

        return $this->find('first',$params);
    }

    var $validate = array(
        'nombres_persona' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '80'),
                'message' => 'Este campo tiene un limite de caracteres (80).',
            ),
        ),

        'tipo_documento_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'no_identificacion_persona' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
          
        ),
        'tipo_sexo_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'fecha_nacimiento' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'date' => array(
                'rule' => array('date'),
                'message' => 'Debe seleccionar una fecha valida para el campo.',
            ),
        ),

        'municipio_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'direccion_residencia' => array(

            'maxlength' => array(
                'rule' => array('maxlength', '100'),
                'message' => 'Este campo tiene un limite de caracteres (100).',
            ),
        ),
        'telefono_residencia' => array(

            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'celular_persona' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'email_persona' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'rol_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
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
        'TipoDocumento' => array(
            'className' => 'TipoDocumento',
            'foreignKey' => 'tipo_documento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoSexo' => array(
            'className' => 'TipoSexo',
            'foreignKey' => 'tipo_sexo_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'rol_id',
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
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Asociado' => array(
            'className' => 'Asociado',
            'foreignKey' => 'asociado_id',
        )
    );



}

?>