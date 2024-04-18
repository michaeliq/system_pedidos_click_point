<?php

class SolicitudesDetalle extends AppModel {

    var $name = 'SolicitudesDetalle';
    var $useTable = 'solicitudes_detalles';
    var $validate = array(
        'solicitud_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'detalle_observacion' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );
    var $belongsTo = array(
        'Solicitud' => array(
            'className' => 'Solicitud',
            'foreignKey' => 'solicitud_id',
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
        'TipoEstadoSolicitud1' => array(
            'className' => 'TipoEstadoSolicitud',
            'foreignKey' => 'tipo_estado_solicitud_id_1',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'TipoEstadoSolicitud2' => array(
            'className' => 'TipoEstadoSolicitud',
            'foreignKey' => 'tipo_estado_solicitud_id_2',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>