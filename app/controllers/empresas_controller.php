<?php

class EmpresasController extends AppController {

    var $name = 'Empresas';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Empresa', 'EmpresasAprobadore', 'Departamento', 'Municipio', 'User', 'Sucursale', 'TipoPedido', 'Regionale', 'Vendedore', 'Sectore');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Empresas', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Empresas', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {

        if ($this->Session->read('Auth.User.rol_id') == '1') {
            $conditions = array();
        } else {
            $permisos = $this->EmpresasAprobadore->find('all', array('fields' => 'EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id', 'conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
            $empresas_permisos = array();
            $sucursales_permisos = array();
            foreach ($permisos as $permiso) {
                array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
                array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
            }
            $conditions = array('Empresa.id' => $empresas_permisos);
        }

        // Consultar las empresas sin aprobadores
        $sql = "SELECT id FROM empresas WHERE id NOT IN (SELECT DISTINCT empresa_id FROM empresas_aprobadores) ORDER BY nombre_empresa LIMIT 45;";
        $empresas_sin_aprobadores = $this->Empresa->query($sql);

        $this->paginate = array('limit' => 30, 'order' => array(
                'Empresa.nombre_empresa' => 'asc'
        ));
        $this->Empresa->recursive = 0;

        $this->Empresa->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Empresa']['contrato_empresa'])) {
                $where = "+Empresa+.+contrato_empresa+ ilike '%" . $this->data['Empresa']['contrato_empresa'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['nit_empresa'])) {
                $where = "+Empresa+.+nit_empresa+ ilike '%" . $this->data['Empresa']['nit_empresa'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['nombre_empresa'])) {
                $where = "+Empresa+.+nombre_empresa+ ilike '%" . $this->data['Empresa']['nombre_empresa'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['direccion_empresa'])) {
                $where = "+Empresa+.+direccion_empresa+ ilike '%" . $this->data['Empresa']['direccion_empresa'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['nombre_contacto'])) {
                $where = "+Empresa+.+nombre_contacto+ ilike '%" . $this->data['Empresa']['nombre_contacto'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['departamento_id'])) {
                $where = "+Empresa+.+departamento_id+ = " . $this->data['Empresa']['departamento_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['municipio_id'])) {
                $where = "+Empresa+.+municipio_id+ = " . $this->data['Empresa']['municipio_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['estado_empresa'])) {
                $where = "+Empresa+.+estado_empresa+ = " . $this->data['Empresa']['estado_empresa'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Empresa']['empresa_aprobadores'])) {
                $empresas_sin_aprobador_array = array();
                foreach ($empresas_sin_aprobadores as $empresas_sin_aprobador):
                    array_push($empresas_sin_aprobador_array, $empresas_sin_aprobador['0']['id']);
                endforeach;
                $where = "+Empresa+.+id+ IN (" . implode(',', $empresas_sin_aprobador_array) . ")";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
                $this->paginate = array('limit' => 100, 'order' => array(
                        'Empresa.nombre_empresa' => 'asc'
                ));
            }
            $this->set('empresas', $this->paginate($conditions));
        } else {
            $this->set('empresas', $this->paginate($conditions));
        }
        $estados = array('false' => 'Inactivo', 'true' => 'Activo');
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $this->set(compact('departamentos', 'municipios', 'empresas_sin_aprobadores', 'estados'));
    }

    function aprobadores($id = null) {
        //Configure::write('debug', 2);
        ini_set('memory_limit', '1024M');
        $this->Session->write('EmpresasAprobadore.empresa_id', $id);
        if (!empty($this->data)) {
            if (empty($this->data['EmpresasAprobadore']['empresa_id_2']) && !empty($this->data['EmpresasAprobadore']['regional_id'])) {
                // Cuando se selecciona regional
                $sucursales = $this->Sucursale->find('all', array('fields' => 'Sucursale.id, Sucursale.regional_id, Regionale.nombre_regional, Sucursale.id_empresa', 'conditions' => array('Sucursale.estado_sucursal' => true, 'Sucursale.regional_id' => $this->data['EmpresasAprobadore']['regional_id'])));
                foreach ($sucursales as $sucursal) {
                    $delete = "DELETE FROM empresas_aprobadores WHERE user_id IN (1," . $this->data['EmpresasAprobadore']['user_id'] . ")  AND empresa_id = " . $sucursal['Sucursale']['id_empresa'] . " AND sucursal_id = " . $sucursal['Sucursale']['id'] . " AND regional_id = " . $this->data['EmpresasAprobadore']['regional_id'] . " AND tipo_pedido_id =" . $this->data['EmpresasAprobadore']['tipo_pedido_id'] . ";";
                    $this->EmpresasAprobadore->query($delete);

                    $this->EmpresasAprobadore->create();
                    $data_sucursales = array('empresa_id' => $sucursal['Sucursale']['id_empresa'], //$this->data['EmpresasAprobadore']['empresa_id'],
                        'user_id' => $this->data['EmpresasAprobadore']['user_id'],
                        //   'tipo_pedido_id' => $this->data['EmpresasAprobadore']['tipo_pedido_id'],
                        'sucursal_id' => $sucursal['Sucursale']['id'],
                        'regional_id' => $this->data['EmpresasAprobadore']['regional_id']);
                    $this->EmpresasAprobadore->save($data_sucursales, FALSE);

                    $this->EmpresasAprobadore->create();
                    $permisos_admin = array('empresa_id' => $sucursal['Sucursale']['id_empresa'],
                        'user_id' => '1',
                        //     'tipo_pedido_id' => $this->data['EmpresasAprobadore']['tipo_pedido_id'],
                        'sucursal_id' => $sucursal['Sucursale']['id'],
                        'regional_id' => $this->data['EmpresasAprobadore']['regional_id']);
                    $this->EmpresasAprobadore->save($permisos_admin, FALSE);
                    $regional = $sucursal['Regionale']['nombre_regional'];
                }
            } else {
                // Cuando es para todas la sucursales de una empresa
                $delete = "DELETE FROM empresas_aprobadores WHERE user_id IN (1," . $this->data['EmpresasAprobadore']['user_id'] . ")  AND empresa_id = " . $this->data['EmpresasAprobadore']['empresa_id'] . ";";
                $this->EmpresasAprobadore->query($delete);

                $permisos_usuario = "INSERT INTO empresas_aprobadores (sucursal_id,empresa_id,regional_id, user_id)
                        (SELECT id, id_empresa, regional_id, " . $this->data['EmpresasAprobadore']['user_id'] . " FROM sucursales WHERE id_empresa = " . $this->data['EmpresasAprobadore']['empresa_id_2'] . ");";
                $this->EmpresasAprobadore->query($permisos_usuario);

                $permisos_admin = "INSERT INTO empresas_aprobadores (sucursal_id,empresa_id,regional_id, user_id)
                        (SELECT id, id_empresa, regional_id, 1  FROM sucursales WHERE id_empresa = " . $this->data['EmpresasAprobadore']['empresa_id_2'] . ");";
                $this->EmpresasAprobadore->query($permisos_admin);
            }

            /* $sql = "UPDATE empresas_aprobadores SET regional_id = sucursales.regional_id
              FROM sucursales WHERE empresas_aprobadores.sucursal_id = sucursales.id
              AND sucursales.estado_sucursal = true";
              $this->EmpresasAprobadore->query($sql); //31052018 */

            $this->Session->setFlash(__('Se han asignado los permisos a los pedidos para el usuario seleccionado. ', true));
            if (!empty($this->data['EmpresasAprobadore']['url'])) {
                $this->redirect(array('controller' => 'users', 'action' => $this->data['EmpresasAprobadore']['url']));
                exit;
            }
            $this->redirect(array('controller' => 'empresas', 'action' => 'aprobadores/' . $this->data['EmpresasAprobadore']['empresa_id']));
            /*
              if ($this->EmpresasAprobadore->save($this->data)) {

              // Insertar los aprobadores para que ADMIN pueda listar
              $this->EmpresasAprobadore->create();
              $empresas_aprobadores = array('empresa_id' => $this->data['EmpresasAprobadore']['empresa_id'],
              'user_id' => '1',
              'tipo_pedido_id' => $this->data['EmpresasAprobadore']['tipo_pedido_id'],
              'sucursal_id' => $this->data['EmpresasAprobadore']['sucursal_id'],
              'regional_id' => $this->data['EmpresasAprobadore']['regional_id']
              );
              $this->EmpresasAprobadore->save($empresas_aprobadores, FALSE);



              $this->Session->setFlash(__('Se ha asignado un aprobador. ', true));
              // $this->redirect(array('action' => 'index'));
              header("Location: ../aprobadores/" . $this->data['EmpresasAprobadore']['empresa_id']);
              } else {
              $this->Session->setFlash(__('El aprobador ya esta asignado para un tipo de pedido. ', true));
              } */
        }
        $empresa = $this->Empresa->find('all', array('fields' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => $id)));
        $aprobadores = $this->EmpresasAprobadore->find('all', array(
            'fields' => 'Empresa.nombre_empresa, Sucursale.nombre_sucursal, Sucursale.regional_sucursal, User.username, User.nombres_persona, EmpresasAprobadore.id',
            'conditions' => array('EmpresasAprobadore.empresa_id' => $id/*, 'EmpresasAprobadore.user_id >' => $this->Session->read('Auth.User.id')*/),
            'order' => "EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id, EmpresasAprobadore.user_id",
            'limit' => '100')
        );
        $this->set('empresa', $empresa);
        $this->set('aprobadores', $aprobadores);

        //  $aprobadores = array($id, '1');
        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', array('conditions' => array('Regionale.estado' => true))));
        $users = $this->User->find('list', array('fields' => 'User.username', 'order' => 'User.nombres_persona', 'order' => 'User.username', 'conditions' => array(/* 'User.empresa_id' => $aprobadores, */'User.estado' => true /* , 'User.rol_id' => $this->Permisos->RolesInternos() */)));
        // $users = $this->User->find('list', array('fields' => 'User.nombres_persona', 'order' => 'User.nombres_persona', 'conditions' => array('User.empresa_id' => $id, 'User.estado' => true, 'User.rol_id' => $this->Permisos->RolesInternos())));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('users', 'tipoPedido', 'regionales')); //31052018
        $this->set('empresa_id', $id);
    }

    function quitar_aprobador($id = null) {
        $this->EmpresasAprobadore->id = $id;
        if ($this->EmpresasAprobadore->delete()) {
            $this->Session->setFlash(__('Se quitó el aprobador correctamete de la lista ', true));
            $this->redirect(array('action' => 'aprobadores/' . $this->Session->read('EmpresasAprobadore.empresa_id'))); //31052018 Se debe pasar por parametro
        }
    }

    function add() {
        if (!empty($this->data)) {
            $this->Empresa->create();
            if ($this->Empresa->save($this->data)) {
                $empresa_id = $this->Empresa->getInsertID();
                $this->Session->write('Empresa.nombre_empresa', $this->data['Empresa']['nombre_empresa']);
                $sql_parametro_precio = "UPDATE sucursales SET parametro_precio = empresas.parametro_precio 
                                    FROM empresas
                                    WHERE empresas.id = sucursales.id_empresa
                                    AND sucursales.id_empresa = " . $empresa_id . ";";
                $this->Empresa->query($sql_parametro_precio);
                $this->Session->setFlash(__('Se ha creado la empresa ' . $this->data['Empresa']['nombre_empresa'] . '. ', true));
                /* $this->redirect(array('action' => 'index')); */header("Location: ../regionales/index/" . $empresa_id);
            } else {
                $this->Session->setFlash(__('La empresa no pudo ser salvada. Por favor intente de nuevo.', true));
            }
        }

        $parametro_precio = array('1' => 'Precio Centro Aseo', '2' => 'Precio Venta'); // 2022-11-17 
        $users = $this->User->find('list', array('fields' => 'User.nombres_persona', 'order' => 'User.nombres_persona', 'conditions' => array('User.estado' => true, 'User.rol_id' => $this->Permisos->RolesInternos())));
        $vendedores = $this->Vendedore->find('list', array('fields' => 'Vendedore.nombre_vendedor', 'order' => 'Vendedore.nombre_vendedor', 'conditions' => array('Vendedore.estado_vendedor' => true)));
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $sectores = $this->Sectore->find('list', array('fields' => 'Sectore.nombre_sector', 'order' => 'Sectore.nombre_sector'));
        $this->set(compact('users', 'vendedores', 'departamentos', 'municipios', 'parametro_precio', 'sectores'));
    }

    function edit($id = null) {
        Configure::write('debug', 2);
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró la empresa', true));
            /* $this->redirect(array('action' => 'index')); */header("Location: index");
        }
        if (!empty($this->data)) {

            if ($this->Empresa->save($this->data)) {
                $empresa_id = $this->data['Empresa']['id'];
                $sql_parametro_precio = "UPDATE sucursales SET parametro_precio = empresas.parametro_precio 
                                    FROM empresas
                                    WHERE empresas.id = sucursales.id_empresa
                                    AND sucursales.id_empresa = " . $empresa_id . ";";
                $this->Empresa->query($sql_parametro_precio);

                $this->Session->setFlash(__('La empresa se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Empresa->read(null, $id);
        }

        $parametro_precio = array('1' => 'Precio Centro Aseo', '2' => 'Precio Venta'); // 2022-11-17 
        $vendedores = $this->Vendedore->find('list', array('fields' => 'Vendedore.nombre_vendedor', 'order' => 'Vendedore.nombre_vendedor', 'conditions' => array('Vendedore.estado_vendedor' => true)));
        $users = $this->User->find('list', array('fields' => 'User.nombres_persona', 'order' => 'User.nombres_persona', 'conditions' => array('User.estado' => true, 'User.rol_id' => $this->Permisos->RolesInternos())));
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $sectores = $this->Sectore->find('list', array('fields' => 'Sectore.nombre_sector', 'order' => 'Sectore.nombre_sector'));
        $this->set(compact('users', 'vendedores', 'departamentos', 'municipios', 'parametro_precio','sectores'));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('No se encontró la empresa', true));
            /* $this->redirect(array('action' => 'index')); */header("Location: index");
        }

        $presupuestos = $this->Empresa->query(" SELECT sucursales.id, json_agg(t) 
        FROM sucursales, sucursales_presupuestos_pedidos AS t
        WHERE sucursales.id = t.sucursal_id
        AND sucursales.id_empresa= " . $id . "
        GROUP BY sucursales.id");

        $this->set('empresa', $this->Empresa->read(null, $id));
        $this->set('sucursals', $this->Sucursale->find('all', array('conditions' => array('Sucursale.id_empresa' => $id))));
        $this->set('presupuestos', $presupuestos);
    }

    function delete($id = null) {
        $this->Empresa->set($this->data);
        if (!empty($this->data)) {
            foreach ($this->data['Empresa'] as $key => $value) {
                if ($value > 0) {
                    $estado = $this->Empresa->find('first', array('fields' => 'Empresa.estado_empresa', 'conditions' => array('Empresa.id' => $value)));
                    if ($estado['Empresa']['estado_empresa']) {
                        $this->Empresa->updateAll(array("Empresa.estado_empresa" => 'false'), array("Empresa.id" => $value));
                        $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'false'), array("Sucursale.id_empresa" => $value));
                        $this->Regionale->updateAll(array("Regionale.estado_regional" => 'false'), array("Regionale.empresa_id" => $value));
                    } else {
                        $this->Empresa->updateAll(array("Empresa.estado_empresa" => 'true'), array("Empresa.id" => $value));
                        $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'true'), array("Sucursale.id_empresa" => $value));
                        $this->Regionale->updateAll(array("Regionale.estado_regional" => 'true'), array("Regionale.empresa_id" => $value));
                    }
                }
            }
            $this->Session->setFlash(__('Las empresas seleccionados se les ha cambiado su estado masivamente. De igual manera sus Regionales y Sucursales', true));
            $this->redirect(array('controller' => 'empresas', 'action' => 'index'));
            exit;
        }
        if (!$id) {
            $this->Session->setFlash(__('Empresa invalida.', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->Empresa->find('first', array('fields' => 'Empresa.estado_empresa', 'conditions' => array('Empresa.id' => $id)));
            if ($estado['Empresa']['estado_empresa']) {
                $this->Empresa->updateAll(array("Empresa.estado_empresa" => 'false'), array("Empresa.id" => $id));
                $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'false'), array("Sucursale.id_empresa" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO la empresa.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Empresa->updateAll(array("Empresa.estado_empresa" => 'true'), array("Empresa.id" => $id));
                $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'true'), array("Sucursale.id_empresa" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO la empresa.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
    }

}

?>
