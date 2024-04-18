<?php

class MenuAction extends AppModel {

    var $name = 'MenuAction';
    var $primaryKey = 'id';
    var $useTable = 'menus_actions';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Menu' => array(
            'className' => 'Menu',
            'foreignKey' => 'menu_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}

?>
