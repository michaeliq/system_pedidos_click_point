<script>
    $(function () {
        $('#regresar_add').click(function () {
            if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
                window.location = "index";
            }
        });
    });
    /*  Cargar municipios a partir del departamento seleccionado. */
    $(function () {
        $('#BodegaDepartamentoId').change(function () {
            $.ajax({
                url: '../users/municipios/',
                type: 'POST',
                data: {
                    UserDepartamentoId: $('#BodegaDepartamentoId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Municipios para este Departamento');
                } else {
                    document.getElementById('BodegaMunicipioId').innerHTML = '';
                    $('#BodegaMunicipioId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opcion';
                            op_default.value = '0';
                            document.getElementById('BodegaMunicipioId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Municipio.nombre_municipio;
                        opcion.value = data[i].Municipio.id;
                        document.getElementById('BodegaMunicipioId').add(opcion, null);

                    }
                    if ($('#BodegaDepartamentoId').val() == -1)
                        $('#BodegaMunicipioId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
</script>
<div class="bodegas form">
<?php echo $this->Form->create('Bodega');?>
    <fieldset>
        <legend><?php __('Nueva Bodega'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Bodega: *</td>
                <td><?php echo $this->Form->input('nombre_bodega', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20')); ?></td>
            </tr>
            <tr>
                <td>Capacidad: </td>
                <td><?php echo $this->Form->input('capacidad_bodega', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10')); ?></td>
            </tr>
            <tr>
                <td>Departamento: </td>
                <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Municipio: </td>
                <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Bodega', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
	<?php
		echo $this->Form->input('estado_bodega', array('type' => 'hidden', 'value' => true));
                echo $this->Form->input('fecha_registro_bodega', array('type' => 'hidden'));
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>