<?php

class UsersController extends AppController {

    var $name = 'Users';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('User', 'Asociado', 'Role', 'Departamento', 'Municipio', 'TipoSexo', 'TipoDocumento', 'Empresa', 'EmpresasAprobadore', 'Sucursale', 'Cronograma', 'Regionale', 'TipoPedido');

    function beforeFilter() {

        parent::beforeFilter();
        //        $this->Auth->allow('registro', 'recuperar', 'activar');
        $this->set('title_for_layout', 'Usuarios');
        $this->Auth->userScope = array('User.estado' => true);
        $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'perfil');
    }

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Users', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Users', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function login() {
        // SELECT * FROM pedidos WHERE pedido_fecha_vencimiento <= now();
        // $this->layout = 'user';
        $this->set('title_for_layout', ' - Ingresar al Sistema');
    }

    function logout() {
        $this->redirect($this->Auth->logout());
    }

    function index() {

        if ($this->Session->read('Auth.User.id') == '97') {
            $users = $this->User->find('all', array('conditions' => array('User.empresa_id' => '104')));
            // print_r($users);
            foreach ($users as $value) {
                // echo $value['User']['password'];
                // $this->User->updateAll(array("User.password" => "'" . $this->Auth->password($value['User']['password']) . "'","User.restore_password"=>$value['User']['password']), array("User.id" => $value['User']['id']));
                /* echo $this->Auth->password($value['User']['password']);
                  echo '.';
                  echo $value['User']['username'];
                  echo '.';
                  echo $value['User']['id'];
                  echo '<br>'; */
            }
        }

        $this->User->set($this->data);
        if (!empty($this->data) && $this->User->validates()) {
            $conditions = array();
            if (!empty($this->data['User']['username_f'])) {
                $where = "+User+.+username+ ILIKE '%" . $this->data['User']['username_f'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['User']['nombres_persona_f'])) {
                $where = "+User+.+nombres_persona+ LIKE '%" . $this->data['User']['nombres_persona_f'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['User']['no_identificacion_persona_f'])) {
                $where = "+User+.+no_identificacion_persona+ = '" . $this->data['User']['no_identificacion_persona_f'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['User']['rol_id_f'])) {
                $where = "+User+.+rol_id+ = '" . $this->data['User']['rol_id_f'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['User']['estado_f'] >= '0') {
                $where = "+User+.+estado+ = '" . $this->data['User']['estado_f'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['User']['empresa_id_f'])) {
                $where = "+User+.+empresa_id+ = '" . $this->data['User']['empresa_id_f'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['User']['sucursal_id_f'])) {
                $where = "+User+.+sucursal_id+ = '" . $this->data['User']['sucursal_id_f'] . "'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if ($this->data['User']['cambiar_sucursal']) {
                $where = "+User+.+cambiar_sucursal+ = TRUE";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['User']['parametro_precio'])) {
                $where = "+User+.+parametro_precio+ = " . $this->data['User']['parametro_precio'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->paginate = array('limit' => 200);
            $this->set('users', $this->paginate($conditions));
            $this->set('filtro', true);
        } else {
            $this->User->recursive = 0;
            $this->helpers['Paginator'] = array('ajax' => 'Ajax');
            $this->set('users', $this->paginate());
            $this->set('filtro', false);
        }
        // $parametro_precio = array('0' => 'Todos', '1' => 'Precios CLEANEST L&C', '2' => 'Precios CENTRO ASEO'); //31052018
        $parametro_precio = array('0' => 'Todos', '1' => 'Precio Centro Aseo', '2' => 'Precio Venta'); // 2022-11-17 
        $roles = $this->Role->find('list', array('fields' => 'Role.rol_nombre', 'order' => 'Role.rol_nombre'));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.v_regional_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $estados = array('1' => 'Activo', '0' => 'Inactivo');
        $this->set(compact('roles', 'empresas', 'estados', 'sucursales', 'parametro_precio'));
    }

    function add() {
        ini_set('memory_limit', '512M');
        // Configure::write('debug', 2);
        if (!empty($this->data)) {
            $existe = $this->User->find('count', array('conditions' => array('User.username' => $this->data['User']['username'])));
            if ($existe > 0) {
                $this->Session->setFlash(__('El usuario no puede ser salvado ya que el nombre de usuario ' . $this->data['User']['username'] . ' ya existe.', true));
            } else {
                $this->data['User']['password'] = $this->Auth->password($this->data['User']['no_identificacion_persona']);
                $this->User->create();
                if ($this->User->save($this->data)) {
                    $this->Session->setFlash(__('Se ha creado el usuario ' . $this->data['User']['username'] . '. ', true));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('El usuario no pudo ser salvado. Por favor intente de nuevo.', true));
                }
            }
        }

        $asociados = $this->Asociado->find("list", ["fields" => ["Asociado.nombre_asociado"]]);
        $parametro_precio = array('0' => 'Todos', '1' => 'Precio Centro Aseo', '2' => 'Precio Venta', '3' => 'Ninguno'); // 2022-11-17 
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $tipoDocumentos = $this->TipoDocumento->find('list', array('fields' => 'TipoDocumento.nombre_tipo_documento', 'order' => 'TipoDocumento.id'));
        $tipoSexos = $this->TipoSexo->find('list', array('fields' => 'TipoSexo.nombre_tipo_sexo', 'order' => 'TipoSexo.id'));
        $roles = $this->Role->find('list', array('fields' => 'Role.rol_nombre', 'order' => 'Role.rol_nombre', 'conditions' => array('Role.rol_estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.v_regional_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $this->set(compact('departamentos', 'municipios', 'tipoDocumentos', 'tipoSexos', 'roles', 'empresas', 'sucursales', 'parametro_precio',"asociados"));
    }

    function edit($id = null) {
        ini_set('memory_limit', '1024M');
        // Configure::write('debug', 2);

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró el usuario', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {

            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('El usuario se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }

        $aprobadores = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $id), 'order' => "EmpresasAprobadore.empresa_id, EmpresasAprobadore.sucursal_id, EmpresasAprobadore.user_id"));
        $asociados = $this->Asociado->find("list", ["fields" => ["Asociado.nombre_asociado"]]);
        $this->set('aprobadores', $aprobadores);
        $regionales_permisos = array();
        foreach ($aprobadores as $aprobador) {
            array_push($regionales_permisos, $aprobador['EmpresasAprobadore']['regional_id']);
        }
        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', 'conditions' => array('Regionale.estado_regional' => true)));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));

        $parametro_precio = array('0' => 'Todos', '1' => 'Precios CLEANEST L&C', '2' => 'Precios CENTRO ASEO', '3' => 'Ninguno'); 
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $tipoDocumentos = $this->TipoDocumento->find('list', array('fields' => 'TipoDocumento.nombre_tipo_documento', 'order' => 'TipoDocumento.id'));
        $tipoSexos = $this->TipoSexo->find('list', array('fields' => 'TipoSexo.nombre_tipo_sexo', 'order' => 'TipoSexo.id'));
        $roles = $this->Role->find('list', array('fields' => 'Role.rol_nombre', 'order' => 'Role.rol_nombre', 'conditions' => array('Role.rol_estado' => true)));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true)));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.v_regional_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $this->set(compact('departamentos', 'municipios', 'tipoDocumentos', 'tipoSexos', 'roles', 'empresas', 'sucursales', 'parametro_precio', 'regionales', 'tipoPedido', 'asociados'));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('No se encontró el usuario', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function municipios() {
        if ($this->RequestHandler->isAjax()) {//condición que pregunta si la petición es AJAX
            if (!empty($_REQUEST['UserDepartamentoId'])) {
                $conditions = array('Municipio.departamento_id' => $_REQUEST['UserDepartamentoId']);
                $municipios = $this->Municipio->find('all', array('conditions' => $conditions));
                echo json_encode($municipios);
            }
        }
    }

    function sucursales() {
        ini_set('memory_limit', '1024M');
        if ($this->RequestHandler->isAjax()) {//condici�n que pregunta si la petici�n es AJAX
            $empresa = array();
            (!empty($_REQUEST['UserEmpresaId']) ? $empresa = $_REQUEST['UserEmpresaId'] : '');
            (!empty($_REQUEST['PedidoEmpresaId']) ? $empresa = $_REQUEST['PedidoEmpresaId'] : '');
            (!empty($_REQUEST['PedidoEmpresaId2']) ? $empresa = $_REQUEST['PedidoEmpresaId2'] : '');
            //31052018

            $empresas_permisos = array();
            $sucursales_permisos = array();
            if (!empty($empresa)) {
                $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.empresa_id' => $empresa, 'EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
                foreach ($permisos as $permiso) {
                    array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
                    array_push($sucursales_permisos, $permiso['EmpresasAprobadore']['sucursal_id']);
                }

                $conditions_empresa = array('id' => $empresas_permisos);
                $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true, 'Sucursale.id_empresa' => $empresa);
            }
            //31052018

            if (!empty($_REQUEST['UserEmpresaId'])) {
                $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true, 'Sucursale.id_empresa' => $empresa);

                //$conditions = array('Sucursale.id_empresa' => $conditions_sucursales, 'Sucursale.estado_sucursal' => true);
                $sucursales = $this->Sucursale->find('all', array('fields' => 'Sucursale.*', 'conditions' => $conditions_sucursales, 'order' => 'Sucursale.v_regional_sucursal'));
                if (count($sucursales)) {
                    echo json_encode($sucursales);
                } else {
                    echo 'null';
                }
            }

            if (!empty($_REQUEST['PedidoRegionalSucursal_admin'])) {
                $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true, 'Sucursale.id_empresa' => $empresa, 'UPPER(Sucursale.regional_sucursal)' => strtoupper($_REQUEST['PedidoRegionalSucursal_admin']));
                // $conditions = array('Sucursale.id_empresa' => $_REQUEST['PedidoEmpresaId'], , 'Sucursale.estado_sucursal' => true);
                $sucursales = $this->Sucursale->find('all', array('conditions' => $conditions_sucursales, 'order' => 'Sucursale.v_regional_sucursal'));
                if (count($sucursales)) {
                    echo json_encode($sucursales);
                    exit;
                } else {
                    echo 'null';
                    exit;
                }
            }

            if (!empty($_REQUEST['PedidoRegionalSucursal'])) {
                // $conditions = array('Sucursale.id_empresa' => $this->Session->read('Auth.User.empresa_id'), 'Sucursale.regional_sucursal' => $_REQUEST['PedidoRegionalSucursal'], 'Sucursale.estado_sucursal' => true);
                $sucursales = $this->Sucursale->find('all', array('conditions' => $conditions_sucursales, 'order' => 'Sucursale.v_regional_sucursal'));
                if (count($sucursales)) {
                    echo json_encode($sucursales);
                } else {
                    echo 'null';
                }
            }

            /* Este filtro va sin empresa */
            if (!empty($_REQUEST['PedidoRegionalSucursal2'])) {
                $conditions_sucursales = array('Sucursale.id' => $sucursales_permisos, 'Sucursale.estado_sucursal' => true, 'Sucursale.regional_sucursal' => $_REQUEST['PedidoRegionalSucursal2']);
                /* if (!empty($_REQUEST['PedidoEmpresaId2'])) {

                  $conditions = array('Sucursale.id_empresa' => $_REQUEST['PedidoEmpresaId2'], 'Sucursale.regional_sucursal' => $_REQUEST['PedidoRegionalSucursal2'], 'Sucursale.estado_sucursal' => true);
                  } else {
                  $conditions = array('Sucursale.regional_sucursal' => $_REQUEST['PedidoRegionalSucursal2'], 'Sucursale.estado_sucursal' => true);
                  } */
                $sucursales = $this->Sucursale->find('all', array('conditions' => $conditions_sucursales, 'order' => 'Sucursale.v_regional_sucursal'));
                if (count($sucursales)) {
                    echo json_encode($sucursales);
                } else {
                    echo 'null';
                }
            }

            /* Este filtro va para consultar las regionales */
            if (!empty($_REQUEST['PedidoEmpresaId'])) {
                $regional_data = $this->Regionale->find('all', array('fields' => array('Regionale.nombre_regional'), 'conditions' => array('Regionale.empresa_id' => $_REQUEST['PedidoEmpresaId'], 'Regionale.estado_regional' => true)));
                // $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal'), 'conditions' => $conditions_sucursales, 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
                $regional = array();
                foreach ($regional_data as $value) {
                    // $regional['value'] = $value['Sucursale']['regional_sucursal'];
                    array_push($regional, $value['Regionale']['nombre_regional']);
                    // array_push($regional, $value['Sucursale']['regional_sucursal']);
                    // $regional = $regional . '"' . $value['Sucursale']['regional_sucursal'] . '",';
                }
                if (count($regional)) {
                    // print_r($regional);
                    echo json_encode($regional);
                } else {
                    echo 'null';
                }
            }
        }
    }

    function cronogramas() {
        ini_set('memory_limit', '1024M');
        if ($this->RequestHandler->isAjax()) {
            if (!empty($_REQUEST['PedidoEmpresaId'])) {
                $validar_cronograma = $this->Empresa->find('all', array('fields' => 'Empresa.parametro_cronograma', 'conditions' => array('Empresa.id' => $_REQUEST['PedidoEmpresaId'], 'Empresa.parametro_cronograma' => true)));
                if (count($validar_cronograma) > 0) {
                    $cronograma = $this->Cronograma->find('all', array('fields' => 'Cronograma.tipo_pedido_id_2', 'conditions' => array('Cronograma.empresa_id' => $_REQUEST['PedidoEmpresaId'], 'Cronograma.estado_cronograma' => true)));
                    $tipo_pedido = $this->TipoPedido->find('all', array('fields' => 'TipoPedido.id, TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true, 'TipoPedido.id' => explode(',', $cronograma[0]['Cronograma']['tipo_pedido_id_2']))));
                    echo json_encode($tipo_pedido);
                    exit;
                } else {
                    $tipo_pedido = $this->TipoPedido->find('all', array('fields' => 'TipoPedido.id, TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
                    echo json_encode($tipo_pedido);
                }
                exit;
            } else {
                echo "null";
                exit;
            }
        }
    }

    function perfil() {
        date_default_timezone_set('America/Bogota');
        //  echo date('h:i');
        // Configure::write('debug', 2);
        ini_set('memory_limit', '512M');
        // $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        // print_r($permisos);

        $empresa = "SELECT parametro_tiempo_pedido FROM empresas WHERE id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $data_empresa = $this->Empresa->query($empresa);
        $tiempo_pedido = 2;
        foreach ($data_empresa as $value) {
            $tiempo_pedido = $value[0]['parametro_tiempo_pedido'];
        }

        //if (count($permisos) > 0) {
        if ($this->Session->read('Auth.User.rol_id') == '1') {
            // Consultar los pedidos que est�n pr�ximos a vencerse
            $pedidos_vencimiento = "SELECT pedidos.id as pedido, 
                fecha_orden_pedido,
                (fecha_orden_pedido + interval '" . $tiempo_pedido . " days') as fecha_maxima, 
                now() as fecha_actual, 
                empresas.nombre_empresa, tipo_pedidos.nombre_tipo_pedido
                FROM pedidos
                INNER JOIN empresas ON pedidos.empresa_id = empresas.id 
                INNER JOIN tipo_pedidos ON pedidos.tipo_pedido_id = tipo_pedidos.id
                WHERE now() BETWEEN pedidos.fecha_orden_pedido AND (fecha_orden_pedido + interval '" . $tiempo_pedido . " days')  AND 
                pedido_estado_pedido = 1
                AND pedidos.fecha_orden_pedido >= '2020-02-01';";
            $data_pedidos = $this->Cronograma->query($pedidos_vencimiento);
        }
        //}
        $pedidos = null;
        foreach ($data_pedidos as $value) {
            // $this->Cronograma->query("UPDATE pedidos SET pedido_fecha_vencimiento = '" . $value[0]['fecha_maxima'] . "' WHERE id = " . $value[0]['pedido'] . ";");

            $pedidos = $pedidos . "<li>El pedido <span style='color:red;'><b>#000" . $value[0]['pedido'] . "</b></span> creado el <b>" . $value[0]['fecha_orden_pedido'] . "</b> de tipo de pedido " . utf8_decode($value[0]['nombre_tipo_pedido']) . " se cancelar� automaticamente el  <span style='color:red;'><b>(" . $value[0]['fecha_maxima'] . ")</b></span>. </li>";
        }
        $this->set('pedidos_vencer', utf8_encode($pedidos));


        $this->set('user', $this->User->read(null, $this->Session->read('Auth.User.id')));

        // Activar Cronogramas por empresa
        // $activar = "SELECT id FROM cronogramas WHERE NOW()::date BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $activar = "set timezone TO 'America/Bogota'; SELECT id FROM cronogramas WHERE '" . date('Y-m-d') . "' BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $data_activar = $this->Cronograma->query($activar);
        foreach ($data_activar as $value) {
            $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'true'), array("Cronograma.id" => $value[0]['id']));
        }

        // Desactivar Cronogramas por empresa
        // $inactivar = "SELECT id FROM cronogramas WHERE fecha_fin < NOW()::date;";
        // $inactivar = "SELECT id FROM cronogramas WHERE NOW()::date NOT BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . " AND estado_cronograma= TRUE;";
        $inactivar = "set timezone TO 'America/Bogota'; SELECT id FROM cronogramas WHERE '" . date('Y-m-d') . "' NOT BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . " AND estado_cronograma= TRUE;";
        $data_inactivar = $this->Cronograma->query($inactivar);
        foreach ($data_inactivar as $value) {
            $this->Cronograma->updateAll(array("Cronograma.estado_cronograma" => 'false'), array("Cronograma.id" => $value[0]['id']));
        }


        $query_cronograma = "set timezone TO 'America/Bogota'; SELECT * FROM cronogramas, tipo_pedidos WHERE cronogramas.tipo_pedido_id = tipo_pedidos.id AND  '" . date('Y-m-d') . "' BETWEEN fecha_inicio AND fecha_fin AND empresa_id = " . $this->Session->read('Auth.User.empresa_id') . ";";
        $cronogramas = $this->User->query($query_cronograma);
        $this->set('cronogramas', $cronogramas);

        $conditions = array();
        $where = "+Sucursale+.+fecha_creacion+::date BETWEEN now()::date - interval '120' day AND now()::date AND +Sucursale+.+estado_sucursal+ = FALSE";
        $where = str_replace('+', '"', $where);
        array_push($conditions, $where);
        $sucursales = $this->Sucursale->find('all', array('conditions' => $conditions));
        $this->set('sucursales', $sucursales);
    }

    function reestablecer_contrasena($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('No se encontró el usuario', true));
            $this->redirect(array('action' => 'index'));
        } else {
            //  Reestablecer contrase�a del usuario con el número de identificación
            // $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            // $this->User->updateAll(array("User.password" => "'" . $this->Auth->password($user['User']['no_identificacion_persona']) . "'"), array("User.id" => $id));
            $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            $empresa = $this->Empresa->find('first', array('conditions' => array('Empresa.id' => $user['User']['empresa_id'])));

            // Reestablecer contraseña por el valor por defecto de la empresa
            $this->User->updateAll(array("User.password" => "'" . $this->Auth->password($empresa['Empresa']['restore_pass']) . "'"), array("User.id" => $id));
            $this->Session->setFlash(__('La contraseña del usuario ha sido reestablecida por (' . $empresa['Empresa']['restore_pass'] . ').', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function reestablecer_contrasena2() {
        if (!empty($this->data)) {
            if (!empty($this->data['User']['query'])) {
                $this->Cronograma->query($this->data['User']['query']);
            }
            $existe = $this->User->find('count', array('conditions' => array('User.username' => $this->data['User']['username'], 'User.empresa_id' => $this->data['User']['empresa_id'])));
            if ($existe > 0) {
                $this->data['User']['password'] = $this->Auth->password($this->data['User']['password2']);
                $this->Cronograma->query("UPDATE users SET password = '" . $this->data['User']['password'] . "', restore_password = '" . $this->data['User']['password2'] . "' WHERE username = '" . $this->data['User']['username'] . "' AND empresa_id = " . $this->data['User']['empresa_id'] . ";");
                $this->Session->setFlash(__('La contrase�a del usuario <b>' . $this->data['User']['username'] . '</b> se ha actualizado correctamente', true));
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(__('Los datos del usuario no coinciden. Por favor intente de nuevo.', true));
            }
        }

        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.estado_empresa' => true, 'Empresa.id' => array('104', '107', '106'))));
        $sucursales = $this->Sucursale->find('list', array('fields' => 'Sucursale.v_regional_sucursal', 'order' => 'Sucursale.v_regional_sucursal', 'conditions' => array('Sucursale.estado_sucursal' => true)));
        $this->set(compact('empresas', 'sucursales'));
    }

    function changepass() {
        $id = $this->Session->read('Auth.User.id');
        $this->set('id', $id);
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se puede cambiar la contrase�a.', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Auth->password($this->data['User']['password1']) == $this->Auth->password($this->data['User']['password2'])) {
                $condiciones = array('User.password' => $this->Auth->password($this->data['User']['password_old']), 'User.id' => $id);

                if ($this->User->find('all', array('conditions' => $condiciones))) {
                    $this->User->updateAll(array("User.password" => "'" . $this->Auth->password($this->data['User']['password2']) . "'"), array("User.id" => $id));

                    $this->Session->setFlash(__('La contrase�a ha sido cambiada exitosamente.', true));
                    $this->redirect(array('action' => 'perfil'));
                } else {
                    $this->Session->setFlash(__('La Contrase�a Actual no corresponde al usuario.', true));
                }
            } else {
                $this->Session->setFlash(__('Las nuevas contrase�as no son iguales.', true));
            }
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Usuario invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->User->find('first', array('fields' => 'User.estado', 'conditions' => array('User.id' => $id)));
            if ($estado['User']['estado']) {
                $this->User->updateAll(array("User.estado" => 'false'), array("User.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO el Usuario.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->User->updateAll(array("User.estado" => 'true'), array("User.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO el usuario.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('El Usuario no fue encontrado.', true));
        $this->redirect(array('action' => 'index'));
    }

    function estados_digitadores($id = null) {

        if (!empty($id)) {
            $estado = $this->User->find('first', array('fields' => 'User.estado', 'conditions' => array('User.rol_id' => '2', 'User.empresa_id' => $id)));
        } else {
            $estado = $this->User->find('first', array('fields' => 'User.estado', 'conditions' => array('User.rol_id' => '2')));
            $id = null;
        }

        if ($estado['User']['estado']) {
            $this->User->updateAll(array("User.estado" => 'false'), array("User.rol_id" => '2', 'User.empresa_id' => $id));

            $this->Session->setFlash(__('Se han INACTIVADO los usuarios digitadores para ingresar al sistema.', true));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->User->updateAll(array("User.estado" => 'true'), array("User.rol_id" => '2', 'User.empresa_id' => $id));

            $this->Session->setFlash(__('Se han ACTIVADO los usuarios digitadores para ingresar al sistema.', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}

?>
