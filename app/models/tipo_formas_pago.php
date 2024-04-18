<?php

class TipoFormasPago extends AppModel {

    var $name = 'TipoFormasPago';
    var $virtualFields = array(
        'fecha_vencimiento' => "(now() + interval '1 day' * numero_dias)::date"
    );
    var $validate = array(
        'nombre_forma_pago' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $hasMany = array(
        'MovimientosEntrada' => array(
            'className' => 'MovimientosEntrada',
            'foreignKey' => 'tipo_formas_pago_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

}
