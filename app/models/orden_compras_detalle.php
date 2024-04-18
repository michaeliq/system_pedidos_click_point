<?php

class OrdenComprasDetalle extends AppModel {

    var $name = 'OrdenComprasDetalle';
    var $useTable = 'orden_compras_detalles';
    var $validate = array(
        'producto_id2' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            ),
        ),
        'cantidad_orden' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '5'),
                'message' => 'Este campo tiene un limite de caracteres (5).',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
    );
    var $belongsTo = array(
        'OrdenCompra' => array(
            'className' => 'OrdenCompra',
            'foreignKey' => 'orden_compra_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Producto' => array(
            'className' => 'Producto',
            'foreignKey' => 'producto_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}

?>