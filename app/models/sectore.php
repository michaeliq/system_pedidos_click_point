<?php

class Sectore extends AppModel {

    var $name = 'Sectore';
    var $useTable = 'sectores';
    var $order = 'Sectore.id';
    var $validate = array(
        'nombre_sector' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );

}

?>
