<?php

class SolicitudesController extends AppController {

    var $name = 'Solicitudes';
    var $helpers = array('Html', 'Ajax', 'Javascript');
    var $components = array('RequestHandler', 'Auth', 'Permisos');
    var $uses = array('Solicitud', 'SolicitudesDetalle', 'TipoSolicitude', 'TipoEstadoSolicitud', 'TipoMotivosSolicitud', 'Empresa', 'EmpresasAprobadore', 'User');

    function isAuthorized() {
        $this->Auth->authorize = 'controller';
        $authorize = $this->Permisos->Allow('Solicitudes', $this->Session->read('Auth.User.rol_id'));
        $this->set('authorize', $authorize);
        if (in_array($this->action, $authorize)) {
            return true;
        }

        $no_authorize = $this->Permisos->Deny('Solicitudes', $this->Session->read('Auth.User.rol_id'));
        if (in_array($this->action, $no_authorize)) {
            $this->Session->setFlash(__('No tiene los suficientes permisos para ver esta pantalla.', true));
            return false;
        }
    }

    function index() {
        
    }

    function listar_solicitudes() {
        ini_set('memory_limit', '512M');
        $permisos = $this->EmpresasAprobadore->find('all', array('conditions' => array('EmpresasAprobadore.user_id' => $this->Session->read('Auth.User.id'))));
        $empresas_permisos = array();
        foreach ($permisos as $permiso) {
            array_push($empresas_permisos, $permiso['EmpresasAprobadore']['empresa_id']);
        }
        $conditions_empresa = array('id' => $empresas_permisos);

        if ($this->Session->read('Auth.User.rol_id') == 1 || $this->Session->read('Auth.User.rol_id') == 6 || $this->Session->read('Auth.User.rol_id') == 13) {
            $conditions = array();
        } else {
            // $conditions = array('Solicitud.user_id' => $this->Session->read('Auth.User.id'));
            $conditions = array(
                'OR' => array(
                    array('Solicitud.user_id' => $this->Session->read('Auth.User.id')),
                    array('Solicitud.user_id_asignado' => $this->Session->read('Auth.User.id')),
                )
            );
        }

        $this->Solicitud->set($this->data);
        if (!empty($this->data)) {
            if (!empty($this->data['Solicitude']['tipo_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_solicitud_id+ = " . $this->data['Solicitude']['tipo_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['tipo_estado_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_estado_solicitud_id+ = " . $this->data['Solicitude']['tipo_estado_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['tipo_motivo_solicitud_id'])) {
                $where = "+Solicitud+.+tipo_motivo_solicitud_id+ = " . $this->data['Solicitude']['tipo_motivo_solicitud_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            if (!empty($this->data['Solicitude']['empresa_id'])) {
                $where = "+Solicitud+.+empresa_id+ = " . $this->data['Solicitude']['empresa_id'] . "";
                $where = str_replace('+', '"', $where);
                array_push($conditions, $where);
            }
            $this->set('solicitudes', $this->paginate($conditions));
        } else {
            $this->paginate = array('limit' => 500, 'order' => array(
                    'Solicitud.id' => 'desc'
            ));
            $this->set('solicitudes', $this->paginate($conditions));
        }

        $empresa = $this->Empresa->find('list', array('fields' => 'Empresa.nombre_empresa', 'order' => 'Empresa.nombre_empresa', 'conditions' => $conditions_empresa));
        $tipoSolicitud = $this->TipoSolicitude->find('list', array('fields' => 'TipoSolicitude.nombre_tipo_solicitud', 'order' => 'TipoSolicitude.nombre_tipo_solicitud', 'conditions' => array('estado' => true)));
        $tipoMotivo = $this->TipoMotivosSolicitud->find('list', array('fields' => 'TipoMotivosSolicitud.nombre_tipo_motivo', 'conditions' => array('estado' => true), 'order' => 'TipoMotivosSolicitud.nombre_tipo_motivo'));
        $tipoEstadoSolicitud = $this->TipoEstadoSolicitud->find('list', array('fields' => 'TipoEstadoSolicitud.nombre_estado_solicitud', 'order' => 'TipoEstadoSolicitud.orden', 'conditions' => array('estado' => true)));
        $this->set(compact('tipoSolicitud', 'tipoEstadoSolicitud', 'tipoMotivo', 'empresa'));
    }

    function registrar_solicitud() {
        ini_set('memory_limit', '512M');
        date_default_timezone_set('America/Bogota');
        if (!empty($this->data)) {
            $this->Solicitud->create();
            $this->data['Solicitud']['user_id'] = $this->Session->read('Auth.User.id');
            $this->data['Solicitud']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
            $this->data['Solicitud']['tipo_estado_solicitud_id'] = 1;
            $this->data['Solicitud']['fecha_registro'] = date('Y-m-d H:i:s');
            $this->data['Solicitud']['motivo_asunto'] = '-';

            /*
              // Configuración de la carga
              $mensaje = null;
              $dir_img = 'pqr/adjuntos/';
              $max_file = 3145728; // 3 MB = 3145728 byte
              // Verificar que se haya cargado un archivo
              if (!empty($this->data['Solicitud']['archivo_adjunto']['name'])) {
              // Verificar si el archivo tiene formato .zip o rar
              // .rar    application/x-rar-compressed, application/octet-stream
              // .zip    application/zip, application/octet-stream, application/x-zip-compressed, multipart/x-zip

              if ($this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-rar-compressed' ||
              $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/octet-stream' ||
              $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/zip' ||
              $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-zip-compressed' ||
              $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-zip') {
              // Verificar el tamaño del archivo
              if ($this->data['Solicitud']['archivo_adjunto']['size'] < $max_file) {
              move_uploaded_file($this->data['Solicitud']['archivo_adjunto']['tmp_name'], $dir_img . '/' . $this->data['Solicitud']['archivo_adjunto']['name']);
              $aux = explode('.', $this->data['Solicitud']['archivo_adjunto']['name']);

              rename($dir_img . $this->data['Solicitud']['archivo_adjunto']['name'], $dir_img . $this->Session->read('Auth.User.id') . '_' . date('Ymdhis') . '.' . $aux[1]);
              $this->data['Solicitud']['archivo_adjunto'] = $this->Session->read('Auth.User.id') . '_' . date('Ymdhis') . '.' . $aux[1];
              } else {
              $this->data['Solicitud']['archivo_adjunto'] = '';
              $mensaje = " - El archivo tiene un tamaño superior a las 3MB, por lo tanto no se cargó";
              }
              } else {
              $this->data['Solicitud']['archivo_adjunto'] = '';
              $mensaje = " - El archivo no tiene un formato de compresión correcto, por lo tanto no se cargó";
              }
              } else {
              $this->data['Solicitud']['archivo_adjunto'] = '';
              } */

            // Configuración de la carga de archivos multiples
            $mensaje = null;
            $dir_img = 'pqr/adjuntos/';
            $archivos = "";
            $countfiles = count($this->data['Solicitud']['archivo_adjunto']);
            $max_file = 3145728; // 3 MB = 3145728 byte
            //
            // print_r($this->data['Solicitud']['archivo_adjunto2']);
            for ($i = 0; $i < $countfiles; $i++) {
                /*
                  print_r($this->data['Solicitud']['archivo_adjunto'][$i]);
                  echo $this->data['Solicitud']['archivo_adjunto'][$i]['error'];
                  echo $this->data['Solicitud']['archivo_adjunto'][$i]['name'];
                  echo $this->data['Solicitud']['archivo_adjunto'][$i]['tmp_name'];
                  echo $this->data['Solicitud']['archivo_adjunto'][$i]['size'];
                  exit; */

                if ($this->data['Solicitud']['archivo_adjunto'][$i]['error'] == 1) {
                    $mensaje = $mensaje . " - El archivo (" . $this->data['Solicitud']['archivo_adjunto'][$i]['tmp_name'] . ") tiene un tamaño superior a las 3MB, por lo tanto no se cargó";
                } else {
                    $filename = $this->data['Solicitud']['archivo_adjunto'][$i]['name'];

                    // Upload file
                    move_uploaded_file($this->data['Solicitud']['archivo_adjunto'][$i]['tmp_name'], $dir_img . '/' . $this->data['Solicitud']['archivo_adjunto'][$i]['name']);
                    $aux = explode('.', $this->data['Solicitud']['archivo_adjunto'][$i]['name']);

                    rename($dir_img . $this->data['Solicitud']['archivo_adjunto'][$i]['name'], $dir_img . $this->Session->read('Auth.User.id') . '_' . $i . date('Ymdhis') . '.' . $aux[1]);
                    $archivos = $archivos . ';' . $this->Session->read('Auth.User.id') . '_' . $i . date('Ymdhis') . '.' . $aux[1];
                    //   move_uploaded_file($_FILES['file']['tmp_name'][$i], 'upload/' . $filename);
                    //echo $filename;
                }
            }

            $this->data['Solicitud']['archivo_adjunto'] = $archivos;

            if ($this->Solicitud->save($this->data)) {
                $solicitud = $this->Solicitud->find('all', array('conditions' => array('Solicitud.id' => $this->Solicitud->getInsertID())));
                $this->Session->setFlash(__('Se ha creado la solicitud ' . $solicitud['0']['TipoSolicitude']['nombre_tipo_solicitud'] . ' con Radicado ' . $solicitud['0']['TipoSolicitude']['sigla_solicitud'] . $this->Solicitud->getInsertID() . ' ' . $mensaje . '. ', true));
                $this->redirect(array('action' => '/index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('La solicitud no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }

        $tipoSolicitud = $this->TipoSolicitude->find('list', array('fields' => 'TipoSolicitude.nombre_tipo_solicitud', 'conditions' => array('estado' => true), 'order' => 'TipoSolicitude.nombre_tipo_solicitud'));
        $tipoMotivo = $this->TipoMotivosSolicitud->find('list', array('fields' => 'TipoMotivosSolicitud.nombre_tipo_motivo', 'conditions' => array('estado' => true), 'order' => 'TipoMotivosSolicitud.nombre_tipo_motivo'));
        $this->set(compact('tipoSolicitud', 'tipoMotivo'));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('No se encontró la solicitud referenciada', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Session->read('Auth.User.rol_id') == 1 || $this->Session->read('Auth.User.rol_id') == 6 || $this->Session->read('Auth.User.rol_id') == 11 || $this->Session->read('Auth.User.rol_id') == 13) {
            $solicitud = $this->Solicitud->find('all', array('conditions' => array('Solicitud.id' => base64_decode($id))));
            if ($solicitud['0']['Solicitud']['tipo_estado_solicitud_id'] == '7' || ($solicitud['0']['Solicitud']['tipo_estado_solicitud_id'] == '1' && !empty($solicitud['0']['Solicitud']['user_id_asignado']))) {
                $this->Solicitud->updateAll(array("Solicitud.tipo_estado_solicitud_id" => 2, "Solicitud.fecha_respuesta" => "'" . date('Y-m-d H:i:s') . "'"), array("Solicitud.id" => base64_decode($id)));
                $this->SolicitudesDetalle->create();
                $this->data['SolicitudesDetalle']['user_id'] = $this->Session->read('Auth.User.id');
                $this->data['SolicitudesDetalle']['fecha_detalle'] = date('Y-m-d H:i:s');
                $this->data['SolicitudesDetalle']['detalle_observacion'] = 'En Revisión';
                $this->data['SolicitudesDetalle']['solicitud_id'] = base64_decode($id);
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] = 7;
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] = 2;
                $this->data['SolicitudesDetalle']['archivo_adjunto'] = null;
                $this->SolicitudesDetalle->save($this->data);
            }
        } else {
            $this->Session->setFlash(__('El rol del usuario, no permite gestionar las solicitudes. Contactese con el Administrador del Sistema', true));
        }
        $this->set('solicitud', $this->Solicitud->read(null, base64_decode($id)));
        $this->set('solicitud_detalle', $this->SolicitudesDetalle->find('all', array('order' => 'SolicitudesDetalle.id DESC', 'conditions' => array('solicitud_id' => base64_decode($id)))));
    }

    function detalle_solicitud($id = null) {
        date_default_timezone_set('America/Bogota');
        $this->set('solicitud', $this->Solicitud->read(null, base64_decode($id)));
        $this->set('solicitud_detalle', $this->SolicitudesDetalle->find('all', array('order' => 'SolicitudesDetalle.id DESC', 'conditions' => array('solicitud_id' => base64_decode($id)))));

        if (!empty($this->data)) {
            $this->SolicitudesDetalle->create();

            // Configuración de la carga
            $mensaje = null;
            $dir_img = 'pqr/respuestas/';
            $max_file = 3145728; // 3 MB = 3145728 byte
            // Verificar que se haya cargado un archivo
            if (!empty($this->data['Solicitud']['archivo_adjunto']['name'])) {
                // Verificar si el archivo tiene formato .zip o rar

                /*
                  .rar    application/x-rar-compressed, application/octet-stream
                  .zip    application/zip, application/octet-stream, application/x-zip-compressed, multipart/x-zip
                 */
                if ($this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-rar-compressed' ||
                        $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/octet-stream' ||
                        $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/zip' ||
                        $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-zip-compressed' ||
                        $this->data['Solicitud']['archivo_adjunto']['type'] == 'application/x-zip') {
                    // Verificar el tamaño del archivo
                    if ($this->data['Solicitud']['archivo_adjunto']['size'] < $max_file) {
                        move_uploaded_file($this->data['Solicitud']['archivo_adjunto']['tmp_name'], $dir_img . '/' . $this->data['Solicitud']['archivo_adjunto']['name']);
                        $aux = explode('.', $this->data['Solicitud']['archivo_adjunto']['name']);

                        rename($dir_img . $this->data['Solicitud']['archivo_adjunto']['name'], $dir_img . $this->data['Solicitud']['solicitud_id'] . '_' . date('Ymdhis') . '.' . $aux[1]);
                        $this->data['SolicitudesDetalle']['archivo_adjunto'] = $this->data['Solicitud']['solicitud_id'] . '_' . date('Ymdhis') . '.' . $aux[1];
                    } else {
                        $this->data['SolicitudesDetalle']['archivo_adjunto'] = '';
                        $mensaje = " - El archivo tiene un tamaño superior a las 3MB, por lo tanto no se cargó";
                    }
                } else {
                    $this->data['SolicitudesDetalle']['archivo_adjunto'] = '';
                    $mensaje = " - El archivo no tiene un formato de compresión correcto, por lo tanto no se cargó";
                }
            } else {
                $this->data['SolicitudesDetalle']['archivo_adjunto'] = '';
            }


            $this->data['SolicitudesDetalle']['user_id'] = $this->Session->read('Auth.User.id');
            $this->data['SolicitudesDetalle']['fecha_detalle'] = date('Y-m-d H:i:s');
            $this->data['SolicitudesDetalle']['detalle_observacion'] = $this->data['Solicitud']['detalle_observacion'];
            $this->data['SolicitudesDetalle']['solicitud_id'] = $this->data['Solicitud']['solicitud_id'];
            $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] = $this->data['Solicitud']['tipo_estado_solicitud_id'];

            if ($this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] == 1) {
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] = $this->data['Solicitud']['tipo_estado_solicitud_id'];
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] = 7;
            }

            if ($this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] == 2) {
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] = 4;
            }

