<?php

class MenuUser extends AppModel {

    var $name = 'MenuUser';
    var $primaryKey = 'id';
    var $useTable = 'menus_users';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Menu' => array(
            'className' => 'Menu',
            'foreignKey' => 'menus_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'MenuAction' => array(
            'className' => 'MenuAction',
            'foreignKey' => 'menus_actions_id',
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
    );

}

?>
