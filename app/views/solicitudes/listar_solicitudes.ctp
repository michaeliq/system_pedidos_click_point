<?php ?>
<h2><span class="glyphicon glyphicon-filter"></span> PQR - Gestionar Solicitud PQR</h2>
<?php echo $this->Form->create('Solicitude'); ?>
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
</table>
<div class="text-center">
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Solicitud</th>
        <th>Tipo Solicitud</th>
        <th>Motivo/Asunto</th>
        <th>Fecha Registro</th>
        <th>Ultima Respuesta</th>
        <th>Solicitada por</th>
        <th>Asignada a</th>
        <th>Empresa</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
 <?php
     if (count($solicitudes) > 0) {
        foreach ($solicitudes as $solicitud):
            $files = explode(';', $solicitud['Solicitud']['archivo_adjunto']);
            $vencimiento = $solicitud['TipoSolicitude']['sigla_solicitud'];
     ?>
    <tr>
        <td><?php echo $solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']; ?></td>
        <td><div style="border: #000 solid thin; padding: 1px; background-color: <?php echo $solicitud['TipoSolicitude']['color_solicitud']; ?>"><?php echo $solicitud['TipoSolicitude']['nombre_tipo_solicitud']; ?></div>
            <span style="font-size: x-small">Adjuntos: <?php echo count($files)-1; ?></span></td>
        <td><?php echo $solicitud['TipoMotivosSolicitud']['nombre_tipo_motivo'];  //  echo $solicitud['Solicitud']['motivo_asunto']; ?></td>
        <td><?php echo  substr($solicitud['Solicitud']['fecha_registro'], 0, 16); ?></td>
        <td><?php echo  substr($solicitud['Solicitud']['fecha_respuesta'], 0, 16); ?></td>
        <td><?php echo $solicitud['User']['nombres_persona']; ?></td>
        <td><?php if(!empty($solicitud['Solicitud']['user_id_asignado'])) echo $solicitud['UserAsignado']['nombres_persona']; ?></td>
        <td><?php echo $solicitud['Empresa']['nombre_empresa']; ?></td>
        <td><?php echo $solicitud['TipoEstadoSolicitud']['nombre_estado_solicitud']; ?></td>
        <td>
            <?php if(empty($solicitud['Solicitud']['user_id_asignado'])){ ?>
            <div class="asignar_solicitud" title="Asignar Solicitud"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_solicitud',base64_encode($solicitud['Solicitud']['id'])), array('class' => 'glyphicon glyphicon-transfer', 'escape' => false)); ?></div>
            <?php }else{ ?> 
            <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view',base64_encode($solicitud['Solicitud']['id'])), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
            <?php } ?>
            <?php
            $simbol = null;
            if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == '2'){
                $simbol = "glyphicon glyphicon-question-sign";
            }
            if($solicitud['Solicitud']['tipo_estado_solicitud_id'] == '4' && $solicitud['User']['id'] == $this->Session->read('Auth.User.id') ){
               $simbol = "glyphicon glyphicon-share-alt"; 
            }
            ?>
            <div class="detalle_solicitud" title="Responder <?php echo $solicitud['TipoSolicitude']['sigla_solicitud'].$solicitud['Solicitud']['id']; ?>"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_solicitud', base64_encode($solicitud['Solicitud']['id'])), array('class' => $simbol, 'escape' => false)); ?></div>   
            <?php if($solicitud['Solicitud']['prioridad'] && $solicitud['Solicitud']['tipo_estado_solicitud_id']<4) echo "<span style='color:red; padding-left:5px;' class='glyphicon glyphicon-warning-sign'></span>"; ?>
        </td>
    </tr>
    <?php
        endforeach;
    }else {
        ?>
    <tr>
        <td colspan="9" class="text-center">No se han creado solicitudes PQR en el sistema.</td>
    </tr>
        <?php
    }
    ?>
</table>
