<?php

ini_set('max_execution_time','300');
ini_set('memory_limit', '912M'); ?>
<?php

// Crear archivo plano csv
    $file_name = 'informes/InformeCantidadesParciales.csv';
    $file = fopen($file_name, 'w');
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
                    document.getElementById('PedidosDetalleRegionalSucursal').innerHTML = '';
                    $('#PedidosDetalleSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Todos';
                            op_default.value = '0';
                            document.getElementById('PedidosDetalleSucursalId').add(op_default, null);

                            var op_default_reg = document.createElement('option');
                            op_default_reg.text = 'Todos';
                            op_default_reg.value = '0';
                            document.getElementById('PedidosDetalleRegionalSucursal').add(op_default_reg, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.v_regional_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidosDetalleSucursalId').add(opcion, null);

                        var opcion_reg = document.createElement('option');
                        opcion_reg.text = data[i].Sucursale.regional_sucursal;
                        opcion_reg.value = data[i].Sucursale.regional_sucursal;
                        document.getElementById('PedidosDetalleRegionalSucursal').add(opcion_reg, null);

                    }
                    $("#PedidosDetalleRegionalSucursal option").each(function () {
                        $(this).siblings("[value='" + this.value + "']").remove();
                    });
                    if ($('#PedidosDetalleEmpresaId').val() == -1)
                        $('#PedidosDetalleSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });


    $(function () {
        $('#PedidosDetalleRegionalSucursal').change(function () {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    PedidoRegionalSucursal2: $('#PedidosDetalleRegionalSucursal option:selected').text()
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
                            op_default.text = 'Seleccione una Opción';
                            op_default.value = '0';
                            document.getElementById('PedidosDetalleSucursalId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.regional_sucursal + ' - ' + data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidosDetalleSucursalId').add(opcion, null);

                    }
                    if ($('#PedidosDetalleRegionalSucursal').val() == -1)
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
<h2><span class="glyphicon glyphicon-book"></span> INFORME DE CANTIDADES PARCIALES</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_cantidades_ep'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Empresa:</b></td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td><b>Regional:</b></td>
            <td colspan="3"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options'=>$regional, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td><b>Sucursal:</b></td>
            <td colspan="3"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>     
            <td><b>No. Orden:</b></td>
            <td><?php echo $this->Form->input('id', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td><b>No. Orden Entrega Pracial:</td>
            <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
        <tr>     
            <td><b>Fecha Inicio:</b></td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td><b>Fecha Corte:</td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
<!--        <tr>
            <td><b>Estado Orden:</b></td>                
            <td><?php // echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td><b>Tipo Pedido:</b></td>
            <td><?php // echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>-->
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<?php if(count($detalles_ep)> 0){?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<table class="table table-hover">
    <tr>
        <th>No. Orden</th>
        <th>No. Orden<br>Entrega Parcial</th>
        <th>Empresa</th>
        <th>Producto</th>
        <th>Cantidad<br>Solicitada</th>
        <th>Cantidad<br>Entregada</th>
        <th>Cantidad<br>Faltante</th>
    </tr>
  <?php
        $data_csv = utf8_decode("No. Orden Parcial;No. Orden;Empresa;Sucursal;Regional;Codigo;Descripcion;CantidadSolicitada;CantidadEntregada;CantidadFaltante\n");
        fwrite($file, $data_csv);  
        foreach ($detalles_ep as $detalle) :
?>
    <tr>
        <td style='color: red; font-weight: bold;'>#000<?php echo $detalle['VInformeCantidadesEp']['id']; ?></td>
        <td style='color: green; font-weight: bold;'>#000<?php echo $detalle['VInformeCantidadesEp']['pedido_entrega_parcial']; ?></td>
        <td><b>Emp:</b> <?php echo $detalle['VInformeCantidadesEp']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $detalle['VInformeCantidadesEp']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $detalle['VInformeCantidadesEp']['regional_sucursal'];?></td>
        <td><?php echo $detalle['VInformeCantidadesEp']['codigo_producto'].' - '.$detalle['VInformeCantidadesEp']['nombre_producto']; ?></td>
        <td><?php echo $detalle['VInformeCantidadesEp']['solicitada']; ?></td>
        <td><?php echo $detalle['VInformeCantidadesEp']['entregada']; ?></td>
        <td><?php echo $detalle['VInformeCantidadesEp']['faltante']; ?></td>
    </tr> 
        <?php
            $data_csv = utf8_decode('#000'. $detalle['VInformeCantidadesEp']['id']) . ';' .
                utf8_decode('#000'. $detalle['VInformeCantidadesEp']['pedido_entrega_parcial']). ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['nombre_empresa']). ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['nombre_sucursal']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['regional_sucursal']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['codigo_producto']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['nombre_producto']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['solicitada']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['entregada']) . ';' .
                utf8_decode($detalle['VInformeCantidadesEp']['faltante']);
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));

            endforeach;
            fclose($file);
         ?>    
</table>
<?php } ?>