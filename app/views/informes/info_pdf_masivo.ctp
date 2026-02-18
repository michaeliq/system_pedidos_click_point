<?php
$shalom_option = false;
?>
<script>
    let shalom_option = false;
    $(document).ready(function() {
        $('#PedidoInfoPdfMasivoForm').attr('target', '_blank');

        $(".check_todos").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_cien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_doscien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_doscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_doscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_trescien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_trescien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_trescien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cuatrocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_cuatrocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cuatrocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_cincocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_cincocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_cincocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_seiscien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_seiscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_seiscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_sietecien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_sietecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_sietecien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_ochocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_ochocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_ochocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_nuevecien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_nuevecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_nuevecien:checkbox:checked").removeAttr("checked");
            }
        });

        // Desde 1000
        $(".check_milcien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milcien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milcien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milunocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milunocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milunocien:checkbox:checked").removeAttr("checked");
            }
        });


        $(".check_mildoscien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_mildoscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_mildoscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_miltrescien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_miltrescien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_miltrescien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milcuatrocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milcuatrocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milcuatrocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milcincocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milcincocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milcincocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milseiscien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milseiscien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milseiscien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milsietecien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milsietecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milsietecien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milochocien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milochocien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milochocien:checkbox:checked").removeAttr("checked");
            }
        });

        $(".check_milnuevecien").click(function(event) {
            if ($(this).is(":checked")) {
                $(".ck_milnuevecien:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_milnuevecien:checkbox:checked").removeAttr("checked");
            }
        });
    });

    $(function() {
        $('#PedidoDespachoEmpresaId').change(function() {
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

                    $("#PedidoDespachoRegionalSucursal option").each(function() {
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

    $(function() {
        $('#PedidoDespachoRegionalSucursal').change(function() {
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
    $(function() {
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
        <td colspan="5"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Todos', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Regional:</b></td>
        <td colspan="5"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options' => $regional, 'empty' => 'Todos', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Sucursales:</b></td>
        <td colspan="5"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Todos', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Orden Inicial:</b></td>
        <td><?php echo $this->Form->input('id_inicial', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td><b>No. Orden Final:</b></td>
        <td><?php echo $this->Form->input('id_final', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td><b>Fecha Aprobación:</b></td>
        <td><?php echo $this->Form->input('fecha_aprobado_pedido', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Ordenes (comas)</b></td>
        <td colspan="5">
            <?php echo $this->Form->input('id_ordenes', array('type' => 'textarea', 'cols' => '70', 'rows' => '3', 'label' => false, 'placeholder' => '# Ordenes separadas por comas')); ?>
        </td>
    </tr>
</table>
<div class="text-center">

    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>

<?php echo $this->Form->create('Pedido', array('url' => array(
    'controller' => 'informes',
    'action' => 'info_pdf_masivo'
)));
?>
<?php
if (count($pedidos) > 0) {
?>
    <div style="display: flex; justify-content:center; align-items:center; gap: 2rem;">
        <div>
            <!-- <?php /* echo $this->Form->input('optional_report', array('type' => 'select','label' => 'Generar reporte a nombre de .' , 'options' => [
                "centro_aseo" => "Centro Aseo",
                "shalom" => "Shalom",
                "click_point" => "CLick Point",
                "megaexpertos" => "Megaexpertos",
                "ut_cce_amp_iv" => "UT CCE AMP IV",
            ])); */ ?> -->

            <?php echo $this->Form->input('optional_report', array('type' => 'checkbox', 'label' => "Reporte CA", "checked" => "checked")); ?>
            <?php echo $this->Form->input('optional_report_shalom', array('type' => 'checkbox', 'label' => "Reporte Shalom")); ?>
            <?php echo $this->Form->input('optional_report_megaexpertos', array('type' => 'checkbox', 'label' => "Reporte Megaexpertos")); ?>
            <?php echo $this->Form->input('optional_report_click_point', array('type' => 'checkbox', 'label' => "Reporte Click Point")); ?>
            <?php echo $this->Form->input('optional_report_ut_zoe', array('type' => 'checkbox', 'label' => "Reporte UT ZOE")); ?>
            <?php echo $this->Form->input('optional_report_ut_biocenter', array('type' => 'checkbox', 'label' => "Reporte UT BIOCENTER")); ?>
            <?php echo $this->Form->input('optional_report_consorcio_kapital', array('type' => 'checkbox', 'label' => "Reporte CONSORCIO KAPITAL")); ?>
            <?php echo $this->Form->input('optional_report_consorcio_1a', array('type' => 'checkbox', 'label' => "Reporte CONSORCIO 1A")); ?>
            <?php echo $this->Form->input('optional_report_ut_cce_amp', array('type' => 'checkbox', 'label' => "Reporte UT CCE AMP IV")); ?>
            <?php echo $this->Form->input('optional_report_limpio_plus', array('type' => 'checkbox', 'label' => "Reporte LIMPIO PLUS")); ?>
            <?php echo $this->Form->input('optional_report_klean_logist', array('type' => 'checkbox', 'label' => "Reporte KLEAN LOG")); ?>
        </div>
        <div>
            <?php echo $this->Form->button('GENERAR PDF MASIVO', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
        </div>
    </div>
    <div>&nbsp;</div>
    <div class="text-center">Mostrando <?php echo count($pedidos); ?> registros.</div>
<?php
}
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th style="font-size: 10px; width: 90px;">
            <input name="Todos" type="checkbox" value="1" class="check_todos" /> 0 a 99<br>
            <input name="100a199" type="checkbox" value="1" class="check_cien" /> 100 a 199<br>
            <input name="200a299" type="checkbox" value="1" class="check_doscien" /> 200 a 299<br>
            <input name="300a399" type="checkbox" value="1" class="check_trescien" /> 300 a 399<br>
            <input name="400a499" type="checkbox" value="1" class="check_cuatrocien" /> 400 a 499<br>
            <input name="500a599" type="checkbox" value="1" class="check_cicnocien" /> 500 a 599<br>
            <input name="600a699" type="checkbox" value="1" class="check_seiscien" /> 600 a 699<br>
            <input name="700a799" type="checkbox" value="1" class="check_sietecien" /> 700 a 799<br>
            <input name="800a899" type="checkbox" value="1" class="check_ochocien" /> 800 a 899<br>
            <input name="900a999" type="checkbox" value="1" class="check_nuevecien" /> 900 a 999<br>
            <input name="1000a1099" type="checkbox" value="1" class="check_milcien" /> 1000 a 1099<br>
            <input name="1100a1199" type="checkbox" value="1" class="check_milunocien" /> 1100 a 1199<br>
            <input name="1200a1299" type="checkbox" value="1" class="check_mildoscien" /> 1200 a 1299<br>
            <input name="1300a1399" type="checkbox" value="1" class="check_miltrescien" /> 1300 a 1399<br>
            <input name="1400a1499" type="checkbox" value="1" class="check_milcuatrocien" /> 1400 a 1499<br>
            <input name="1500a1599" type="checkbox" value="1" class="check_milcincocien" /> 1500 a 1599<br>
            <input name="1600a1699" type="checkbox" value="1" class="check_milseiscien" /> 1600 a 1699<br>
            <input name="1700a1799" type="checkbox" value="1" class="check_milsietecien" /> 1700 a 1799<br>
            <input name="1800a1899" type="checkbox" value="1" class="check_milochocien" /> 1800 a 1899<br>
            <input name="1900a1999" type="checkbox" value="1" class="check_milnuevecien" /> 1900 a 1999
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
        foreach ($pedidos as $pedido) :

            if ($i >= 0 && $i <= 99) {
                $class_ck = 'ck';
            }
            if ($i > 100 && $i <= 199) {
                $class_ck = 'ck_cien';
            }
            if ($i > 200 && $i <= 299) {
                $class_ck = 'ck_doscien';
            }
            if ($i > 300 && $i <= 399) {
                $class_ck = 'ck_trescien';
            }
            if ($i > 400 && $i <= 499) {
                $class_ck = 'ck_cuatrocien';
            }
            if ($i > 500 && $i <= 599) {
                $class_ck = 'ck_cincocien';
            }
            if ($i > 600 && $i <= 699) {
                $class_ck = 'ck_seiscien';
            }
            if ($i > 700 && $i <= 799) {
                $class_ck = 'ck_sietecien';
            }
            if ($i > 800 && $i <= 899) {
                $class_ck = 'ck_ochocien';
            }
            if ($i > 900 && $i <= 999) {
                $class_ck = 'ck_nuevecien';
            }
            if ($i > 1000 && $i <= 1099) {
                $class_ck = 'ck_milcien';
            }
            if ($i > 1100 && $i <= 1199) {
                $class_ck = 'ck_milunocien';
            }
            if ($i > 1200 && $i <= 1299) {
                $class_ck = 'ck_mildoscien';
            }
            if ($i > 1300 && $i <= 1399) {
                $class_ck = 'ck_miltrescien';
            }
            if ($i > 1400 && $i <= 1499) {
                $class_ck = 'ck_milcuatrocien';
            }
            if ($i > 1500 && $i <= 1599) {
                $class_ck = 'ck_milcincocien';
            }
            if ($i > 1600 && $i <= 1699) {
                $class_ck = 'ck_milseiscien';
            }
            if ($i > 1700 && $i <= 1799) {
                $class_ck = 'ck_milsietecien';
            }
            if ($i > 1800 && $i <= 1899) {
                $class_ck = 'ck_milochocien';
            }
            if ($i > 1900 && $i <= 1999) {
                $class_ck = 'ck_milnuevecien';
            }

    ?>
            <div
                id='<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>'
                style="
                position:fixed; 
                top:0; 
                left:0; 
                width:100%; 
                height:100vh; 
                background-color:#0003;
                justify-content:center;
                align-items:center;
                display:none;">
                <div class="print-dialog-box" style="padding:2rem; width: 600px; height: auto; display:flex;flex-direction:column;row-gap:1rem; justify-content:center; align-items:center; background-color: #ddd; border-radius:25px;">
                    <div class="header-dialog-box">
                        <h3 class=""><?php echo "Generar reporte de Orden #" . $pedido['Pedido']['id']; ?></h3>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CENTRO ASEO <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte MEGAEXPERTOS <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_megaexpertos', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CLICK POINT <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_click_point', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte UT ZOE <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_zoe', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte UT BIOCENTER <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_biocenter', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CONSORCIO 1A <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_1a', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CONSORCIO KAPITAL <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_kapital', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL CCE AMP IV 2022 <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_cce_amp', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte LIMPIO PLUS <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_limpio_plus', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO KLEAN Y LOGISTIC <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_klean_logist', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <div class="footer-container-btn">
                        <button type="button" id='<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>' class="btn btn-secondary btn-lg m-2 p-3">CERRAR</button>
                    </div>
                </div>
            </div>
            <tr>
                <td><?php echo  $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => $class_ck)); ?></td>
                <td><span style='color:red;'><b>#000<?php echo $pedido['Pedido']['id']; ?></b></span></td>
                <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa']; ?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal']; ?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal']; ?></td>
                <td style="font-size: 10px;"><b>Pedido:</b><br><?php echo substr($pedido['Pedido']['fecha_orden_pedido'], 0, 10); ?><br><b>Aprobado:</b><br><?php echo substr($pedido['Pedido']['fecha_aprobado_pedido'], 0, 10); ?><br><b>Despachado:</b><br><?php echo substr($pedido['Pedido']['fecha_despacho'], 0, 10); ?> </td>
                <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php echo $pedido['Municipio2']['nombre_municipio']; ?><br><?php echo $pedido['Sucursale']['direccion_sucursal']; ?> </td>
                <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
                <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
                <td>

                    <div title="Ver"><?php echo $this->Html->link(__('', true), array('controller' => 'pedidos', 'action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'target' => '_blank', 'escape' => false)); ?></div>

                    <!-- <div title="Imprimir Reporte Shalom"><?php /* echo $this->Html->link(__('', true), array('controller' => 'pedidos', 'action' => 'pedido_pdf_shalom', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank', 'escape' => false)); */ ?></div> -->

                    <div id='<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>' title="Imprimir Reporte Shalom">
                        <p class="glyphicon glyphicon-print text-primary" style="cursor:pointer"></p>
                    </div>
                </td>
            </tr>
            <script>
                $('#<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click", function() {
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display", "flex")
                })

                $('#<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click", function(e) {
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display", "none")
                })
            </script>
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