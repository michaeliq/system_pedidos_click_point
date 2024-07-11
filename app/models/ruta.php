<?php

class Ruta extends AppModel
{

    var $name = 'Ruta';
    var $primaryKey = 'ruta_id';
    var $order = 'Ruta.ruta_id';
    var $validate = array(
        'codigo_sirbe' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'El CODIGO_SIRBE debe ser unico',
            )
        ),
        'nombre' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '50'),
                'message' => 'Este campo tiene un limite de caracteres (50).',
            ),
        ),
        'archivado' => array(
            'boolean'
        ),
        'created' => array(
            'datetime'
        ),
        'modified' => array(
            'datetime'
        ),
    );

    var $hasOne = array(
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => 'id',
        ),
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => 'id',
        ),
    );

    var $belongsTo = array(
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursale_id',
        ),
    );
}

class TempRuta extends AppModel
{

    var $name = 'TempRuta';
    var $primaryKey = 'ruta_id';
    var $order = 'TempRuta.ruta_id';
    var $validate = array(
        'codigo_sirbe' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'El CODIGO_SIRBE debe ser unico',
            )
        ),
        'nombre' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '50'),
                'message' => 'Este campo tiene un limite de caracteres (50).',
            ),
        ),
        'to_create' => array(
            'boolean'
        ),
        'created' => array(
            'datetime'
        ),
        'modified' => array(
            'datetime'
        ),
    );

    var $hasOne = array(
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => 'id',
        ),
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => 'id',
        ),
    );
}
