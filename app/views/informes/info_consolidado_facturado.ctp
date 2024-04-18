<?php

ini_set('memory_limit', '512M'); ?>
<?php

// Crear archivo plano csv
    $file_name = 'informes/InformeConsolidadoFacturado.csv';
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
    
 $(function () {
        $('#PedidosDetallePedidoFechaVencimiento').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            maxDate: '+180'
        });
    });


</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME CONSOLIDADO FACTURADO</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_consolidado_facturado'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa:</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Regional:</td>
            <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options'=>$regional, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Sucursal:</td>
            <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Fecha Inicio: *</td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Fecha Corte: *</td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Estado Orden: </td>                
            <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Nota Concepto:</td>
            <td><?php echo $this->Form->input('pedido_nota_concepto', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
            <td>Vendedor:</td>
            <td><?php echo $this->Form->input('pedido_vendedor', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Fecha Vencimiento: *</td>
            <td><?php echo $this->Form->input('pedido_fecha_vencimiento', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Categoría:</td>
            <td><?php echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $tipoCategoria, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>No. Orden:</td>
            <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td></td>
            <td></td>
        </tr>

    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>

<table class="table table-hover">
    <tr>						
        <th>Empresa</th>
        <th>Tipo de Doc/DOC.</th>
        <th>Prefijo</th>
        <th>No. Doc.</th>
        <th>Fecha</th>
        <th>Benefic/terc externo</th>
        <th>Vendedor</th>
        <th>Nota/Concepto</th>
        <th>Doc Externo</th>
        <th>Forma de pago</th>
        <th>Moneda</th>
        <th>Anulado</th>
        <th>Producto</th>
        <th>Bodega</th>
        <th>U.Medida</th>
        <th>Cantidad</th>
        <th>Valor Unit</th>
        <th>IVA</th>
        <th>Centro costos</th>
        <th>Vencimiento</th>
        <th>Tercero</th>
        <th>FactorConversiónMovimientoABodega</th>
        <th>FactorConversiónMovimientoAInventario</th>
        <th>Descuento</th>
    </tr>
    <?php
    $total_final = 0;
    if (count($detalles) > 0) {
       
    // Escribir encabezado del arhivo
    $data_csv = utf8_decode("Empresa;Tipo de Doc/DOC.;Prefijo;No. Doc.;Fecha;Benefic/terc externo;Vendedor;Nota/Concepto;Doc Externo;Forma de pago;Moneda;Anulado;Producto;Bodega;U.Medida;Cantidad;Valor Unit;IVA;Centro costos;Vencimiento;Tercero;FactorConversiónMovimientoABodega;FactorConversiónMovimientoAInventario;Descuento;\n");
    fwrite($file, $data_csv);
    foreach ($detalles as $detalle) :
    ?>
    <tr>
        <td><?php echo $detalle['VConsolidadoFacturado']['empresa']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['tipo_doc']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['prefijo']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['no_doc']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['fecha']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['benefic']; ?></td>
        <td><?php echo $this->data['PedidosDetalle']['pedido_vendedor']; ?></td>
        <td><?php echo $this->data['PedidosDetalle']['pedido_nota_concepto']; // echo $detalle['VConsolidadoFacturado']['nota_concepto']; ?></td>
        <td>&nbsp;</td> <!-- Doc Externo -->
        <td><?php echo $detalle['VConsolidadoFacturado']['forma_pago']; ?></td>
        <td>&nbsp;</td> <!-- Moneda -->
        <td>0</td> <!-- Anulado -->
        <td><?php echo $detalle['VConsolidadoFacturado']['producto']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['bodega']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['u_medida']; ?></td>
        <td><?php echo $detalle['0']['cantidad']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['valor_unit']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['iva']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['centro_costos']; ?></td>
        <td><?php echo $this->data['PedidosDetalle']['pedido_fecha_vencimiento']; ?></td>
        <td><?php echo $detalle['VConsolidadoFacturado']['benefic']; ?></td>
        <th>1</th> <!-- FactorConversiónMovimientoABodega -->
        <th>1</th> <!-- FactorConversiónMovimientoAInventario -->
        <th>0</th> <!-- Descuento -->
    </tr> 
        <?php
            $data_csv =  utf8_decode($detalle['VConsolidadoFacturado']['empresa']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['tipo_doc']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['prefijo']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['no_doc']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['fecha']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['benefic']). ';' .
         utf8_decode($this->data['PedidosDetalle']['pedido_vendedor']). ';' .
         utf8_decode($this->data['PedidosDetalle']['pedido_nota_concepto']). ';' .
         utf8_decode(""). ';' .                    
         utf8_decode($detalle['VConsolidadoFacturado']['forma_pago']). ';' .
         utf8_decode(""). ';' .        
         utf8_decode("0"). ';' .        
         utf8_decode($detalle['VConsolidadoFacturado']['producto']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['bodega']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['u_medida']). ';' .
         utf8_decode($detalle['0']['cantidad']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['valor_unit']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['iva']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['centro_costos']). ';' .
         utf8_decode($this->data['PedidosDetalle']['pedido_fecha_vencimiento']). ';' .
         utf8_decode($detalle['VConsolidadoFacturado']['benefic']). ';' .
         utf8_decode("1"). ';' .
         utf8_decode("1"). ';' .
         utf8_decode("0");
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));
            endforeach;
            fclose($file);
        }
        ?>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>