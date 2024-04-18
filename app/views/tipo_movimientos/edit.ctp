<div class="tipoMovimientos form">
<?php echo $this->Form->create('TipoMovimiento');?>
    <fieldset>
        <legend><?php __('Editar Tipo de Movimiento'); ?></legend><table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Tipo Movimiento: *</td>
                <td><?php echo $this->Form->input('nombre_tipo_movimiento', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '45')); ?></td>
            </tr>
            <tr>
                <td>Desde: *</td>
                <td><?php echo $this->Form->input('flujo_inicial', array('type' => 'select', 'options' => $flujos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Hacia: *</td>
                <td><?php echo $this->Form->input('flujo_final', array('type' => 'select', 'options' => $flujos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Descripción</td>
                <td><?php echo $this->Form->input('descripcion_tipo_movimiento', array('type' => 'textarea', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Tipo: </td>
                <td><?php echo $this->Form->input('tipo_movimiento', array('type' => 'select', 'options' => $tipo_movimiento, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Tipo Movimiento', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>

	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('estado_tipo_movimiento',array('type'=>'hidden'));
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>