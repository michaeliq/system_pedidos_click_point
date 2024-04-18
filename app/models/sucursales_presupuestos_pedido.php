<?php

class SucursalesPresupuestosPedido extends AppModel {

    var $name = 'SucursalesPresupuestosPedido';
    /* var $validate = array(
      'nombre_sucursal' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'maxlength' => array(
      'rule' => array('maxlength', '120'),
      'message' => 'Este campo tiene un limite de caracteres (20).',
      ),
      ),
      'departamento_id' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      ),
      'municipio_id' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      ),
      'direccion_sucursal' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'maxlength' => array(
      'rule' => array('maxlength', '120'),
      'message' => 'Este campo tiene un limite de caracteres (20).',
      ),
      ),
      'telefono_sucursal' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'maxlength' => array(
      'rule' => array('maxlength', '120'),
      'message' => 'Este campo tiene un limite de caracteres (20).',
      ),
      ),
      'email_sucursal' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'email' => array(
      'rule' => array('email'),
      'message' => 'Debe tener un formato valido de e-mail.',
      ),
      ),
      'nombre_contacto' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'maxlength' => array(
      'rule' => array('maxlength', '120'),
      'message' => 'Este campo tiene un limite de caracteres (20).',
      ),
      ),
      'telefono_contacto' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'maxlength' => array(
      'rule' => array('maxlength', '120'),
      'message' => 'Este campo tiene un limite de caracteres (20).',
      ),
      ),
      'email_contacto' => array(
      'notempty' => array(
      'rule' => array('notempty'),
      'message' => 'Este campo no debe estar vacio.',
      ),
      'email' => array(
      'rule' => array('email'),
      'message' => 'Debe tener un formato valido de e-mail.',
      ),
      ),
      ); */
    var $belongsTo = array(
        'Sucursale' => array(
            'className' => 'Sucursale',
            'foreignKey' => 'sucursal_id',
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => '',
            'conditions' => 'Empresa.id = Sucursale.id_empresa',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>
