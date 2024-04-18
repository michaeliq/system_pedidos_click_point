<?php

class EmpresasAprobadore extends AppModel {

    var $name = 'EmpresasAprobadore';
    var $validate = array(
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'empresa_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
//        'tipo_pedido_id' => array(
//            'notempty' => array(
//                'rule' => array('notempty'),
//                'message' => 'Este campo no debe estar vacio.',
//            ),
//        ),
    );
    var $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ), //31052018
        'Regionale' => array(
            'className' => 'Regionale',
            'foreignKey' => 'regional_id',
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
//        'TipoPedido' => array(
//            'className' => 'TipoPedido',
//            'foreignKey' => 'tipo_pedido_id',
//            'conditions' => '',
//            'fields' => '',
//            'order' => ''
//        ),
    );

}

?>
