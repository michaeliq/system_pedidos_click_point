<?php

/**
 * Main Class to display general options to setting administrative rules. Extends from AppController Cake Class
 * @method Authorized validate user's priviliges
 * @method index show config options
 */

class AdministracionController extends AppController {

    var $name = "Administracion";
    var $components = array('RequestHandler', 'Auth', 'Permisos','Tools');
    var $uses = array('Ajuste');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Administracion', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Administracion', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        
    }

}

?>
