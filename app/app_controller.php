<?php

class AppController extends Controller
{

    var $components = array('Auth', 'Session', 'Tools');

    function beforeFilter()
    {
        $this->Tools->existsTable();
        $this->Auth->loginError = "Usuario o Contraseña incorrecto. Por favor verifique sus datos.";

        $this->Auth->allow('pedido_pdf');
        $this->Auth->allow('reestablecer_contrasena2');

        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'perfil');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->authorize = 'controller';
    }

    function isAuthorized()
    {
        return true;
    }

    function beforeRender()
    {
        $menus = ClassRegistry::init('MenuUser')->find('all', array(
            'fields' => 'DISTINCT Menu.menu_orden, Menu.menu_controller',
            'order' => 'Menu.menu_orden',
            'conditions' => array(
                'Menu.menu_principal' => true,
                'MenuAction.menus_actions_ajax' => false,
                'MenuUser.rol_id' => $this->Session->read('Auth.User.rol_id'),
                'MenuUser.allow_deny' => TRUE
            )
        ));
        $this->set('menus', $menus);
    }
}
