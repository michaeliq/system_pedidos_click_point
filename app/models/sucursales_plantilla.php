<?php

class SucursalesPlantilla extends AppModel {

    var $name = 'SucursalesPlantilla';
    var $belongsTo = array(
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursale_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Plantilla' => array(
            'className' => 'Plantilla',
            'foreignKey' => 'plantilla_id',
            'conditions' => '',
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
