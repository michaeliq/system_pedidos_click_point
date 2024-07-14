<?php

class Localidad extends AppModel
{

    var $name = 'Localidad';
    var $useTable = 'localidades';
    var $primaryKey = 'localidad_id';
    var $order = 'Localidad.localidad_id';
    var $validate = array(
        'nombre_localidad' => array(
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

    var $hasMany = array(
        'LocalidadRelRuta' => array(
            'className' => 'LocalidadRelRuta',
            'foreignKey' => 'id',
        ),
    );
}

class TempLocalidad extends AppModel
{

    var $name = 'TempLocalidad';
    var $useTable = 'temp_localidades';
    var $primaryKey = 'localidad_id';
    var $order = 'TempLocalidad.localidad_id';
    var $validate = array(
        'nombre_localidad' => array(
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
}

class LocalidadRelRuta extends AppModel
{
    var $name = 'LocalidadRelRuta';
    var $primaryKey = 'id';
    var $order = 'LocalidadRelRuta.id';
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
        'nombre_rel' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );

    var $belongsTo = array(
        'Ruta' => array(
            'className' => 'Ruta',
            'foreignKey' => 'ruta_id',
        ),
        'Localidad' => array(
            'className' => 'Localidad',
            'foreignKey' => 'localidad_id',
        ),
        'Sucursal' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'codigo_sirbe',
        ),
    );
}
