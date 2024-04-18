<?php

class PedidosDetalle extends AppModel {

    var $name = 'PedidosDetalle';
    var $useTable = 'pedidos_detalles';
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
        'cantidad_pedido' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '6'),
                'message' => 'Este campo tiene un limite de caracteres (6).',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
    );
    var $belongsTo = array(
        'Pedido' => array(
            'className' => 'Pedido',
            'foreignKey' => 'pedido_id',
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
            'foreignKey' => 'tipo_categoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => '',
            'conditions' => 'Pedido.empresa_id = Empresa.id',
            'fields' => '',
            'order' => ''
        ),
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => '',
            'conditions' => 'Pedido.sucursal_id = Sucursale.id',
            'fields' => '',
            'order' => ''
        ),
        'EstadoPedido' => array(
            'className' => 'EstadoPedido',
            'foreignKey' => '',
            'conditions' => 'Pedido.pedido_estado_pedido = EstadoPedido.id',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => '',
            'conditions' => 'Pedido.user_id = User.id',
            'fields' => 'nombres_persona,no_identificacion_persona,celular_persona',
            'order' => ''
        ),
        'Departamento' => array(
            'className' => 'Departamento',
            'foreignKey' => '',
            'conditions' => 'Sucursale.departamento_id = Departamento.id',
            'fields' => '',
            'order' => ''
        ),
        'Municipio' => array(
            'className' => 'Municipio',
            'foreignKey' => '',
            'conditions' => 'Sucursale.municipio_id = Municipio.id',
            'fields' => '',
            'order' => ''
        ),
        'TipoPedido' => array(
            'className' => 'TipoPedido',
            'foreignKey' => '',
            'conditions' => 'Pedido.tipo_pedido_id = TipoPedido.id',
            'fields' => '',
            'order' => ''
        ),
        'TipoMovimiento' => array(
            'className' => 'TipoMovimiento',
            'foreignKey' => '',
            'conditions' => 'Pedido.tipo_movimiento_id = TipoMovimiento.id',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>