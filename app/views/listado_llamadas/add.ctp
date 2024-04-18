<script>

    $(function () {
        $('#ListadoLlamadaBdIdentificacion').change(function () {
            $.ajax({
                url: 'clientes',
                type: 'POST',
                data: {
                    ListadoLlamadaBdIdentificacion: $('#ListadoLlamadaBdIdentificacion').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No se encontró ningún cliente para este Nit');
                    document.getElementById('ListadoLlamadaBdClienteId').innerHTML = '';
                } else {
                    document.getElementById('ListadoLlamadaBdClienteId').innerHTML = '';
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opcion';
                            op_default.value = '0';
                            // document.getElementById('ListadoLlamadaBdClienteId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].BdCliente.bd_razon_social;
                        opcion.value = data[i].BdCliente.id;
                        document.getElementById('ListadoLlamadaBdClienteId').add(opcion, null);

                    }
                }
            }
        });
    });

</script>
<div class="listadoLlamadas form">
<?php echo $this->Form->create('ListadoLlamada');?>
    <fieldset>
        <legend><?php __('Registrar Nueva Llamada a Cliente'); ?></legend>
        <div style="text-align: center">
	<?php
		echo $this->Form->input('bd_identificacion', array('type' => 'text', 'label' => 'Nit Cliente:', 'size' => '30', 'maxlength' => '60')); 
		echo $this->Form->input('bd_cliente_id', array('type' => 'select', 'empty' => 'Seleccione una Opción', 'label' => 'Cliente: '));
		echo $this->Form->input('estado_llamada', array('type' => 'hidden', 'value' => true));
		echo $this->Form->input('fecha_registro', array('type' => 'hidden', 'value' => 'now()'));
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
                echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning'));
                echo " ";
                echo $this->Form->button('Registrar Nueva Llamada', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
        </div>
    </fieldset>
<?php echo $this->Form->end();?>
</div>