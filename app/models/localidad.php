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
        'Ruta' => array(
            'className' => 'Ruta',
            'foreignKey' => 'ruta_id',
        ),
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'id',
        ),
    );
}

?>