<?php

class Pedido extends AppModel {

    var $name = 'Pedido';
    var $validate = array(
        'sucursal_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'tipo_pedido_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'tipo_pedido_id' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
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
        'EmpresasAprobadore' => array(
            'className' => 'EmpresasAprobadore',
            'foreignKey' => '',
            //'conditions' => 'EmpresasAprobadore.empresa_id = Empresa.id AND EmpresasAprobadore.tipo_pedido_id = Pedido.tipo_pedido_id AND EmpresasAprobadore.sucursal_id = Pedido.sucursal_id', //31052018
            'conditions' => 'EmpresasAprobadore.empresa_id = Empresa.id AND EmpresasAprobadore.sucursal_id = Pedido.sucursal_id', //31052018
            'fields' => '',
            'order' => ''
        ),
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursal_id',
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
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => 'municipio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => '',
            'conditions' => 'Municipio.departamento_id = Departamento.id',
            'fields' => '',
            'order' => ''
        ),
        'Municipio2' => array(
            'className' => 'Municipio',
            'foreignKey' => '',
            'conditions' => 'Sucursale.municipio_id = Municipio2.id',
            'fields' => '',
            'order' => ''
        ),
        'Departamento2' => array(
            'className' => 'Departamento',
            'foreignKey' => '',
            'conditions' => 'Sucursale.departamento_id = Departamento2.id',
            'fields' => '',
            'order' => ''
        ),
        'TipoPedido' => array(
            'className' => 'TipoPedido',
            'foreignKey' => 'tipo_pedido_id',
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