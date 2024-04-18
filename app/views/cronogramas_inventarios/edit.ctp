<script>
    $(function () {
        $('#regresar_add').click(function () {
            if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
                window.location = "../index";
            }

        });
    });

    $(function () {
        $('#CronogramasInventarioFechaInicio').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            minDate: 0,
            maxDate: +365
        });

        $('#CronogramasInventarioFechaFin').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            minDate: 0,
            maxDate: +365
        });
    });


</script>
<div class="cronogramasInventarios form">
<?php echo $this->Form->create('CronogramasInventario');?>
	<fieldset>
		<legend><?php __('Editar Cronogramas Inventario'); ?></legend>
                <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Cronograma: *</td>
                <td><?php echo $this->Form->input('nombre_cronograma', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '45')); ?></td>

                <td>Bodega: *</td>
                <td><?php echo $this->Form->input('bodega_id', array('type' => 'select', 'options' => $bodegas, 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Inicio: *</td>
                <td><?php echo $this->Form->input('fecha_inicio', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10')); ?></td>
                 <td>Fin: *</td>
                <td><?php echo $this->Form->input('fecha_fin', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10')); ?></td>
            </tr>
            <tr>
                <td>Categorias: *</td>               
                <td><?php echo $form->input('tipo_categoria_id', array('multiple' => 'checkbox', 'label' => false, 'options' => $tipoCategorias, 'selected' => json_decode($this->data['CronogramasInventario']['tipo_categoria_id']))); ?> 
                    <?php // echo $this->Form->checkbox('tipo_categoria_id', $tipoCategorias, array('legend' => false,'separator' => '<br>')); ?> </td>
                <td>Detalle / Observaciones: </td>
                <td><?php echo $this->Form->input('detalle_cronograma',array('label' => false,)); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Cronograma', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
	<?php
		echo $this->Form->input('id');
	?>
	</fieldset>
<?php echo $this->Form->end();?>
</div>