<?php

class ProveedoresController extends AppController {

    var $name = 'Proveedores';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Proveedore', 'Municipio', 'Departamento', 'TipoRegimene', 'TipoFormasPago', 'TipoCategoria');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';

        $authorize = $this->Permisos->Allow('Proveedores', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Proveedores', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        $conditions = array('Proveedore.estado' => true);
        $this->Proveedore->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Proveedore']['nombre_proveedor'])) {
                $where = "+Proveedore+.+nombre_proveedor+ ilike '%" . $this->data['Proveedore']['nombre_proveedor'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }

            if (!empty($this->data['Proveedore']['persona_contacto'])) {
                $where = "+Proveedore+.+persona_contacto+ ilike '%" . $this->data['Proveedore']['persona_contacto'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Proveedore']['nit_proveedor'])) {
                $where = "+Proveedore+.+nit_proveedor+ ilike '%" . $this->data['Proveedore']['nit_proveedor'] . "%'";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->Proveedore->recursive = 0;
            $this->set('proveedores', $this->paginate($conditions));
        } else {
            $this->Proveedore->recursive = 0;
            $this->set('proveedores', $this->paginate($conditions));
        }
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Proveedor incorrecto', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('proveedore', $this->Proveedore->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Proveedore->create();
            if ($this->Proveedore->save($this->data)) {
                $this->Session->setFlash(__('El Proveedor fue guardado correctamente.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El Proveedor no se pudo guardar. Por favor intente de nuevo.', true));
            }
        }
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Proveedore->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $tipoRegimenes = $this->Proveedore->TipoRegimene->find('list', array('fields' => 'TipoRegimene.tipo_regimen_nombre', 'order' => 'TipoRegimene.tipo_regimen_nombre'));
        $tipoFormasPagos = $this->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        $tipoCategorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $this->set(compact('municipios', 'tipoRegimenes', 'departamentos', 'tipoFormasPagos'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Proveedor incorrecto', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Proveedore->save($this->data)) {
                $this->Session->setFlash(__('El Proveedor fue guardado correctamente.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El Proveedor no se pudo guardar. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Proveedore->read(null, $id);
        }
        $departamentos = $this->Departamento->find('list', array('fields' => 'Departamento.nombre_departamento', 'order' => 'Departamento.nombre_departamento'));
        $municipios = $this->Proveedore->Municipio->find('list', array('fields' => 'Municipio.nombre_municipio', 'order' => 'Municipio.nombre_municipio'));
        $tipoRegimenes = $this->Proveedore->TipoRegimene->find('list', array('fields' => 'TipoRegimene.tipo_regimen_nombre', 'order' => 'TipoRegimene.tipo_regimen_nombre'));
        $tipoFormasPagos = $this->TipoFormasPago->find('list', array('fields' => 'TipoFormasPago.nombre_forma_pago', 'order' => 'TipoFormasPago.nombre_forma_pago'));
        $tipoCategorias = $this->TipoCategoria->find('list', array('fields' => 'TipoCategoria.tipo_categoria_descripcion', 'order' => 'TipoCategoria.tipo_categoria_descripcion'));
        $this->set(compact('municipios', 'tipoRegimenes', 'departamentos', 'tipoFormasPagos'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Id incorrecto para el proveedor', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($id > 0) {
            $estado = $this->Proveedore->find('first', array('fields' => 'Proveedore.estado', 'conditions' => array('Proveedore.id' => $id)));
            if ($estado['Proveedore']['estado']) {
                $this->Proveedore->updateAll(array("Proveedore.estado" => 'false'), array("Proveedore.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO al proveedor.', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Proveedore->updateAll(array("Proveedore.estado" => 'true'), array("Proveedore.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO al proveedor.', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('El Proveedor no fue modificado.', true));
        $this->redirect(array('action' => 'index'));
    }

}
