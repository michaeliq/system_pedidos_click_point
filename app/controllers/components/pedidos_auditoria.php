<?php

class PedidosAuditoriaComponent extends Object {

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

    function AuditoriaCambioEstado($Pedido, $PedidoEstado, $UserId, $Observaciones = null) {
        // PedidosAudit
        $Auditoria = ClassRegistry::init('PedidosAudit');

        $detalle_auditoria = array(
            'pedido_id' => $Pedido,
            'pedido_estado_pedido' => $PedidoEstado,
            'fecha_cambio_estado' => date('Y-m-d H:i:s'),
            'user_id' => $UserId,
            'observaciones' => $Observaciones
        );
        $Auditoria->save($detalle_auditoria, FALSE);
    }

}
