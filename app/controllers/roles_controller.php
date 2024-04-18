<?php

class RolesController extends AppController {

    var $name = 'Roles';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Role', 'User', 'Menu', 'MenuAction', 'MenuUser');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Roles', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Roles', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {

        $this->Role->recursive = 0;
        $this->helpers['Paginator'] = array('ajax' => 'Ajax');
        $this->set('roles', $this->paginate());

        /* $sql = "SELECT  pedidos.id, pedidos.pedido_fecha, 
          pedidos.pedido_estado_pedido,
          pedidos_detalles.*,
          productos.codigo_producto,
          productos.precio_producto,
          productos.precio_producto - pedidos_detalles.precio_producto diferecia,
          'UPDATE pedidos_detalles SET precio_producto = '||productos.precio_producto||' WHERE id = '||pedidos_detalles.id||';' sql_update
          FROM pedidos, pedidos_detalles, productos
          where pedidos.id = pedidos_detalles.pedido_id
          and pedidos_detalles.producto_id = productos.id
          and  pedidos_detalles.producto_id IN (SELECT id FROM productos WHERE codigo_producto IN ('C0098','R0030','A0055','R0047','A0061','P0029','A0022','J0041','J0006',
          'B0015','C0177','L0028','P0034','B0006','J0040','J0051','C0006','A0023','P0090','V0031','R0046','P0047','C0048','C0192','P0063','S0027','M0056',
          'M0010','T0057','L0018','C0111','S0007','G0012','B0029','H0016','C0110','C0188','M0052','M0051','L0007','B0122','B0102','C0166'))
          AND pedido_fecha > '2015-02-28'
          ORDER BY fecha_pedido_detalle,productos.id;";
          $data_pedidos = $this->Role->query($sql);
          foreach ($data_pedidos as $data_pedido) :
          echo $data_pedido['0']['sql_update'];
          echo "<br>";
          endforeach; */
    }

    function add() {
        $options = $this->MenuAction->find('all', array('order' => 'MenuAction.menu_id', 'conditions' => array('MenuAction.menus_actions_ajax' => false)));
        $this->set('options', $options);

        if (!empty($this->data)) {
            $this->Role->create();
            if ($this->Role->save($this->data)) {
                $rol_id = $this->Role->getInsertID();

                // Permisos por defecto para todos los usuarios
                $options_ajaxs = $this->MenuAction->find('all', array('order' => 'MenuAction.menu_id', 'conditions' => array('MenuAction.menus_actions_ajax' => true)));
                foreach ($options_ajaxs as $options_ajax) {
                    $actions_default = array('menus_id' => '0',
                        'menus_actions_id' => $options_ajax['MenuAction']['id'],
                        'allow_deny' => '1',
                        'rol_id' => $rol_id);
                    $this->MenuUser->create();
                    $this->MenuUser->save($actions_default, false);
                }

                foreach ($options as $option) :
                    $this->MenuUser->create();
                    if ($this->data['Role'][$option['MenuAction']['id']] > 0) {
                        $datos_actions = array('menus_id' => '0',
                            'menus_actions_id' => $option['MenuAction']['id'],
                            'allow_deny' => '1',
                            'rol_id' => $rol_id);
                        $this->MenuUser->save($datos_actions, false);
                    } else {
                        $datos_actions = array('menus_id' => '0',
                            'menus_actions_id' => $option['MenuAction']['id'],
                            'allow_deny' => '0',
                            'rol_id' => $rol_id);
                        $this->MenuUser->save($datos_actions, FALSE);
                    }
                endforeach;
                $this->Session->setFlash(__('Se agrego el registro.', true));
                $this->redirect(array('controller' => 'roles', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('El rol no se puede salvar, verifique los campos obligatorios e intente de nuevo.', true));
            }
        }
    }

    function edit($id = null) {
        $options = $this->MenuAction->find('all', array('order' => 'MenuAction.menu_id asc', 'conditions' => array('MenuAction.menus_actions_ajax' => false)));
        $this->set('options', $options);

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Rol invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Role->save($this->data)) {

                $delete = "DELETE FROM menus_users WHERE menus_users.rol_id = " . $id . "";
                $this->MenuUser->query($delete);

                $rol_id = $id;

                // Permisos por defecto para todos los usuarios
                $options_ajaxs = $this->MenuAction->find('all', array('order' => 'MenuAction.menu_id', 'conditions' => array('MenuAction.menus_actions_ajax' => true)));
                foreach ($options_ajaxs as $options_ajax) {
                    $actions_default = array('menus_id' => '0',
                        'menus_actions_id' => $options_ajax['MenuAction']['id'],
                        'allow_deny' => '1',
                        'rol_id' => $rol_id);
                    $this->MenuUser->create();
                    $this->MenuUser->save($actions_default, false);
                }

                foreach ($options as $option) :
                    $this->MenuUser->create();
                    if ($this->data['Role'][$option['MenuAction']['id']] > 0) {
                        $datos_actions = array('menus_id' => '0',
                            'menus_actions_id' => $option['MenuAction']['id'],
                            'allow_deny' => '1',
                            'rol_id' => $rol_id);
                        $this->MenuUser->save($datos_actions, false);
                    } else {
                        $datos_actions = array('menus_id' => '0',
                            'menus_actions_id' => $option['MenuAction']['id'],
                            'allow_deny' => '0',
                            'rol_id' => $rol_id);
                        $this->MenuUser->save($datos_actions, FALSE);
                    }
                endforeach;

                $this->Session->setFlash(__('El rol y sus respectivos permisos han sido modificados.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El rol no puede ser modificada. Intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Role->read(null, $id);
            $menu_users = $this->MenuUser->find('all', array('conditions' => array('MenuUser.rol_id' => $id)));
            $this->set('menu_users', $menu_users);
        }
    }

    function view($id = null) {
        $options = $this->MenuAction->find('all', array('conditions' => array('MenuAction.menus_actions_ajax' => false)));
        $this->set('options', $options);

        if (!$id) {
            $this->Session->setFlash(__('Rol invalido', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('role', $this->Role->read(null, $id));

        // Consultar los que habitan la vivienda
        $menu_users = $this->MenuUser->find('all', array('conditions' => array('MenuUser.rol_id' => $id)));
        $this->set('menu_users', $menu_users);
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Rol invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $rol_estado = $this->Role->find('first', array('fields' => 'Role.rol_estado', 'conditions' => array('Role.rol_id' => $id)));
            if ($rol_estado['Role']['rol_estado']) {
                $this->Role->updateAll(array("Role.rol_estado" => 'false'), array("Role.rol_id" => $id));
                $this->User->updateAll(array("User.user_estado" => 'false'), array("User.rol_id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO del rol. Se inactivaron todos los usuarios asociados a este rol.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Role->updateAll(array("Role.rol_estado" => 'true'), array("Role.rol_id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO del rol.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('El rol no fue encontrado.', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
