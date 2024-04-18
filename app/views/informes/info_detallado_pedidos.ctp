<?php

ini_set('max_execution_time','300');
ini_set('memory_limit', '912M'); ?>
<?php

// Crear archivo plano csv
    $file_name = 'informes/InformeDetalladoPedidos.csv';
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
<h2><span class="glyphicon glyphicon-book"></span> INFORME DETALLADO DE PEDIDOS</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_detallado_pedidos'))); ?>
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
            <td>Fecha Inicio: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Fecha Corte: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Estado Orden: </td>                
            <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Empresa Logisitica:</td>
            <td><?php echo $this->Form->input('empresa_logistica', array('type' => 'select', 'options' => array('0'=>'Centro Aseo','1'=>'Empresa 1','2'=>'Empresa 2','3'=>'Empresa 3','4'=>'Empresa 4','5'=>'Empresa 5','6'=>'Empresa 6'), 'label' => false)); ?></td>
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
<?php if(count($data_estados)){ ?>
<div>&nbsp;</div>
<table class="table-striped table-bordered table-condensed" align="center">
    <tr>
        <th>Cantidad</th>
        <th>Estado Pedido</th>
    </tr>
<?php 
foreach ($data_estados as $data_estado) :
?>
    <tr>
        <td><?php echo $data_estado['0']['cantidad']; ?></td>
        <td><?php echo $data_estado['0']['nombre_estado'];?></td>
    </tr>
    <?php 
endforeach;
?>
</table>
<div>&nbsp;</div>
<?php } ?>

<table class="table table-hover">
    <tr>
<!--        <th></th>-->
<!--        <th>C&oacute;digo</th>-->
        <th>No. Orden</th>
        <th>Regional</th>
        <th>Centro de Costo</th>
<!--        <th>Centro de Costo OPESE</th>
        <th>Orden Interna OPESE</th>-->
        <th>Categor&iacute;a <?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $categorias, 'empty' => '', 'label' => false, 'default' => '1'));                                  ?></th>
        <th>Descripci&oacute;n</th>
        <th>Fecha</th>
        <th>Cantidad</th>
