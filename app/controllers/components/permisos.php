<?php

class PermisosComponent extends Object {

    //llamado antes de  Controller::beforeFilter()
    function initialize(&$controller) {
        // salvando la referencia al controlador para uso posterior
        $this->controller = & $controller;
    }

    //llamado tras Controller::beforeFilter()
    function startup(&$controller) {
        
    }

    function redirectSomewhere($value) {
        // ulizando un método de controlador
        $this->controller->redirect($value);
    }

    function Allow($Controller, $User_id) {
        $Instancia = ClassRegistry::init('MenuUser');

        $authorize = array();
        $allows = $Instancia->find('all', array('fields' => array(
            'MenuAction.menus_actions_accion'), 
            'conditions' => array('MenuUser.rol_id' => $User_id, 
            'Menu.menu_controller' => $Controller, 
            'MenuUser.allow_deny' => TRUE)));
        foreach ($allows as $allow) :
            array_push($authorize, $allow['MenuAction']['menus_actions_accion']);
        endforeach;

        return $authorize;
    }

    function Deny($Controller, $User_id) {
        $Instancia = ClassRegistry::init('MenuUser');

        $no_authorize = array();
        $denys = $Instancia->find('all', array('fields' => array('MenuAction.menus_actions_accion'), 'conditions' => array('MenuUser.rol_id' => $User_id, 'Menu.menu_descripcion' => $Controller, 'MenuUser.allow_deny' => FALSE)));
        foreach ($denys as $deny) :
            array_push($no_authorize, $deny['MenuAction']['menus_actions_accion']);
        endforeach;

        return $no_authorize;
    }

    function RolesInternos() {
        $Instancia = ClassRegistry::init('Role');
        $internos = $Instancia->find('list', array('fields' => array('Role.rol_id'), 'conditions' => array('Role.rol_interno_externo' => true)));
        return $internos;
    }

}

?>
