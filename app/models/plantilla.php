<?php

class Plantilla extends AppModel {

    var $name = 'Plantilla';
    var $useTable = 'plantillas';
    var $virtualFields = array(
        'plantilla_tipo' => "TipoPedido.nombre_tipo_pedido||' - '||nombre_plantilla"
    );
    var $validate = array(
        'nombre_plantilla' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '60'),
                'message' => 'Este campo tiene un limite de caracteres (60).',
            ),
        ),
        'tipo_pedido_id' => array(
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
        'detalle_planilla' => array(
            'maxlength' => array(
                'rule' => array('maxlength', '1000'),
                'message' => 'Este campo tiene un limite de caracteres (1000).',
            ),
        ),
    );
    var $belongsTo = array(
        'TipoPedido' => array(
            'className' => 'TipoPedido',
            'foreignKey' => 'tipo_pedido_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
	'Regionale' => array(
            'className' => 'Regionale',
            'foreignKey' => 'sucursal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),

    );

}

?>