            if (!empty($_REQUEST['respuesta_solicitud']) && $_REQUEST['respuesta_solicitud'] == 1 && $this->data['Solicitud']['tipo_estado_solicitud_id'] == '4') {
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] = $this->data['Solicitud']['tipo_estado_solicitud_id'];
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] = 5;
            }
            if (!empty($_REQUEST['respuesta_solicitud']) && $_REQUEST['respuesta_solicitud'] == 2 && $this->data['Solicitud']['tipo_estado_solicitud_id'] == '4') {
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] = $this->data['Solicitud']['tipo_estado_solicitud_id'];
                $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] = 1;
            }


            if ($this->SolicitudesDetalle->save($this->data)) {
                // PQR ASIGNADA A USUARIO
                if ($this->data['Solicitud']['tipo_estado_solicitud_id'] == 1) {
                    $this->Solicitud->updateAll(array("Solicitud.tipo_estado_solicitud_id" => 7, "Solicitud.user_id_asignado" => $this->data['Solicitud']['user_id_asignado'], "Solicitud.fecha_respuesta" => "'" . date('Y-m-d H:i:s') . "'"), array("Solicitud.id" => $this->data['Solicitud']['solicitud_id'], "Solicitud.tipo_estado_solicitud_id" => 1));
                }
                // PQR CON RESPUESTA
                if ($this->data['Solicitud']['tipo_estado_solicitud_id'] == 2) {
                    $this->Solicitud->updateAll(array("Solicitud.tipo_estado_solicitud_id" => 4, "Solicitud.fecha_respuesta" => "'" . date('Y-m-d H:i:s') . "'"), array("Solicitud.id" => $this->data['Solicitud']['solicitud_id'], "Solicitud.tipo_estado_solicitud_id" => 2));
                }
                // PQR CON RESPUESTA ACEPTADA
                if ($this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] == 4 && $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] == 5) {
                    $this->Solicitud->updateAll(array("Solicitud.tipo_estado_solicitud_id" => 5, "Solicitud.fecha_respuesta" => "'" . date('Y-m-d H:i:s') . "'"), array("Solicitud.id" => $this->data['Solicitud']['solicitud_id'], "Solicitud.tipo_estado_solicitud_id" => 4));
                }
                // PQR CON RESPUESTA RECHAZADA
                if ($this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_1'] == 4 && $this->data['SolicitudesDetalle']['tipo_estado_solicitud_id_2'] == 1) {
                    $this->Solicitud->updateAll(array("Solicitud.tipo_estado_solicitud_id" => 1, "Solicitud.prioridad" => 'true', "Solicitud.fecha_respuesta" => "'" . date('Y-m-d H:i:s') . "'"), array("Solicitud.id" => $this->data['Solicitud']['solicitud_id'], "Solicitud.tipo_estado_solicitud_id" => 4));
                }
                $solicitud = $this->Solicitud->find('all', array('conditions' => array('Solicitud.id' => $this->data['Solicitud']['solicitud_id'])));
                $this->Session->setFlash(__('Se ha gestionado la solicitud ' . $solicitud['0']['TipoSolicitude']['nombre_tipo_solicitud'] . ' con Radicado ' . $solicitud['0']['TipoSolicitude']['sigla_solicitud'] . $this->data['Solicitud']['solicitud_id'] . '      ' . $mensaje . '. ', true));
                $this->redirect(array('action' => '/listar_solicitudes')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('La solicitud no pudo ser salvada. Por favor intente de nuevo.', true));
            }
        }

        $respuesta_solicitud = array('1' => 'Aceptar Respuesta',
            '2' => 'Rechazar Respuesta',
        );
        $this->set('respuesta_solicitud', $respuesta_solicitud);
        $userAsignar = $this->User->find('list', array('fields' => 'User.nombres_persona', 'conditions' => array('estado' => true, 'empresa_id' => '1'), 'order' => 'User.nombres_persona'));
        $this->set(compact('userAsignar'));
    }

    /*
      $this->Solicitud->set($this->data);
      if (!empty($this->data)) {
      $conditions = array();
      if (!empty($this->data['Solicitud']['nombre_vendedor'])) {
      $where = "+Solicitud+.+nombre_vendedor+ ILIKE '%" . $this->data['Solicitud']['nombre_vendedor'] . "%'";
      $where = str_replace('+', '"', $where);
      array_push($conditions, $where);
      }
      if ($this->data['Solicitud']['no_identificacion']) {
      $where = "+Solicitud+.+no_identificacion+ = " . $this->data['Solicitud']['no_identificacion'] . "";
      $where = str_replace('+', '"', $where);
      array_push($conditions, $where);
      }
      $this->Solicitud->recursive = 0;
      $this->paginate = array('limit' => 50, 'order' => array('Solicitud.estado_vendedor' => 'desc'));
      $this->set('vendedores', $this->paginate($conditions));
      } else {
      // $conditions = array('Solicitud.empresa_id' => $this->Session->read('Regional.empresa_id'));
      $conditions = array();
      $this->paginate = array('limit' => 50, 'order' => array('Solicitud.id' => 'asc'));
      $this->Solicitud->recursive = 0;
      $this->set('vendedores', $this->paginate($conditions));
      }
      $estados = array('false' => 'Inactivo', 'true' => 'Activo');
      $this->set(compact('estados'));
     *      */

    function add() {
        if (!empty($this->data)) {
            $this->Solicitud->create();
            if ($this->Solicitud->save($this->data)) {
                $this->Session->setFlash(__('Se ha creado el vendedor ' . $this->data['Solicitud']['nombre_vendedor'] . '. ', true));
                $this->redirect(array('action' => 'index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('El vendedor no pudo ser salvado. Por favor intente de nuevo.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('No se encontró el vendedor', true));
            $this->redirect(array('action' => 'index')); /* header("Location: index"); */
        }
        if (!empty($this->data)) {

            if ($this->Solicitud->save($this->data)) {
                $this->Session->setFlash(__('El vendedor se ha actualizado exitosamente', true));
                $this->redirect(array('action' => 'index')); /* header("Location: index"); */
            } else {
                $this->Session->setFlash(__('No se logró actulizar la información. Por favor intente de nuevo.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Solicitud->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Vendedor incorrecto.', true));
            $this->redirect(array('action' => 'index')); /* /header("Location: index"); */
        }
        if ($id > 0) {
            $estado = $this->Solicitud->find('first', array('fields' => 'Solicitud.estado_vendedor', 'conditions' => array('Solicitud.id' => $id)));
            if ($estado['Solicitud']['estado_vendedor']) {
                $this->Solicitud->updateAll(array("Solicitud.estado_vendedor" => 'false'), array("Solicitud.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a INACTIVO de el vendedor.', true));
                $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
            } else {
                $this->Solicitud->updateAll(array("Solicitud.estado_vendedor" => 'true'), array("Solicitud.id" => $id));

                $this->Session->setFlash(__('Se ha cambiado el estado a ACTIVO de el vendedor.', true));
                /* $this->redirect(array('action' => 'index')); */header("Location: index");
            }
        }
        $this->Session->setFlash(__('El vendedor no fue encontrada.', true));
        $this->redirect(array('action' => 'index')); /* /header("Location: ../index"); */
    }

}

?>
