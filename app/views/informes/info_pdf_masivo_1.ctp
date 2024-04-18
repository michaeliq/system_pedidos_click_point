<?php ?>
<script>

    $(document).ready(function () {
        $('#PedidoInfoPdfMasivoForm').attr('target', '_blank');

        $(".check_todos").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_cien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_doscien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_doscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_doscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_trescien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_trescien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_trescien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cuatrocien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_cuatrocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cuatrocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cincocien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_cincocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cincocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_seiscien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_seiscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_seiscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_sietecien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_sietecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_sietecien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_ochocien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_ochocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_ochocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_nuevecien").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_nuevecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_nuevecien:checkbox:checked").removeAttr("checked");
            }
        });
    });

    $(function () {
        $('#PedidoDespachoEmpresaId').change(function () {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    UserEmpresaId: $('#PedidoDespachoEmpresaId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Sucursales para esta Empresa');
                } else {
                    document.getElementById('PedidoDespachoSucursalId').innerHTML = '';
                    document.getElementById('PedidoDespachoRegionalSucursal').innerHTML = '';
                    $('#PedidoDespachoSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Todos';
                            op_default.value = '0';
                            document.getElementById('PedidoDespachoSucursalId').add(op_default, null);

                            var op_default_reg = document.createElement('option');
                            op_default_reg.text = 'Todos';
                            op_default_reg.value = '0';
                            document.getElementById('PedidoDespachoRegionalSucursal').add(op_default_reg, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidoDespachoSucursalId').add(opcion, null);


                        var opcion_reg = document.createElement('option');
                        opcion_reg.text = data[i].Sucursale.regional_sucursal;
                        opcion_reg.value = data[i].Sucursale.regional_sucursal;
                        document.getElementById('PedidoDespachoRegionalSucursal').add(opcion_reg, null);

                    }

                    $("#PedidoDespachoRegionalSucursal option").each(function () {
                        $(this).siblings("[value='" + this.value + "']").remove();
                    });

                    if ($('#PedidoDespachoEmpresaId').val() == -1)
                        $('#PedidoDespachoSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });

    $(function () {
        $('#PedidoDespachoRegionalSucursal').change(function () {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    PedidoRegionalSucursal2: $('#PedidoDespachoRegionalSucursal option:selected').text()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Sucursales para esta Empresa');
                } else {
                    document.getElementById('PedidoDespachoSucursalId').innerHTML = '';
                    $('#PedidoDespachoSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opción';
                            op_default.value = '0';
                            document.getElementById('PedidoDespachoSucursalId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.regional_sucursal + ' - ' + data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidoDespachoSucursalId').add(opcion, null);

                    }
                    if ($('#PedidoDespachoRegionalSucursal').val() == -1)
                        $('#PedidoDespachoSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
    $(function () {
        $('#PedidoDespachoFechaAprobadoPedido').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            //yearRange: "-120:+0",
            maxDate: '0'
        });
    });
</script>

<h2><span class="glyphicon glyphicon-book"></span> INFORME ORDENES MASIVAS PDF</h2>
<?php echo $this->Form->create('PedidoDespacho', array('url' => array('controller' => 'informes', 'action' => 'info_pdf_masivo'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Empresas:</b></td>
        <td colspan="5"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Regional:</b></td>
        <td colspan="5"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select','options' => $regional,'empty' => 'Todos', 'label' => false)); ?></td>
    </tr>
    <tr>    
        <td><b>Sucursales:</b></td>
        <td colspan="5"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Orden Inicial:</b></td>
        <td><?php echo $this->Form->input('id_inicial', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        <td><b>No. Orden Final:</b></td>
        <td><?php echo $this->Form->input('id_final', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        <td><b>Fecha Aprobación:</b></td>
        <td><?php echo $this->Form->input('fecha_aprobado_pedido', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>

<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'informes', 'action' => 'info_pdf_masivo'))); ?>
<?php 
if (count($pedidos) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('GENERAR PDF MASIVO', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
</div>
<div>&nbsp;</div>
<div class="text-center">Mostrando <?php echo count($pedidos); ?> registros.</div>
<?php
}
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th style="font-size: 10px; width: 90px;">
            <input name="Todos" type="checkbox" value="1" class="check_todos"/> 0 a 199<br>
            <input name="200a399" type="checkbox" value="1" class="check_cien"/> 200 a 399<br>
            <input name="400a599" type="checkbox" value="1" class="check_doscien"/> 400 a 599<br>
            <input name="600a799" type="checkbox" value="1" class="check_trescien"/> 600 a 799<br>
            <input name="800a999" type="checkbox" value="1" class="check_cuatrocien"/> 800 a 999<br>
            <input name="1000a1199" type="checkbox" value="1" class="check_cincocien"/> 1000 a 1199<br>
            <input name="1200a1399" type="checkbox" value="1" class="check_seiscien"/> 1200 a 1399<br>
            <input name="1400a1599" type="checkbox" value="1" class="check_sietecien"/> 1400 a 1599<br>
            <input name="1600a1799" type="checkbox" value="1" class="check_ochocien"/> 1600 a 1799
            <input name="1800a1999" type="checkbox" value="1" class="check_nuevecien"/> 1800 a 1999
        </th>
        <th>No. Orden</th>
        <th>Datos Empresa</th>
        <th>Fecha Orden</th>
        <th>Direcci&oacute;n Envio</th>
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
    $i = 0;
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido):
            
            if($i >= 0 && $i <= 199){
                $class_ck = 'ck';
            }
            if($i > 199 && $i <= 399){
                $class_ck = 'ck_cien';
            }
            if($i > 399 && $i <= 599){
                $class_ck = 'ck_doscien';
            }
            if($i > 599 && $i <= 799){
                $class_ck = 'ck_trescien';
            }
            if($i > 799 && $i <= 999){
                $class_ck = 'ck_cuatrocien';
            }
            if($i > 999 && $i <= 1199){
                $class_ck = 'ck_cincocien';
            }
            if($i > 1199 && $i <= 1399){
                $class_ck = 'ck_seiscien';
            }
            if($i > 1399 && $i <= 1599){
                $class_ck = 'ck_sietecien';
            }
            if($i > 1599 && $i <= 1799){
                $class_ck = 'ck_ochocien';
            }
            if($i > 1799 && $i <= 1999){
                $class_ck = 'ck_nuevecien';
            }
            
            ?>
    <tr>
        <td><?php echo  $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => $class_ck)); ?></td>
        <td><span style='color:red;'><b>#000<?php echo $pedido['Pedido']['id']; ?></b></span></td>
        <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal'];?></td>
        <td style="font-size: 10px;" ><b>Pedido:</b><br><?php echo substr($pedido['Pedido']['fecha_orden_pedido'], 0, 10); ?><br><b>Aprobado:</b><br><?php echo substr($pedido['Pedido']['fecha_aprobado_pedido'], 0, 10); ?><br><b>Despachado:</b><br><?php echo substr($pedido['Pedido']['fecha_despacho'], 0, 10); ?> </td>
        <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php  echo $pedido['Municipio2']['nombre_municipio']; ?><br><?php echo $pedido['Sucursale']['direccion_sucursal']; ?> </td>
        <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
        <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
        <td>

            <div title="Ver"><?php echo $this->Html->link(__('', true), array('controller'=>'pedidos','action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'target'=>'_blank','escape' => false)); ?></div>

        </td>
    </tr>
            <?php
            $i++;
        endforeach;
    } else {
        ?>
    <tr>
        <td colspan="8" class="text-center">No existen ordenes de pedido pendientes para despachar.</td>
    </tr>
        <?php
    }
    ?>
</table>
<?php 
if (count($pedidos) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('GENERAR PDF MASIVO', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
</div>
<div>&nbsp;</div>
<?php
}
?>
<?php echo $this->Form->end(); ?>