<?php

class OrdenCompra extends AppModel {

    var $name = 'OrdenCompra';
    var $virtualFields = array(
        'orden_compra_completa' => "'#000'||OrdenCompra.id||' - '||OrdenCompra.fecha_orden_compra::date"
    );
    var $validate = array(
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'proveedor_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    var $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Proveedore' => array(
            'className' => 'Proveedore',
            'foreignKey' => 'proveedor_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoOrden' => array(
            'className' => 'TipoOrden',
            'foreignKey' => 'tipo_orden_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoEstadoOrden' => array(
            'className' => 'TipoEstadoOrden',
            'foreignKey' => 'tipo_estado_orden_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoFormasPago' => array(
            'className' => 'TipoFormasPago',
            'foreignKey' => 'tipo_formas_pago_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_elaboro',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User2' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_aprobo',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
