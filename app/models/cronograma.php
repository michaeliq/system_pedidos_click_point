<?php

class Cronograma extends AppModel {

    var $name = 'Cronograma';
    var $validate = array(
        'nombre_cronograma' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '60'),
                'message' => 'Este campo tiene un limite de caracteres (60).',
            ),
        ),
        'empresa_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'tipo_pedido_id_2' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'fecha_inicio' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'date' => array(
                'rule' => array('date'),
                'message' => 'Debe seleccionar una fecha valida para el campo.',
            ),
        ),
        'fecha_fin' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'date' => array(
                'rule' => array('date'),
                'message' => 'Debe seleccionar una fecha valida para el campo.',
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
        'TipoPedido' => array(
            'className' => 'TipoPedido',
            'foreignKey' => 'tipo_pedido_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>
