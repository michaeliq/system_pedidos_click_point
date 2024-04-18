<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script>
    $(function () {
        $('#regresar_view').click(function () {
            window.location = "../listar_solicitudes";

        });
    });
</script>


<fieldset>
    <legend><?php __('Solicitud '); echo $solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']; ?> </legend>
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
        <tr>
            <td colspan="2"><?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_view', 'class' => 'btn btn-warning')); ?></td>
        </tr>
    </table>

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
        if (count($solicitud_detalle) > 0) {
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
        }
        ?>
    </table>
</fieldset>