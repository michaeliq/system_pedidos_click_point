<?php

class Ruta extends AppModel
{

    var $name = 'Ruta';
    var $primaryKey = 'ruta_id';
    var $order = 'Ruta.ruta_id';
    var $validate = array(
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

    var $hasMany = array(
        'LocalidadRelRuta' => array(
            'className' => 'LocalidadRelRuta',
            'foreignKey' => 'id',
        ),
    );


}

class TempRuta extends AppModel
{

    var $name = 'TempRuta';
    var $primaryKey = 'ruta_id';
    var $order = 'TempRuta.ruta_id';
    var $validate = array(
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

