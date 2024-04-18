<?php

//31052018
class Regionale extends AppModel {

    var $name = 'Regionale';
    var $order = 'Regionale.id';
    var $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>
