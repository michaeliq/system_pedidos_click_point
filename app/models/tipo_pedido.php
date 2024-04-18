<?php

class TipoPedido extends AppModel {

    var $name = 'TipoPedido';
    var $useTable = 'tipo_pedidos';
    var $order = 'id ASC';
    var $validate = array(
        'nombre_tipo_pedido' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio',
            )
        )
    );

}
?>