<!--        <th>Precio Bruto Unitario</th>-->
        <th>IVA</th>
        <th>Precio Unitario<br>Con Iva</th>
        <th>Precio Total</th>        
    </tr>
    <?php
    $total_final = 0;
    if (count($detalles) > 0) {
       
    // Escribir encabezado del arhivo
    $data_csv = utf8_decode("Empresa;Departamento;Municipio;Direccion;No. Orden;Estado;Regional;Centro de Costo;Centro de Costo OPESE;Orden Interna OPESE;Categoria;Codigo;Descripcion;FechaPedido;FechaAprobado;FechaDespacho;FechaDesde;FechaHasta;MesPedido;Clasificacion;Cantidad;Unidad;Precio Bruto Unitario;IVA;Precio Unitario Con Iva;Precio Total;CódigoHomologo;NombreHomologo;PrecioHomologo\n");
    fwrite($file, $data_csv);    
        foreach ($detalles as $detalle) :
            $total_final = $total_final + ($detalle['VInformeDetallado']['precio_producto'] + ($detalle['VInformeDetallado']['precio_producto'] * $detalle['VInformeDetallado']['iva_producto'])) * $detalle['VInformeDetallado']['cantidad_pedido'];
            ?>
    <tr>
        <td>#000<?php echo $detalle['VInformeDetallado']['id']; ?></td>
<!--        <td><?php // echo $html->image('productos/'.$detalle['Producto']['codigo_producto'].'.jpg', array('width'=>'40%','height'=>'40%','alt' => $detalle['Producto']['nombre_producto'])) ?></td>-->
<!--        <td><?php //echo $detalle['Producto']['codigo_producto']; ?></td>-->
        <td><?php echo $detalle['VInformeDetallado']['regional_sucursal']; ?></td>
        <td><?php echo $detalle['VInformeDetallado']['nombre_sucursal']; ?></td>
<!--        <td><?php // echo $detalle['Sucursale']['ceco_sucursal']; ?></td>
        <td><?php // echo $detalle['Sucursale']['oi_sucursal']; ?></td>-->
        <td><?php echo $detalle['VInformeDetallado']['tipo_categoria_descripcion']; ?></td>
        <td><?php echo $detalle['VInformeDetallado']['nombre_producto']; ?></td>        
        <td><?php echo $detalle['VInformeDetallado']['pedido_fecha']; ?></td>
        <td><?php echo number_format($detalle['VInformeDetallado']['cantidad_pedido'],0,",","."); ?>&nbsp;<?php echo $detalle['VInformeDetallado']['medida_producto']; ?></td>
<!--        <td>$ <?php // echo number_format($detalle['VInformeDetallado']['precio_producto'], 2, ',', '.'); ?></td>-->
        <td><?php echo ($detalle['VInformeDetallado']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['VInformeDetallado']['precio_producto'] + ($detalle['VInformeDetallado']['precio_producto'] * $detalle['VInformeDetallado']['iva_producto'])), 2, ',', '.'); ?></td>
        <td>$ <?php echo number_format(($detalle['VInformeDetallado']['precio_producto'] + ($detalle['VInformeDetallado']['precio_producto'] * $detalle['VInformeDetallado']['iva_producto'])) * $detalle['VInformeDetallado']['cantidad_pedido'], 2, ',', '.'); ?></td>
    </tr> 
        <?php
        $meses = array('0'=>'INSTALACION','1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
            $data_homologo = '';
            
            if($empresa_logistica == '1'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa1_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa1_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa1_precio'], 2, ',', '.'));
            }
            if($empresa_logistica == '2'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa2_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa2_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa2_precio'], 2, ',', '.'));
            }
            if($empresa_logistica == '3'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa3_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa3_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa3_precio'], 2, ',', '.'));
            }
            if($empresa_logistica == '4'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa4_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa4_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa4_precio'], 2, ',', '.'));
            }
            if($empresa_logistica == '5'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa5_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa5_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa5_precio'], 2, ',', '.'));
            }
            if($empresa_logistica == '6'){
                $data_homologo =  utf8_decode($detalle['VInformeDetallado']['empresa6_codigo']) . ';' .
                         utf8_decode($detalle['VInformeDetallado']['empresa6_nombre']). ';' .
                         utf8_decode(number_format($detalle['VInformeDetallado']['empresa6_precio'], 2, ',', '.'));
            }
            
            $data_csv = utf8_decode($detalle['VInformeDetallado']['nombre_empresa']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['sucursal_departamento']). ';' .
                utf8_decode($detalle['VInformeDetallado']['sucursal_municipio']). ';' .
                utf8_decode($detalle['VInformeDetallado']['direccion_sucursal']). ';' .
                utf8_decode('#000'. $detalle['VInformeDetallado']['id']). ';' .
                utf8_decode($detalle['VInformeDetallado']['nombre_estado']). ';' .
                utf8_decode($detalle['VInformeDetallado']['regional_sucursal']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['nombre_sucursal']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['ceco_sucursal']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['oi_sucursal']) . '.0;' .
                utf8_decode($detalle['VInformeDetallado']['tipo_categoria_descripcion']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['codigo_producto']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['nombre_producto']) . ';' .
                utf8_decode($detalle['VInformeDetallado']['pedido_fecha']) . ';' .
                utf8_decode(substr($detalle['VInformeDetallado']['fecha_aprobado_pedido'],0,10)) . ';' .
                utf8_decode(substr($detalle['VInformeDetallado']['fecha_despacho'],0,10)) . ';' .
                utf8_decode(substr($detalle['VInformeDetallado']['fecha_entrega_1'],0,10)) . ';' .
                utf8_decode(substr($detalle['VInformeDetallado']['fecha_entrega_2'],0,10)) . ';' .
                $meses[$detalle['VInformeDetallado']['mes_pedido']] .';' .
                $detalle['VInformeDetallado']['clasificacion_pedido'] .';' .
                $detalle['VInformeDetallado']['cantidad_pedido'] .';' .
                $detalle['VInformeDetallado']['medida_producto'] .';' .
                $detalle['VInformeDetallado']['precio_producto'] . ';' .
                ($detalle['VInformeDetallado']['iva_producto']*100) . '%;' .
                number_format(($detalle['VInformeDetallado']['precio_producto'] + ($detalle['VInformeDetallado']['precio_producto'] * $detalle['VInformeDetallado']['iva_producto'])), 2, ',', '.') . ';' .
                number_format((($detalle['VInformeDetallado']['precio_producto'] + ($detalle['VInformeDetallado']['precio_producto'] * $detalle['VInformeDetallado']['iva_producto'])) * $detalle['VInformeDetallado']['cantidad_pedido']), 2, ',', '.') .';'.
                $data_homologo;
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));

            endforeach;
            fclose($file);
        }
        ?>
    <tr>
        <td colspan="9" class="text-center"><b>&nbsp; Total >> </b></td>
        <td><b>$ <?php echo number_format($total_final, 2, ',', '.');?></b></td>
    </tr>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>