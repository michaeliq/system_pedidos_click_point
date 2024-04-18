<?php

class Producto extends AppModel {

    var $name = 'Producto';
    var $useTable = 'productos';
    var $virtualFields = array(
    //     'producto_completo' => "codigo_producto||' | '||nombre_producto",
        'producto_completo' => "codigo_producto||' | '||nombre_producto||' | '||especificacion_tecnica"
    );
    var $order = 'tipo_categoria_id';
    var $validate = array(
        'codigo_producto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '20'),
                'message' => 'Este campo tiene un limite de caracteres (20).',
            ),
        ),
        'nombre_producto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'maxlength' => array(
                'rule' => array('maxlength', '120'),
                'message' => 'Este campo tiene un limite de caracteres (120).',
            ),
        ),
        'tipo_categoria_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'precio_producto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo solo acepta valores numéricos.',
            ),
        ),
        'iva_producto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
            'numeric' => array(
                'rule' => array('decimal', 2),
                'message' => 'Este campo debe tener minimo dos decimales (separador punto). En caso de ser cero, coloque (0.00)',
            ),
        ),
        'medida_producto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
        'multiplo' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo debe ser númerico',
            ),
        ),
    );
    var $belongsTo = array(
        'TipoCategoria' => array(
            'className' => 'TipoCategoria',
            'foreignKey' => 'tipo_categoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>