<?php

echo $this->Form->create('Solicitud', array('type' => 'file','url' => array('controller' => 'solicitudes', 'action' => 'detalle_solicitud'))); ?>
<script>
    $(function () {
        $('#regresar_edit').click(function () {
            window.location = "../listar_solicitudes";

        });
    });
</script>
<fieldset>
    <legend><?php if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == '1'){ echo 'Asignar solicitud de '; } echo $solicitud['TipoSolicitude']['nombre_tipo_solicitud'].' '.$solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']; ?> </legend>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Tipo de Solicitud:</b></td>
            <td><?php echo $solicitud['TipoSolicitude']['nombre_tipo_solicitud']; ?></td>            
        </tr>  
        <tr>
            <td><b>Estado de Solicitud:</b></td>
            <td><?php echo  $solicitud['TipoEstadoSolicitud']['nombre_estado_solicitud']; ?></td>            
        </tr>  
        <tr>
            <td><b>Motivo / Asunto:</b></td>
            <td><?php echo $solicitud['TipoMotivosSolicitud']['nombre_tipo_motivo'];  // echo $solicitud['Solicitud']['motivo_asunto']; ?></td>
        </tr>
        <tr>
            <td><b>Detalles de la solicitud:</b></td>
            <td><?php echo $solicitud['Solicitud']['detalles_solicitud']; ?></td>
        </tr>

        <tr>
            <td><b>No. Documentos: </b></td>
            <td><?php echo $solicitud['User']['no_identificacion_persona']; ?></td>
        </tr>
        <tr>
            <td><b>Nombres y Apellidos: </b></td>
            <td><?php echo $solicitud['User']['nombres_persona']; ?></td>
        </tr>
        <tr>
            <td><b>Celular - Teléfono: </b></td>
            <td><?php echo $solicitud['Solicitud']['celular_telefono']; ?></td>
        </tr>
        <tr>
            <td><b># Orden de Pedido: </b></td>
            <td>#000<?php echo $solicitud['Solicitud']['pedido_id']; ?></td>
        </tr>
        <tr>
            <td><b>Adjuntar Archivo:</b> </td>
            <td><?php 
                    if ($solicitud['Solicitud']['archivo_adjunto']) { 
                        $files = explode(';', $solicitud['Solicitud']['archivo_adjunto']);
                        for ($i = 1; $i < count($files); $i++) {
                            echo '<a href="/pedidos/pqr/adjuntos/'.$files[$i].'" target="_blank">'.$files[$i].' <span class="glyphicon glyphicon-download-alt"></span></a>';
                            echo '<br>';
                        }
                    }
                ?>
            </td>
        </tr>
    </table>
    <?php if (count($solicitud_detalle) > 0) { ?>
    <h2>Gestiones a Solicitud</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Estado Anterior</th>
            <th>Nuevo Estado</th>
            <th>Fecha Gestión</th>
            <th>Detalle Observación</th>
            <th>Usuario Gestión</th>
            <th>Archivo Adjunto</th>
        </tr>
        <?php
        
            foreach ($solicitud_detalle as $detalle):
        ?>
        <tr>
            <td><?php echo $detalle['TipoEstadoSolicitud1']['nombre_estado_solicitud']; ?></td>
            <td><?php echo $detalle['TipoEstadoSolicitud2']['nombre_estado_solicitud']; ?></td>
            <td><?php echo $detalle['SolicitudesDetalle']['fecha_detalle']; ?></td>
            <td><?php echo $detalle['SolicitudesDetalle']['detalle_observacion']; ?></td>
            <td><?php echo $detalle['User']['nombres_persona']; ?></td>
            <td><?php 
                    if ($detalle['SolicitudesDetalle']['archivo_adjunto']) { 
                        echo '<a href="/pedidos/pqr/respuestas/'.$detalle['SolicitudesDetalle']['archivo_adjunto'].'" target="_blank">'.$detalle['SolicitudesDetalle']['archivo_adjunto'].' <span class="glyphicon glyphicon-download-alt"></span></a>';
                    }
                ?></td>
        </tr>
        <?php 
            endforeach;
        ?>
    </table>
    <?php } ?>
    <?php if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == 1 && empty($solicitud['Solicitud']['user_id_asignado'])) { ?>
    <div>&nbsp;</div>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td colspan="2"><h3>Asignar Solicitud</h3></td>
        </tr>        
        <tr>
            <td><b>Usuario a Asignar: *</b></td>
            <td><?php echo $this->Form->input('user_id_asignado', array('type' => 'select',  'required'=>'required', 'options' => $userAsignar, 'label' => false, 'empty'=>'Seleccione una Opción')); ?> </td>                        
        </tr>
        <tr>
            <td><b>Observaciones: *</b></td>
            <td><?php echo $this->Form->input('detalle_observacion', array('type' => 'textarea', 'required'=>'required','label' => false, 'rows' => '10')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Asignar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['id']));
        echo $this->Form->input('tipo_estado_solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['tipo_estado_solicitud_id']));
        ?>
    </table>
    <?php } ?>
    <?php if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == 2) { ?>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td colspan="2"><h3>Gestión de <?php echo $solicitud['TipoSolicitude']['nombre_tipo_solicitud'] ?></h3></td>
        </tr>        
        <tr>
            <td><b>Respuesta: *</b></td>
            <td><?php echo $this->Form->input('detalle_observacion', array('type' => 'textarea', 'required'=>'required', 'label' => false, 'rows' => '10')); ?></td>
        </tr>
        <tr>
            <td><b>Adjuntar Respuesta:</b> </td>
            <td><?php echo $this->Form->input('archivo_adjunto', array('type' => 'file', 'label' => false)); ?><br><span style="font-size: x-small;">Solo se aceptan archivos .Zip o .Rar</span></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Gestionar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['id']));
        echo $this->Form->input('tipo_estado_solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['tipo_estado_solicitud_id']));
        ?>
    </table>
    <?php } ?>
    <?php if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == 4) { ?>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Teniendo en cuenta el historial de gestiones realizadas a la solicitud, usted puede:</b><br><span style="font-size: x-small;">En caso de rechazar la respuesta, se iniciará nuevamente el proceso de la solicitud para que sea verificada de manera prioritaría.</span></td>
        </tr>
        <tr>
            <td><?php echo $this->Form->input('detalle_observacion', array('type' => 'textarea', 'required'=>'required', 'label' => false, 'rows' => '10','.')); ?></td>
        </tr>
        <tr>
            <td><?php echo $this->Form->radio('respuesta_solicitud',$respuesta_solicitud,array('name'=>'respuesta_solicitud','default'=>'1','legend'=>false ,'separator'=>'<div style=""></div>')); ?></td>
        </tr>

        <tr>
            <td class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Responder', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
    <?php
        echo $this->Form->input('solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['id']));
        echo $this->Form->input('tipo_estado_solicitud_id', array('type' => 'hidden', 'value' => $solicitud['Solicitud']['tipo_estado_solicitud_id']));
    ?>
    <?php } ?>
</fieldset>
<?php echo $this->Form->end(); ?>