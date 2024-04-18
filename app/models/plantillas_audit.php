<?php

class PlantillasAudit extends AppModel {

    var $name = 'PlantillasAudit';
    var $useTable = 'plantillas_audit';
    var $belongsTo = array(
      
        
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>