<div class="tipoMovimientos form">
<?php echo $this->Form->create('TipoSolicitude');?>
    <fieldset>
        <legend><?php __('Editar Tipo de Solicitud PQR'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td><b>Nombre Tipo de Solicitud PQR: *</b></td>
                <td><?php echo $this->Form->input('nombre_tipo_solicitud', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '20')); ?></td>
            </tr>
            <tr>
                <td><b>Sigla: *</b></td>
                <td><?php echo $this->Form->input('sigla_solicitud', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '5')); ?></td>
            </tr>
            <tr>
                <td><b>Días Hábiles Respuesta: *</b></td>
                <td><?php echo $this->Form->input('dias_respuesta', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '10')); ?></td>
            </tr>
            <tr>
                <td><b>Color Solicitud: *</b></td>
                <td><?php echo $this->Form->input('color_solicitud', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '10')); ?><span><a href="https://htmlcolorcodes.com/es/" target="_blank">Consultar Paleta Colores</a></span><br><span>Recuerde que debe utilizar el formato HEX (#98D33D)</span></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Tipo Solicitud', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
	<?php
        echo $this->Form->input('id');
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>