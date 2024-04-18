<?php

class Cotizacion extends AppModel {

    var $name = 'Cotizacion';
    var $useTable = 'cotizacion';
    var $validate = array(
        'listado_llamada_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'cotizacion_estado_pedido' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'cotizacion_email' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Debe tener un formato valido de e-mail.',
            ),
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'ListadoLlamada' => array(
            'className' => 'ListadoLlamada',
            'foreignKey' => 'listado_llamada_id',
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
        'EstadoPedido' => array(
            'className' => 'EstadoPedido',
            'foreignKey' => 'cotizacion_estado_pedido',
            'conditions' => '',
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
        'BdCliente' => array(
            'className' => 'BdCliente',
            'foreignKey' => 'bd_cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /* var $hasAndBelongsToMany = array(
      'Detalle' => array(
      'className' => 'Detalle',
      'joinTable' => 'cotizacion_detalles',
      'foreignKey' => 'cotizacion_id',
      'associationForeignKey' => 'detalle_id',
      'unique' => true,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'finderQuery' => '',
      'deleteQuery' => '',
      'insertQuery' => ''
      )
      ); */
}
