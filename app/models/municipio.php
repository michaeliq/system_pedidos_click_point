<?php

class Municipio extends AppModel {

    var $name = 'Municipio';
    
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => 'departamento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}

?>
