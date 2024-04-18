<?php ?>
<script>
    /*  Cargar municipios a partir del departamento seleccionado. */
    $(function () {
        $('#CotizacionDepartamentoId').change(function () {
            $.ajax({
                url: '../../users/municipios/',
                type: 'POST',
                data: {
                    UserDepartamentoId: $('#CotizacionDepartamentoId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Municipios para este Departamento');
                } else {
                    document.getElementById('CotizacionMunicipioId').innerHTML = '';
                    $('#CotizacionMunicipioId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opcion';
                            op_default.value = '0';
                            document.getElementById('CotizacionMunicipioId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Municipio.nombre_municipio;
                        opcion.value = data[i].Municipio.id;
                        document.getElementById('CotizacionMunicipioId').add(opcion, null);

                    }
                    if ($('#CotizacionDepartamentoId').val() == -1)
                        $('#CotizacionMunicipioId').attr({
                            disabled: true
                        });
                }
            }
        });
    });

</script>
<h2><span class="glyphicon glyphicon-shopping-cart"></span> CALL CENTER - REALIZAR COTIZACIÓN DE PEDIDO</h2>
<?php echo $this->Form->create('Cotizacion', array('url' => array('controller' => 'listadoLlamadas', 'action' => 'cotizacion/'.base64_encode($listadoLlamada['ListadoLlamada']['id'])))); ?>
<table class="table table-striped table-bordered table-condensed"  align='center' style="width: 60%;">
    <tr>
        <td colspan="4">Por favor actualice los siguientes datos para sean correctos en el envio de la cotización:</td>
    </tr>
    <tr>
        <td><b>Empresa:</b></td>
        <td colspan="3"><?php echo $listadoLlamada['BdCliente']['bd_razon_social']; ?> - Nit: <?php echo $listadoLlamada['BdCliente']['bd_identificacion']; ?><br>
            <b>Ubicación:</b> <?php echo $listadoLlamada['BdCliente']['bd_nombre_municipio']; ?><br>
            <b>Actividad:</b> <span style="font-size: 10px; font-style: italic;"><?php echo $listadoLlamada['BdCliente']['bd_descripcion_1']; ?></td>
    </tr>
    <tr>
        <td><b>Departamento:</b></td>
        <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false,'selected'=> $listadoLlamada['BdCliente']['bd_departamento_id'])); ?></td>
        <td><b>Municipio:</b></td>
        <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false,'selected'=> $listadoLlamada['BdCliente']['bd_municipio_id'])); ?></td>
    </tr>
    <tr style="background-color: #ffff99;">
        <td><b>E-mail:</b></td>
        <td colspan="3"><?php echo $this->Form->input('cotizacion_email', array('type' => 'text', 'size'=>'100', 'label' => false, 'value' => $listadoLlamada['BdCliente']['bd_email'],'required'=>true)); ?></td>
    </tr>
    <tr>
        <td><b>Dirección:</b></td>
        <td><?php echo $this->Form->input('cotizacion_direccion', array('type' => 'text', 'label' => false, 'value' => $listadoLlamada['BdCliente']['bd_direccion'])); ?></td>        
        <td><b>Teléfono:</b></td>
        <td><?php echo $this->Form->input('cotizacion_telefono', array('type' => 'text', 'label' => false, 'value' => $listadoLlamada['BdCliente']['bd_telefonos'])); ?></td>
    </tr>

    <tr>
        <td><b>Tipo Pedido:</b></td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedidos, /*'empty' => 'Seleccione una Opción',*/ 'label' => false)); ?></td>
        <td><b>Observaciones<br>Cotización:</b></td>
        <td><?php echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'40')); ?></td>
    </tr>
    <tr>
        <td class="text-center" colspan="4" >
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning','onclick'=>'history.back()')); ?>
            <?php echo $this->Form->button('Realizar Cotización', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
        </td>
    </tr>   
</table>
<div>&nbsp;</div>
<?php
echo $this->Form->input('listado_llamada_id', array('type' => 'hidden', 'value' => base64_encode($listadoLlamada['ListadoLlamada']['id'])));
echo $this->Form->input('bd_cliente_id', array('type' => 'hidden', 'value' => base64_encode($listadoLlamada['ListadoLlamada']['bd_cliente_id'])));
echo $this->Form->input('fecha_cotizacion', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
?>
<?php echo $this->Form->end(); ?>