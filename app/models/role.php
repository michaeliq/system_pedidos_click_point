<?php

class Role extends AppModel {

    var $name = 'Role';
    var $primaryKey = 'rol_id';
    var $order = 'Role.rol_id';
    var $validate = array(
        'rol_nombre' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo no debe estar vacio.',
            ),
        ),
    );

}

?>
