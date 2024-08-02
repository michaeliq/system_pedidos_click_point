<?php

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
        $text = $this->Tools->execPythonPDFReader("op_000112310.pdf", "despacho/");
        $list_text = explode("\n",$text);
        debug($list_text);
        if(strpos($list_text[8],"1461")){
            debug("Se encontro");
        }else{
            debug("No se encontro");
        }
    }

}

?>
