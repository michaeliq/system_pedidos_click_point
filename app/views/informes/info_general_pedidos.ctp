<?php

// Crear archivo plano csv
    $file_name = 'informes/InformeGeneralPedidos.csv';
    $file = fopen($file_name, 'w');
        if (count($pedidos) > 0) {
            
            // Escribir encabezado del arhivo
            $data_csv = utf8_decode("Sucursal;CECO;OI;Regional;Id_Pedido;Guia_Despacho;Estado;Aseo_0%;Aseo_5%;Aseo_16%;Aseo_19%;Cafeteria_0%;Cafeteria_5%;Cafeteria_16%;Cafeteria_19%;consumible_0%;consumible_5%;consumible_16%;consumible_19%;papeleria_0%;papeleria_5%;papeleria_16%;papeleria_19%;consumibles_0%;consumibles_5%;consumibles_16%;consumibles_19%;dotacion_0%;dotacion_5%;dotacion_16%;dotacion_19%;insumos_0%;insumos_5%;insumos_16%;insumos_19%;\n");
            fwrite($file, $data_csv);    
            foreach ($pedidos as $pedido) :
                $data_csv = utf8_decode($pedido['VInformeGeneral']['sucursal']) . ';' .
                    utf8_decode($pedido['VInformeGeneral']['ceco']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['oi']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['regional']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['pedido_id']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['guia_despacho']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['estado']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['aseo_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['aseo_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['aseo_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['aseo_019']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['cafeteria_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['cafeteria_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['cafeteria_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['cafeteria_019']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumible_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumible_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumible_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumible_019']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['papeleria_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['papeleria_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['papeleria_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['papeleria_019']). ';' .
                    
                    utf8_decode($pedido['VInformeGeneral']['consumibles_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumibles_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumibles_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['consumibles_019']). ';' .
                    
                    utf8_decode($pedido['VInformeGeneral']['dotacion_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['dotacion_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['dotacion_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['dotacion_019']). ';' .
                    
                    utf8_decode($pedido['VInformeGeneral']['insumos_000']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['insumos_005']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['insumos_016']). ';' .
                    utf8_decode($pedido['VInformeGeneral']['insumos_019']);
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));

            endforeach;
            fclose($file);
        }
?>
<script>
$(function () {
        $('#PedidosDetalleEmpresaId').change(function () {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    UserEmpresaId: $('#PedidosDetalleEmpresaId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Sucursales para esta Empresa');
                } else {
                    document.getElementById('PedidosDetalleSucursalId').innerHTML = '';
                    $('#PedidosDetalleSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Todos';
                            op_default.value = '0';
                            document.getElementById('PedidosDetalleSucursalId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidosDetalleSucursalId').add(opcion, null);

                    }
                    if ($('#PedidosDetalleEmpresaId').val() == -1)
                        $('#PedidosDetalleSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
    
    $(function () {
        $('#PedidosDetallePedidoFechaInicio').datepicker({
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
        $('#PedidosDetallePedidoFechaCorte').datepicker({
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
<h2><span class="glyphicon glyphicon-book"></span> INFORME GENERAL DE PEDIDOS</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_general_pedidos'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa:</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Sucursal:</td>
            <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select',/*'options' => $sucursales, */'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Fecha Inicio: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Fecha Corte: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Estado Orden: </td>                
            <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<?php if (count($pedidos) > 0) { ?>
<div>&nbsp;</div>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<div>&nbsp;</div>
<?php } ?>
