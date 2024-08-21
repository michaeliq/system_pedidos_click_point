<?php

class SucursalesController extends AppController
{

    var $name = 'Sucursales';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array(
        'Sucursale', 'Regionale', 'Departamento', 'Municipio', 'Empresa', 'Plantilla', 'SucursalesPlantilla',
        'SucursalesPresupuestosPedido', 'TipoPedido', 'LocalidadRelRuta'
    );

    function isAuthorized()
    {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Sucursales', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Sucursales', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index($id = null)
    {

        $conditions = array('Sucursale.id_empresa' => $id);

        $this->Sucursale->set($this->data);

        if (!empty($this->data)) {
            if (!empty($this->data['Sucursale']['nombre_sucursal'])) {
                $where = "upper(+Sucursale+.+nombre_sucursal+) LIKE '%" . strtoupper($this->data['Sucursale']['nombre_sucursal']) . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Sucursale']['oi_sucursal'])) {
                $where = "+Sucursale+.+oi_sucursal+ LIKE '%" . $this->data['Sucursale']['oi_sucursal'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Sucursale']['ceco_sucursal'])) {
                $where = "+Sucursale+.+ceco_sucursal+ LIKE '%" . $this->data['Sucursale']['ceco_sucursal'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Sucursale']['regional_id'])) {
                $where = "+Sucursale+.+regional_id+ = " . $this->data['Sucursale']['regional_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            //            if (!empty($this->data['Sucursale']['regional_sucursal'])) {
            //                $where = "+Sucursale+.+regional_sucursal+ = '" . $this->data['Sucursale']['regional_sucursal'] . "'";
            //                $where = str_replace('+', '"', $where);
            //                array_push($conditions, $where);
            //            }
            if (!empty($this->data['Sucursale']['plantilla_id'])) {
                $where = "+Sucursale+.+plantilla_id+ like '%" . $this->data['Sucursale']['plantilla_id'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->paginate = array('limit' => '150', 'order' => array('Sucursale.oi_sucursal' => 'asc'));
            $this->set('sucursals', $this->paginate($conditions));
        } else {
            // $conditions = array(/* 'Sucursale.estado_sucursal' => true, */ 'Sucursale.id_empresa' => $id);
            $this->Sucursale->recursive = 0;
            $this->paginate = array('limit' => '150', 'order' => array('Sucursale.oi_sucursal' => 'asc'));

            $this->set('sucursals', $this->paginate($conditions));
        }

        $this->set('id_empresa', $id);
        $this->set('empresa', $this->Empresa->find('all', array('fields' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => $id))));

        /* $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
          $regional = array();
          foreach ($regional_data as $value) {
          $regional[$value['Sucursale']['regional_sucursal']] = $value['Sucursale']['regional_sucursal'];
          }
          /* $regional = array('ANT' => 'ANT',
          'BUC' => 'BUC',
          'CEN' => 'CEN',
          'NOR' => 'NOR',
          'OCC' => 'OCC',
          'SAB' => 'SAB',
          'SUR' => 'SUR'); */
        /* $this->set('regional', $regional); */

        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', array('conditions' => array('Regionale.estado' => true))));
        $plantillas = $this->Plantilla->find('list', array('fields' => 'Plantilla.nombre_plantilla', 'order' => 'Plantilla.nombre_plantilla', 'conditions' => array('Plantilla.estado_plantilla' => true)));
        $this->set(compact('plantillas', 'regionales'));
    }

    function add($id = null)
    {

        //31052018
        $tipo_pedidos = $this->TipoPedido->find('all', array('conditions' => array('TipoPedido.estado' => true))); //31052018
        $this->set('tipo_pedidos', $tipo_pedidos);

        if (!empty($this->data)) {
            $this->Sucursale->create();
            $this->data['Sucursale']['plantilla_id'] = implode(",", $this->data['Sucursale']['plantilla_id2']);
            $this->data['Sucursale']['regional_sucursal'] = strtoupper($this->data['Sucursale']['regional_sucursal']);
            if ($this->Sucursale->save($this->data)) {

                $empresa_id = $this->data['Sucursale']['id_empresa'];
                $sql_parametro_precio = "UPDATE sucursales SET parametro_precio = empresas.parametro_precio 
                                    FROM empresas
                                    WHERE empresas.id = sucursales.id_empresa
                                    AND sucursales.id_empresa = " . $empresa_id . ";";
                $this->Empresa->query($sql_parametro_precio);

                $sucursale_id = $this->Sucursale->getInsertID();
                // Eliminar las plantillas asociadas a la sucursal
                $delete = "DELETE FROM sucursales_plantillas WHERE sucursale_id =" . $sucursale_id . ";";
                $this->SucursalesPlantilla->query($delete);

                foreach ($this->data['Sucursale']['plantilla_id2'] as $plantilla) :

                    // Insertar las plantillas asociadas a la sucursal
                    $this->SucursalesPlantilla->create();
                    $sucursales_plantilla = array(
                        'sucursale_id' => $sucursale_id,
                        'plantilla_id' => $plantilla
                    );
                    $this->SucursalesPlantilla->save($sucursales_plantilla, FALSE);

                    // Insertar las plantillas para que ADMIN pueda ingresar
                    $this->SucursalesPlantilla->create();
                    $sucursales_plantilla = array(
                        'sucursale_id' => '1',
                        'plantilla_id' => $plantilla
                    );
                endforeach;

                foreach ($tipo_pedidos as $tipo_pedido) :
                    if (empty($this->data['Sucursale']['presupuesto_id_' . $tipo_pedido['TipoPedido']['id']])) {
                        $this->SucursalesPresupuestosPedido->create();
                        $presupuesto = array(
                            'sucursal_id' => $sucursale_id,
                            'tipo_pedido_id' => $this->data['Sucursale']['tipo_pedido_id_' . $tipo_pedido['TipoPedido']['id']],
                            'presupuesto_asignado' => $this->data['Sucursale']['presupuesto_asignado_' . $tipo_pedido['TipoPedido']['id']],
                            'fecha_presupuesto_pedido' => 'now()'
                        );
                    } else {
                        $presupuesto = array(
                            'sucursal_id' => $sucursale_id,
                            'id' => $this->data['Sucursale']['presupuesto_id_' . $tipo_pedido['TipoPedido']['id']],
                            'tipo_pedido_id' => $this->data['Sucursale']['tipo_pedido_id_' . $tipo_pedido['TipoPedido']['id']],
                            'presupuesto_asignado' => $this->data['Sucursale']['presupuesto_asignado_' . $tipo_pedido['TipoPedido']['id']],
                            'fecha_presupuesto_pedido' => 'now()'
                        );
                    }
                    $this->SucursalesPresupuestosPedido->save($presupuesto);
                endforeach;
                $this->Session->setFlash(__('Se ha creado la sucursal ' . $this->data['Sucursale']['nombre_sucursal'] . '. ', true));
                // $this->redirect(array('action' => 'index/' . $this->data['Sucursale']['id_empresa']));
                header("Location: index/" . $this->data['Sucursale']['id_empresa']);
            } else {
                $this->Session->setFlash(__('La sucursal no pudo ser salvada. Por favor intente de nuevo.', true));
            }
        }

        /* $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale. regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
          $regional = '';
          foreach ($regional_data as $value) {
          $regional = $regional . '"' . $value['Sucursale']['regional_sucursal'] . '",';
          }
          $this->set('regional', $regional);
           */


        $sql_plantillas = "SELECT id FROM plantillas WHERE (estado_plantilla= true AND empresa_id = " . $id . ") OR todas_empresas = true;";
        $result = $this->Empresa->query($sql_plantillas);
        // print_r($result);
        $array_plantillas = array();
        foreach ($result as $value) {
            array_push($array_plantillas, $value[0]['id']);
        }

        $telefonica = array(248, 768, 827, 828, 657, 862, 684);
        if (array_search($this->data['Sucursale']['id_empresa'], $telefonica)) {
            array_push($array_plantillas, 919);
        }

        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', 'conditions' => array('Regionale.estado_regional' => true, 'Regionale.empresa_id' => $id)));
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $plantillas = $this->Plantilla->find('list', array(
            'fields' => 'Plantilla.nombre_plantilla', 'order' => 'Plantilla.nombre_plantilla', 'conditions' =>
            array('Plantilla.id' => $array_plantillas)
        ));
        $this->set(compact('departamentos', 'municipios', 'plantillas', 'regionales'));
        $this->set('id_empresa', $id);
        $this->set('empresa', $this->Empresa->find('all', array('fields' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => $id))));
    }

    function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró la sucursal', true));
            // $this->redirect(array('action' => 'index'));
            header("Location: ../index");
        }
        //31052018
        $tipo_pedidos = $this->TipoPedido->find('all', array('conditions' => array('TipoPedido.estado' => true))); //31052018
        $this->set('tipo_pedidos', $tipo_pedidos);
        //31052018
        $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $id)));
        $this->set('presupuestos', $presupuestos);

        if (!empty($this->data)) {
            $this->data['Sucursale']['plantilla_id'] = implode(",", $this->data['Sucursale']['plantilla_id2']);
            $this->data['Sucursale']['regional_sucursal'] = strtoupper($this->data['Sucursale']['regional_sucursal']);
            $this->data['Sucursale']['localidad_rel_rutas_id'] = $this->data["Sucursale"]["localidad_ruta_id"];
            if ($this->Sucursale->save($this->data)) {
                $empresa_id = $this->data['Sucursale']['id_empresa'];
                $sql_parametro_precio = "UPDATE sucursales SET parametro_precio = empresas.parametro_precio 
                                    FROM empresas
                                    WHERE empresas.id = sucursales.id_empresa
                                    AND sucursales.id_empresa = " . $empresa_id . ";";
                $this->Empresa->query($sql_parametro_precio);

                $sucursale_id = $id;
                // Eliminar las plantillas asociadas a la sucursal
                $delete = "DELETE FROM sucursales_plantillas WHERE sucursale_id =" . $sucursale_id . ";";
                $this->SucursalesPlantilla->query($delete);
                foreach ($this->data['Sucursale']['plantilla_id2'] as $plantilla) :
                    // Insertar las plantillas asociadas a la sucursal
                    $this->SucursalesPlantilla->create();
                    $sucursales_plantilla = array(
                        'sucursale_id' => $sucursale_id,
                        'plantilla_id' => $plantilla
                    );
                    $this->SucursalesPlantilla->save($sucursales_plantilla, FALSE);

                    // Insertar las plantillas para que ADMIN pueda ingresar
                    $this->SucursalesPlantilla->create();
                    $sucursales_plantilla = array(
                        'sucursale_id' => '1',
                        'plantilla_id' => $plantilla
                    );
                endforeach;

                //31052018
                foreach ($tipo_pedidos as $tipo_pedido) :
                    if (empty($this->data['Sucursale']['presupuesto_id_' . $tipo_pedido['TipoPedido']['id']])) {
                        $this->SucursalesPresupuestosPedido->create();
                        $presupuesto = array(
                            'sucursal_id' => $sucursale_id,
                            'tipo_pedido_id' => $this->data['Sucursale']['tipo_pedido_id_' . $tipo_pedido['TipoPedido']['id']],
                            'presupuesto_asignado' => $this->data['Sucursale']['presupuesto_asignado_' . $tipo_pedido['TipoPedido']['id']],
                            'fecha_presupuesto_pedido' => 'now()'
                        );
                    } else {
                        $presupuesto = array(
                            'sucursal_id' => $sucursale_id,
                            'id' => $this->data['Sucursale']['presupuesto_id_' . $tipo_pedido['TipoPedido']['id']],
                            'tipo_pedido_id' => $this->data['Sucursale']['tipo_pedido_id_' . $tipo_pedido['TipoPedido']['id']],
                            'presupuesto_asignado' => $this->data['Sucursale']['presupuesto_asignado_' . $tipo_pedido['TipoPedido']['id']],
                            'fecha_presupuesto_pedido' => 'now()'
                        );
                    }
                    $this->SucursalesPresupuestosPedido->save($presupuesto);
                endforeach;

                $this->Session->setFlash(__('La sucursal se ha actualizado exitosamente', true));
                // $this->redirect(array('action' => 'index/' . $this->data['Sucursale']['id_empresa']));
                header("Location: ../index/" . $this->data['Sucursale']['id_empresa']);
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Sucursale->read(null, $id);

            $selected = array();
            $plantillas = $this->SucursalesPlantilla->find('all', array('fields' => 'plantilla_id', 'conditions' => array('sucursale_id' => $id)));
            foreach ($plantillas as $plantilla) :
                array_push($selected, $plantilla['SucursalesPlantilla']['plantilla_id']);
            endforeach;

            $this->set('selected', $selected);
        }

        /* $regional_data = $this->Sucursale->find('all', array('fields' => array('DISTINCT Sucursale.regional_sucursal', 'Sucursale.regional_sucursal'), 'group' => 'Sucursale.regional_sucursal', 'order' => 'Sucursale.regional_sucursal'));
          $regional = '';
          foreach ($regional_data as $value) {
          $regional = $regional . '"' . $value['Sucursale']['regional_sucursal'] . '",';
          }
          $this->set('regional', $regional); */


        $sql_plantillas = "SELECT id FROM plantillas WHERE (estado_plantilla= true AND empresa_id = " . $this->data['Sucursale']['id_empresa'] . ") OR todas_empresas = true;";
        $result = $this->Empresa->query($sql_plantillas);
        // print_r($result);
        $array_plantillas = array();
        foreach ($result as $value) {
            array_push($array_plantillas, $value[0]['id']);
        }
        // print_r($array_plantillas);

        $telefonica = array(248, 768, 827, 828, 657, 862, 684);
        if (array_search($this->data['Sucursale']['id_empresa'], $telefonica)) {
            array_push($array_plantillas, 919);
        }

        $localidades = $this->LocalidadRelRuta->find('list', array('fields' => 'LocalidadRelRuta.nombre_rel', "order" => 'LocalidadRelRuta.nombre_rel' ,"conditions" => array(
            "LocalidadRelRuta.codigo_sirbe" => $id
        )));
        $sucursal_localidad = $this->Sucursale->find("first",array("conditions" => ["Sucursale.id" => $id], "fields" => ["localidad_rel_rutas_id"]));
        $localidad = $this->LocalidadRelRuta->find('list', 
        array(
            'fields' => 'LocalidadRelRuta.nombre_rel', 
            "order" => 'LocalidadRelRuta.nombre_rel' ,
            "conditions" => array(
                "LocalidadRelRuta.id" => $sucursal_localidad["Sucursale"]["localidad_rel_rutas_id"],
            )));
            
        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', 'conditions' => array('Regionale.estado_regional' => true, 'Regionale.empresa_id' => $this->data['Sucursale']['id_empresa'])));
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $empresas = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa'));
        $plantillas = $this->Plantilla->find('list', array(
            'fields' => 'Plantilla.nombre_plantilla', 'order' => 'Plantilla.nombre_plantilla', 'conditions' =>
            //array('Plantilla.estado_plantilla' => true, 'Plantilla.empresa_id' => $this->data['Sucursale']['id_empresa'])
            array('Plantilla.id' => $array_plantillas)
        ));
        $this->set(compact('departamentos', 'municipios', 'empresas', 'plantillas', 'regionales', "localidades","localidad"));

        $this->set('empresa', $this->Empresa->find('all', array('fields' => 'Empresa.nombre_empresa', 'conditions' => array('Empresa.id' => $this->data['Sucursale']['id_empresa']))));
    }

    function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('No se encontró la sucursal', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('sucursal', $this->Sucursale->read(null, $id));
        $plantillas = $this->SucursalesPlantilla->find('all', array('conditions' => array('sucursale_id' => $id)));
        $this->set('plantillas', $plantillas);
    }

    function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Sucursale invalida.', true));
            /* $this->redirect(array('action' => 'index')); */
            header("Location: index");
        }
        if ($id > 0) {
            $estado = $this->Sucursale->find('first', array('fields' => array('Sucursale.id_empresa', 'Sucursale.estado_sucursal'), 'conditions' => array('Sucursale.id' => $id)));
            if ($estado['Sucursale']['estado_sucursal']) {
                $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'false'), array("Sucursale.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO la sucursal.', true));
                $this->redirect(array('action' => 'index/' . $estado['Sucursale']['id_empresa']));
            } else {
                $this->Sucursale->updateAll(array("Sucursale.estado_sucursal" => 'true'), array("Sucursale.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO la sucursal.', true));
                $this->redirect(array('action' => 'index/' . $estado['Sucursale']['id_empresa']));
            }
        }
        $this->Session->setFlash(__('La sucursal no fue encontrada.', true));
        /* $this->redirect(array('action' => 'index')); */
        header("Location: index");
    }

    function presupuesto($id = null)
    {
        if (!empty($this->data)) {
            $this->SucursalesPresupuestosPedido->create();
            if ($this->SucursalesPresupuestosPedido->save($this->data)) {
                $this->Session->setFlash(__('Se ha agregado el presupuesto para el tipo de pedido seleccionado.', true));
            } else {
                $this->Session->setFlash(__('El tipo de pedido ya tiene asignado un presupuesto.', true));
            }
        }

        $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('sucursal_id' => $id)));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('tipoPedido'));
        $this->set('sucursal_id', $id);
        $this->set('presupuestos', $presupuestos);
    }

    function presupuesto_edit($id = null)
    {

        if (!empty($this->data)) {
            if ($this->SucursalesPresupuestosPedido->save($this->data)) {
                $sucursale_id = $this->data['SucursalesPresupuestosPedido']['sucursal_id'];
                $this->Session->setFlash(__('El presupuesto de la sucursal se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'presupuesto/' . $sucursale_id));
            } else {
                $this->Session->setFlash(__('No se logro actualizar la informacion. Por favor intente de nuevo.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->SucursalesPresupuestosPedido->read(null, $id);
        }
        $presupuestos = $this->SucursalesPresupuestosPedido->find('all', array('conditions' => array('SucursalesPresupuestosPedido.id' => $id)));
        $tipoPedido = $this->TipoPedido->find('list', array('fields' => 'TipoPedido.nombre_tipo_pedido', 'order' => 'TipoPedido.nombre_tipo_pedido', 'conditions' => array('TipoPedido.estado' => true)));
        $this->set(compact('tipoPedido'));
        $this->set('id', $id);
        $this->set('presupuestos', $presupuestos);
    }

    function upload_sucursales_from_file($id_empresa = null)
    {
        date_default_timezone_set('America/Bogota');
        $dir_file = 'sucursales/masivos/';
        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }
        $max_file = 20145728;
        $sucursales_validas = array();

        $regionales = $this->Regionale->find('list', array('fields' => 'Regionale.nombre_regional', 'order' => 'Regionale.nombre_regional', 'conditions' => array('Regionale.estado_regional' => true, 'Regionale.empresa_id' => $id_empresa)));
        $this->set("regionales",$regionales);
        $sucursales = $this->Sucursale->find('all', array(
            "conditions" => ["Sucursale.id_empresa" => $id_empresa],
            "fields" => ["Sucursale.id","Sucursale.ceco_sucursal","Sucursale.oi_sucursal","Sucursale.nombre_sucursal","Sucursale.departamento_id","Sucursale.municipio_id","Sucursale.direccion_sucursal","Sucursale.telefono_sucursal","Sucursale.email_sucursal","Sucursale.nombre_contacto","Sucursale.telefono_contacto","Sucursale.email_contacto"]
        ));

        if (!is_dir($dir_file)) {
            mkdir($dir_file, 0777, true);
        }
        $this->set(compact("sucursales"));

        if ($this->RequestHandler->isPost()) {
            
            $tipo_pedidos = $this->TipoPedido->find('all', array('conditions' => array('TipoPedido.estado' => true)));
            $regional = $this->Regionale->find('first', array('fields' => ['Regionale.nombre_regional','Regionale.id'], 'conditions' => array('Regionale.estado_regional' => true, 'Regionale.id' => $this->data["Sucursale"]["regional"])));

            if ($this->data['Sucursale']['archivo_csv']['name']) {
                if (($this->data['Sucursale']['archivo_csv']['type'] == 'text/csv') || ($this->data['Sucursale']['archivo_csv']['type'] == 'application/vnd.ms-excel')) {
                    if ($this->data['Sucursale']['archivo_csv']['size'] < $max_file) {
                        move_uploaded_file($this->data['Sucursale']['archivo_csv']['tmp_name'], $dir_file . '/' . $this->data['Sucursale']['archivo_csv']['name']);
                        $file = fopen($dir_file . '/' . $this->data['Sucursale']['archivo_csv']['name'], 'r');
                        if ($file) {
                            $row = 0;
                            $headers = [];
                            while (($data = fgetcsv($file, null, ";")) !== FALSE) {
                                $sql_query_instert_array = array();
                                if ($row == 0) {
                                    $headers = $data;
                                } else {
                                    $data_sucursale = array();
                                    for ($i = 0; $i < count($headers); $i++) {
                                        $data_sucursale[$headers[$i]] = utf8_encode($data[$i]);
                                    }
                                    $localidad_from_db = $this->Sucursale->find("first", array(
                                        'conditions' => array(
                                            'Sucursale.id' => $data_sucursale["ID"]
                                        )
                                    ));

                                    if ($localidad_from_db) {
                                        $data_sucursale["existe"] = true;
                                    } else {
                                        $data_sucursale["existe"] = false;
                                    }

                                    $sql_query_instert_array = array(
                                        "Sucursale" => array(
                                            "id_empresa" => $this->data["Sucursale"]["empresa_id"],
                                            "id" => $data_sucursale["ID"],
                                            "nombre_sucursal" => $data_sucursale["SUCURSAL"],
                                            "departamento_id" => $data_sucursale["DEPARTAMENTO"],
                                            "municipio_id" => $data_sucursale["MUNICIPIO"],
                                            "direccion_sucursal" => $data_sucursale["DIRECCION_SUCURSAL"],
                                            "telefono_sucursal" => $data_sucursale["TELEFONO_SUCURSAL"],
                                            "email_sucursal" => $data_sucursale["CORREO_SUCURSAL"],
                                            "oi_sucursal" => $data_sucursale["OI"],
                                            "ceco_sucursal" => $data_sucursale["CECO"],
                                            "regional_id" => $regional["Regionale"]["id"],
                                            "regional_sucursal" => $regional["Regionale"]["nombre_regional"],
                                            "nombre_contacto" => $data_sucursale["NOMBRE_CONTACTO"],
                                            "telefono_contacto" => $data_sucursale["TELEFONO_CONTACTO"],
                                            "email_contacto" => $data_sucursale["CORREO_CONTACTO"],
                                        )
                                    );
                                    array_push($sucursales_validas, $sql_query_instert_array);
                                    $this->Sucursale->create();
                                    $this->Sucursale->save($sql_query_instert_array);
                                    $sucursale_id = null;
                                    if($this->Sucursale->getInsertID()){
                                        $sucursale_id = $this->Sucursale->getInsertID();
                                    }else{
                                        $sucursale_id = $data_sucursale["ID"];
                                    }
                                    $presupuestos = array();

                                    foreach ($tipo_pedidos as $tipo_pedido) :
                                        if (!empty($data_sucursale[$tipo_pedido["TipoPedido"]["nombre_tipo_pedido"]])) {
                                            $presupuesto = $this->SucursalesPresupuestosPedido->find("first",array(
                                                "conditions" => array(
                                                    "sucursal_id" => $sucursale_id,
                                                    "tipo_pedido_id" => $tipo_pedido["TipoPedido"]["id"]
                                                ),
                                                "fields" => ["id"]
                                            ));
                                            
                                            $data_presupuesto = array(
                                                "SucursalesPresupuestosPedido" => array(
                                                    'id' => $presupuesto["SucursalesPresupuestosPedido"]["id"],
                                                    'sucursal_id' => $sucursale_id,
                                                    'tipo_pedido_id' => $tipo_pedido["TipoPedido"]["id"],
                                                    'presupuesto_asignado' => $data_sucursale[$tipo_pedido["TipoPedido"]["nombre_tipo_pedido"]],
                                                    'fecha_presupuesto_pedido' => 'now()'
                                                )
                                            );
                                            //debug($data_presupuesto);
                                            array_push($presupuestos,$data_presupuesto);
                                        };
                                    endforeach;
                                    if(count($presupuestos) > 0){
                                        
                                        $this->SucursalesPresupuestosPedido->saveAll($presupuestos);
                                    }
                                }
                                $row++;
                            }
                        }


                        $this->set("sucursales_validas", $sucursales_validas);
                        $this->set('empresa', $this->Empresa->find('first', array('fields' => ['Empresa.nombre_empresa','Empresa.id'], 'conditions' => array('Empresa.id' => $id_empresa))));
                        fclose($file);
                        unlink($dir_file . '/' . $this->data['Sucursale']['archivo_csv']['name']);
                        $this->redirect("/sucursales/index/".$this->data["Sucursale"]["empresa_id"]);
                    } else {
                        $this->set("sucursales_validas", $sucursales_validas);
                        $this->set('empresa', $this->Empresa->find('first', array('fields' => ['Empresa.nombre_empresa','Empresa.id'], 'conditions' => array('Empresa.id' => $id_empresa))));
                        $this->Session->setFlash('El tamaño del archivo supera el maximo establecido (20MB).', 'flash_failure');
                        $this->redirect("/sucursales/upload_sucursales_from_file/".$this->data["Sucursale"]["empresa_id"]);
                    }
                } else {
                    $this->set("sucursales_validas", $sucursales_validas);
                    $this->set('empresa', $this->Empresa->find('first', array('fields' => ['Empresa.nombre_empresa','Empresa.id'], 'conditions' => array('Empresa.id' => $id_empresa))));
                    $this->Session->setFlash('El tipo de archivo no es el admitido para este proceso.', 'flash_failure');
                    $this->redirect("/sucursales/upload_sucursales_from_file/".$this->data["Sucursale"]["empresa_id"]);
                }
            } else {
                $this->set("sucursales_validas", $sucursales_validas);
                $this->set('empresa', $this->Empresa->find('first', array('fields' => ['Empresa.nombre_empresa','Empresa.id'], 'conditions' => array('Empresa.id' => $id_empresa))));
                $this->Session->setFlash('Hubo un error al cargar el archivo. Verifique y vuelva a intentar.', 'flash_failure');
                $this->redirect("/sucursales/upload_sucursales_from_file/".$this->data["Sucursale"]["empresa_id"]);
            }
        } else {
            $this->set("sucursales_validas", $sucursales_validas);
            $this->set('empresa', $this->Empresa->find('first', array('fields' => ['Empresa.nombre_empresa','Empresa.id'], 'conditions' => array('Empresa.id' => $id_empresa))));
        }
    }
}
