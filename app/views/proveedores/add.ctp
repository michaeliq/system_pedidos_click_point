<?php ?>
<div class="proveedores form">
<?php echo $this->Form->create('Proveedore');?>
    <fieldset>
        <legend><?php __('Nuevo Proveedor'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td><b>Proveedor: *</b></td>
                <td><?php echo $this->Form->input('nombre_proveedor', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Nit: *</b></td>
                <td><?php echo $this->Form->input('nit_proveedor', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '20')); ?></td>
            </tr>
            <tr>
                <td><b>Tipo Regimen: </b></td>
                <td><?php echo $this->Form->input('tipo_regimene_id', array('type' => 'select', 'options' => $tipoRegimenes, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td><b>Forma de Pago:</b></td>
                <td><?php echo $this->Form->input('tipo_formas_pago_id', array('type' => 'select', 'options' => $tipoFormasPagos, 'label' => false, 'empty'=>'Seleccione una Opción')); ?>
            </tr>
            <tr>
                <td><b>Nombre de Contacto: </b></td>
                <td><?php echo $this->Form->input('persona_contacto', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Dirección Proveedor: </b></td>
                <td><?php echo $this->Form->input('direccion_proveedor', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Teléfono Proveedor: </b></td>
                <td><?php echo $this->Form->input('telefono_proveedor', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '30')); ?></td>
            </tr>
            <tr>
                <td><b>E-mail Proveedor:</b></td>
                <td><?php echo $this->Form->input('email_proveedor', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Departamento: </b></td>
                <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td><b>Municipio: </b></td>
                <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            </tr>

            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Proveedor', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
	<?php
		echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
		echo $this->Form->input('fecha_registro_proveedor', array('type' => 'hidden'));
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>
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
        $('#ProveedoreDepartamentoId').change(function () {
            $.ajax({
                url: '../users/municipios/',
                type: 'POST',
                data: {
                    UserDepartamentoId: $('#ProveedoreDepartamentoId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Municipios para este Departamento');
                } else {
                    document.getElementById('ProveedoreMunicipioId').innerHTML = '';
                    $('#ProveedoreMunicipioId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opcion';
                            op_default.value = '0';
                            document.getElementById('ProveedoreMunicipioId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Municipio.nombre_municipio;
                        opcion.value = data[i].Municipio.id;
                        document.getElementById('ProveedoreMunicipioId').add(opcion, null);

                    }
                    if ($('#ProveedoreDepartamentoId').val() == -1)
                        $('#ProveedoreMunicipioId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
</script>