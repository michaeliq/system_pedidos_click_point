<?php

ini_set('max_execution_time','300');
ini_set('memory_limit', '912M'); ?>
<?php

// Crear archivo plano csv
    $file_name = 'informes/InformeSolicitudes.csv';
    $file = fopen($file_name, 'w');
?>
<script>
    $(function () {
        $('#SolicitudeSolicitudFechaInicio').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            yearRange: "-2:+0",
            maxDate: '0'
        });
    });

    $(function () {
        $('#SolicitudeSolicitudFechaCorte').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            yearRange: "-2:+0",
            maxDate: '0'
        });
    });
</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME DE SOLICITUDES</h2>
<?php echo $this->Form->create('Solicitude', array('url' => array('controller' => 'informes', 'action' => 'info_solicitudes'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Tipo de Solicitud:</b></td>
        <td><?php echo $this->Form->input('tipo_solicitud_id', array('type' => 'select', 'options' => $tipoSolicitud, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td><b>Motivo / Asunto:</b></td>
        <td><?php echo $this->Form->input('tipo_motivo_solicitud_id', array('type' => 'select', 'options' => $tipoMotivo, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <td><b>Empresa:</b></td>
        <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresa, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td><b>Estado:</b></td>
        <td><?php echo $this->Form->input('tipo_estado_solicitud_id', array('type' => 'select', 'options' => $tipoEstadoSolicitud, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td><b>Pedido:</b></td>
        <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '10', 'maxlength' => '5', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Fecha Inicio:</b></td>
        <td><?php echo $this->Form->input('solicitud_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        <td><b>Fecha Corte:</b></td>
        <td><?php echo $this->Form->input('solicitud_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<div>&nbsp;</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Solicitud</th>
        <th>Fecha Registro</th>
        <th>Empresa</th>
        <th>Solicitada por</th>
        <th>Solicitud</th>
        <th>Tipo Solicitud</th>
        <th>Motivo/Asunto</th>
        <th>Ultima Respuesta</th>
        <th>Asignada a</th>
        <th>Estado</th>
        <th>Semaforo</th>
    </tr>
 <?php
    $data_csv = utf8_decode("Solicitud;FechaRegistro;Empresa;SolicitadaPor;Solicitud;TipoSolicitud;Motivo/Asunto;UltimaRespuesta;AsignadaA;Estado;Semaforo\n");
    fwrite($file, $data_csv); 
    foreach ($solicitudes as $solicitud):
        $semaforo = 'verde.png';
        $semaforo_text = 'Verde';
    
        $vencimiento = $solicitud['TipoSolicitude']['dias_respuesta'];
        $registro = new DateTime(substr($solicitud['Solicitud']['fecha_registro'], 0, 10));
        $actual = new DateTime(date('Y-m-d'));
        $dias = $actual->diff($registro);
        
        // echo $dias->days . ' days -- '.$solicitud['TipoSolicitude']['dias_respuesta'];
        $asignado = null;
        $vencida = null;
        if($dias->days >= ($solicitud['TipoSolicitude']['dias_respuesta']/2)){
            $semaforo = 'amarillo.png';
            $semaforo_text = 'Amarillo';
        }
        if($dias->days >= $solicitud['TipoSolicitude']['dias_respuesta']){
            $vencida = 'style="background-color: #ff9999;" title="La Solicitud esta vencida"';
            $semaforo = 'rojo.png';
            $semaforo_text = 'Rojo';
        }

     ?>
    <tr <?php // echo $vencida; ?>>
        <td><?php echo $solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']; ?></td>
        <td><?php echo  substr($solicitud['Solicitud']['fecha_registro'], 0, 16); ?></td>
        <td><?php echo $solicitud['Empresa']['nombre_empresa']; ?></td>
        <td><?php echo $solicitud['User']['nombres_persona']; ?></td>
        <td><?php echo $solicitud['Solicitud']['detalles_solicitud']; ?></td>
        <td><div style="border: #000 solid thin; padding: 1px; background-color: <?php echo $solicitud['TipoSolicitude']['color_solicitud']; ?>"><?php echo $solicitud['TipoSolicitude']['nombre_tipo_solicitud']; ?></div></td>
        <td><?php echo $solicitud['TipoMotivosSolicitud']['nombre_tipo_motivo'];  //  echo $solicitud['Solicitud']['motivo_asunto']; ?></td>
        <td><?php echo  substr($solicitud['Solicitud']['fecha_respuesta'], 0, 16); ?></td>
        <td><?php if(!empty($solicitud['Solicitud']['user_id_asignado'])){ echo $solicitud['UserAsignado']['nombres_persona']; $asignado=$solicitud['UserAsignado']['nombres_persona']; } ?></td>
        <td><?php echo $solicitud['TipoEstadoSolicitud']['nombre_estado_solicitud']; ?></td>
        <td><?php echo $html->image($semaforo); ?></td>
    </tr>
    <?php
            $data_csv = utf8_decode($solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']) . ';' .
                    substr($solicitud['Solicitud']['fecha_registro'], 0, 16) . ';' .
                    $solicitud['Empresa']['nombre_empresa']. ';' .
                    $solicitud['User']['nombres_persona']. ';' .
                    str_replace(';', '', $solicitud['Solicitud']['detalles_solicitud']). ';' .
                    utf8_decode($solicitud['TipoSolicitude']['nombre_tipo_solicitud']). ';' .
                    utf8_decode($solicitud['TipoMotivosSolicitud']['nombre_tipo_motivo']). ';' .
                    substr($solicitud['Solicitud']['fecha_respuesta'], 0, 16). ';' .
                    $asignado. ';' .
                    utf8_decode($solicitud['TipoEstadoSolicitud']['nombre_estado_solicitud']). ';' .
                    $semaforo_text;
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));
        endforeach;
        fclose($file);
    ?>
</table>

<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>