<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// print_r($pedidos);
?>
<script>
    $(function() {
        $('#PedidoEmpresaId').change(function() {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    UserEmpresaId: $('#PedidoEmpresaId').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });

            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Sucursales para esta Empresa');
                } else {
                    document.getElementById('PedidoSucursalId').innerHTML = '';
                    document.getElementById('PedidoRegionalSucursal').innerHTML = '';
                    $('#PedidoSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Todos';
                            op_default.value = '0';
                            document.getElementById('PedidoSucursalId').add(op_default, null);

                            var op_default_reg = document.createElement('option');
                            op_default_reg.text = 'Todos';
                            op_default_reg.value = '0';
                            document.getElementById('PedidoRegionalSucursal').add(op_default_reg, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidoSucursalId').add(opcion, null);

                        var opcion_reg = document.createElement('option');
                        opcion_reg.text = data[i].Sucursale.regional_sucursal;
                        opcion_reg.value = data[i].Sucursale.regional_sucursal;
                        document.getElementById('PedidoRegionalSucursal').add(opcion_reg, null);

                    }
                    $("#PedidoRegionalSucursal option").each(function() {
                        $(this).siblings("[value='" + this.value + "']").remove();
                    });

                    if ($('#PedidoEmpresaId').val() == -1)
                        $('#PedidoSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });

    $(function() {
        $('#PedidoRegionalSucursal').change(function() {
            $.ajax({
                url: '../users/sucursales/',
                type: 'POST',
                data: {
                    UserEmpresaId: $('#PedidoEmpresaId').val(),
                    PedidoRegionalSucursal2: $('#PedidoRegionalSucursal option:selected').text()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });

            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Sucursales para esta Empresa');
                } else {
                    document.getElementById('PedidoSucursalId').innerHTML = '';
                    $('#PedidoSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opción';
                            op_default.value = '0';
                            document.getElementById('PedidoSucursalId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.regional_sucursal + ' - ' + data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidoSucursalId').add(opcion, null);

                    }
                    if ($('#PedidoRegionalSucursal').val() == -1)
                        $('#PedidoSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
    $(function() {
        $('#PedidoPedidoFecha').datepicker({
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
        $('#PedidoFechaEntregado').datepicker({
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
    $(function() {
        $("#slider").slider({
            value: 4,
            min: 0,
            max: 20,
            step: 1,
            slide: function(event, ui) {
                $("#amount").val("Rotulos a imprimir: " + ui.value);
            }
        });
        $("#amount").val("Rotulos a imprimir: " + $("#slider").slider("value"));
    });

    function rotulos(id) {
        url = "rotulos?id=" + id + "&rotulos=" + $("#slider").slider("value");
        window.open(url, '_blank');
        // <a href="/kopan/pedidos/rotulos" class="glyphicon glyphicon-barcode"> </a>
    }
</script>

<h2><span class="glyphicon glyphicon-filter"></span> ORDENES DE PEDIDO - CONSULTAR ORDEN</h2>
<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'search_orden/'))); ?>
<table class="table table-condensed ">
    <tr>
        <td>Empresas:</td>
        <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td colspan="3"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options' => $regional, 'empty' => 'Todos', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Sucursales:</td>
        <td colspan="3"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>No. Orden:</td>
        <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td>Fecha Orden:</td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td>Estado Orden:</td>
        <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>No. Gu&iacute;a:</td>
        <td><?php echo $this->Form->input('guia_despacho', array('type' => 'text', 'size' => '15', 'maxlength' => '20', 'label' => false)); ?></td>
        <td><b>Fecha de Entrega:</b></td>
        <td><?php echo $this->Form->input('fecha_entregado', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<input type="text" id="amount" readonly style="border:0; color: #e32 ; font-weight:bold;">
<div id="slider"></div><br>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>No. Orden</th>
        <th>No. Consecutivo</th>
        <th>Datos Empresa</th>
        <th>Fechas Orden</th>
        <th>Direcci&oacute;n Envio</th>
        <!-- <th>E-mail / Tel&eacute;fono </th> -->
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones....</th>
    </tr>
    <?php
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido):
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

                            <?php echo " " . $this->Html->link("Reporte GRUPO EMPRESARIAL SHALOM <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_shalom', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte MEGAEXPERTOS <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_megaexpertos', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CLICK p <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_click_point', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL ZOE <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_zoe', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO 1A <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_1a', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO KAPITAL <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_kapital', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL BIOCENTER <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_biocenter', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL CCE AMP IV 2022 <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_cce_amp', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <div class="footer-container-btn">
                        <button type="button" id='<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>' class="btn btn-secondary btn-lg m-2 p-3">CERRAR</button>
                    </div>
                </div>
            </div>
            <tr>
                <td><span style='color:red; font-weight: bold;'>#000<?php echo $pedido['Pedido']['id']; ?></span></td>
                <td><span style='color:red; font-weight: bold;'><?php echo $pedido['Pedido']['consecutivo']; ?></span></td>
                <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa']; ?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal']; ?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal']; ?></td>
                <td style="font-size: 10px;"><b>Pedido:</b><br><?php echo substr($pedido['Pedido']['fecha_orden_pedido'], 0, 10); ?><br><b>Aprobado:</b><br><?php echo substr($pedido['Pedido']['fecha_aprobado_pedido'], 0, 10); ?><br><b>Despachado:</b><br><?php echo substr($pedido['Pedido']['fecha_despacho'], 0, 10); ?><br><b>Entregado:</b><br><?php echo substr($pedido['Pedido']['fecha_entregado'], 0, 10); ?> </td>
                <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php echo $pedido['Municipio2']['nombre_municipio']; ?><br>Dir: <?php echo $pedido['Sucursale']['direccion_sucursal']; ?><br><?php echo $pedido['Sucursale']['email_sucursal']; ?><br><?php echo $pedido['Sucursale']['telefono_contacto']; ?> </td>
                <!-- <td></td>-->
                <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
                <td><?php echo $pedido['EstadoPedido']['nombre_estado'];
                    if ($pedido['EstadoPedido']['id'] == '5') {
                        echo "<br><b>Gu&iacute;a: " . $pedido['Pedido']['guia_despacho'] . "</b>";
                    }
                    if ($pedido['Pedido']['archivo_cumplido']) {
                        echo '<a href="../' . $pedido['Pedido']['archivo_cumplido'] . '" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }
                    ?> </td>
                <td>
                    <?php
                    if ($pedido['Pedido']['pedido_estado_pedido'] == '1') {
                    ?>
                        <div title="Continuar Pedido"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
                    <?php
                    } else {
                    ?>
                        <div class="ver_pedido" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>

                        <!-- <div title="Imprimir Reporte Shalom"><?php /* echo $this->Html->link(__('', true), array('controller' => 'pedidos', 'action' => 'pedido_pdf_shalom', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank', 'escape' => false)); */ ?></div> -->

                        <div id='<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>' title="Imprimir Reporte Shalom">
                            <p class="glyphicon glyphicon-print text-primary" style="cursor:pointer"></p>
                        </div>

                        <div class="copiar_pedido" title="Copiar Pedido"><?php echo $this->Html->link(__(' ', true), array('action' => 'copiar_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-transfer', 'escape' => false)); ?></div>
                        <div class="rotulos" title="Rotulos Pedido"><a class="glyphicon glyphicon-barcode" onclick="rotulos(<?php echo $pedido['Pedido']['id']; ?>)"> </a>
                            <?php // echo $this->Html->link(__(' ', true), array('action' => 'rotulos', ), array('class' => 'glyphicon glyphicon-barcode', 'escape' => false)); 
                            ?>
                        </div>
                        <div class="auditorias" title="Auditoria"><?php echo $this->Html->link(__('', true), array('action' => 'auditorias', base64_encode($pedido['Pedido']['id'])), array('class' => 'glyphicon glyphicon-exclamation-sign', 'escape' => false)); ?></div>
                        <div class="observaciones" title="Cambiar Observaciones"><?php echo $this->Html->link(__('', true), array('action' => 'observaciones', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-list-alt', 'escape' => false)); ?></div>

                    <?php
                    }
                    ?>
                </td>
            </tr>
            <script>
                $('#<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click",function(){
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display","flex")
                })

                $('#<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click",function(e){
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display","none")
                })
            </script>
        <?php
        endforeach;
    } else {
        ?>
        <tr>
            <td colspan="8" class="text-center">No existen ordenes de pedido en el sistema.</td>
        </tr>
    <?php
    }
    ?>
</table>
<p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?> </p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | <?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
</div>