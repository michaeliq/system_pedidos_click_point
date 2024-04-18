<?php

class PlantillasDetalle extends AppModel {

    var $name = 'PlantillasDetalle';
    var $useTable = 'plantillas_detalles';
    var $validate = array(
        'plantilla_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'producto_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );
    var $belongsTo = array(
        'Plantilla' => array(
            'className' => 'Plantilla',
            'foreignKey' => 'plantilla_id',
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
        ),
        'TipoCategoria' => array(
            'className' => 'TipoCategoria',
            'foreignKey' => '',
            'conditions' => 'Producto.tipo_categoria_id = TipoCategoria.id',
            'fields' => '',
            'order' => ''
        ),
        'TipoPedido' => array(
            'className' => 'TipoPedido',
            'foreignKey' => '',
            'conditions' => 'Plantilla.tipo_pedido_id = TipoPedido.id',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>