<?php

class TipoCategoria extends AppModel {

    var $name = 'TipoCategoria';
    var $useTable = 'tipo_categorias';
    var $order = 'tipo_categoria_descripcion ASC';
    var $validate = array(
        'tipo_categoria_descripcion' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio',
            )
        )
    );

}
?>
