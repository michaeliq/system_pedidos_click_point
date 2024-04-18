<?php

class PedidosAudit extends AppModel {

    var $name = 'PedidosAudit';
    var $useTable = 'pedidos_audit';
    var $belongsTo = array(
        'Pedido' => array(
            'className' => 'Pedido',
            'foreignKey' => 'pedido_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EstadoPedido' => array(
            'className' => 'EstadoPedido',
            'foreignKey' => 'pedido_estado_pedido',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
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