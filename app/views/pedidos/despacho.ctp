<?php

echo $this->Html->script(array('pedidos/list_ordenes')); 
?>
<script>
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
                    UserEmpresaId: $('#PedidoDespachoEmpresaId').val(),
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
        $('#PedidoDespachoPedidoFecha').datepicker({
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


    $(function () {
        $("#slider").slider({
            value: 4,
            min: 0,
            max: 20,
            step: 1,
            slide: function (event, ui) {
                $("#amount").val("Rotulos a imprimir: " + ui.value);
            }
        });
        $("#amount").val("Rotulos a imprimir: " + $("#slider").slider("value"));
    });

    function rotulos(id) {
        url = "/rotulos?id=" + id + "&rotulos=" + $("#slider").slider("value");
        window.open(url, '_blank');
        // <a href="/kopan/pedidos/rotulos" class="glyphicon glyphicon-barcode"> </a>
    }
</script>

<h2><span class="glyphicon glyphicon-plane"></span> DESPACHOS</h2>
<?php echo $this->Form->create('PedidoDespacho', array('url' => array('controller' => 'pedidos', 'action' => 'despacho'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Empresas:</b></td>
        <td colspan="5"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Regional:</b></td>
        <td colspan="5"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select',/*'options' => $regional,*/'empty' => 'Todos', 'label' => false)); ?></td>
   </tr>
    <tr>
        <td><b>Sucursales:</b></td>
        <td colspan="5"><?php echo $this->Form->input('sucursal_id', array('type' => 'select',/*'options' => $sucursales, */'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Orden:</b></td>
        <td><?php echo $this->Form->input('id', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td><b>Fecha Orden:</b></td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        <td><b>Tipo Pedido:</b></td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'despacho'))); ?>
<?php 
if (count($pedidos) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('MARCAR COMO DESPACHADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
    <div class="text-center"><b>Recuerde que debe colocar un No. Guia para despachar el pedido.</b></div>
    <div class="text-center"><b>El arvhivo de la guia o cumplido debe ir sin simbolos, puntos o comas y en formato PDF.</b></div>
</div>
<div>&nbsp;</div>
<?php
}
?>
<input type="text" id="amount" readonly style="border:0; color: #e32 ; font-weight:bold;">
<div id="slider"></div><br>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th><input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        <th>No. Guia</th>
        <!-- <th>No. Factura</th>--> 
        <th>No. Orden</th>
        <th>Datos Empresa</th>
        <th>Fecha Orden</th>
        <th>Direcci&oacute;n Envio</th>
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido):
            $pedido_original = null;
            $marcar_parcial = null;
            if(!empty($pedido['Pedido']['pedido_id'])){
                $marcar_parcial = "style='background-color: #f7e99b; font-weight: bold;'";
                $pedido_original =  '#000'.$pedido['Pedido']['pedido_id'];
            }
    ?>
    <tr>
        <td><?php echo $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => 'ck')); ?></td>
        <td><?php echo $this->Form->input('guia_'.$pedido['Pedido']['id'], array('type' => 'text', 'label' => false, 'maxlength' => '20','size'=>'20', 'placeholder' => 'No. Guia')); ?></td>
        <!-- <td></td> --><?php echo $this->Form->input('factura_'.$pedido['Pedido']['id'], array('type' => 'hidden', 'label' => false, 'maxlength' => '20','size'=>'20', 'placeholder' => 'No. Factura', 'value'=>$pedido['Pedido']['id'])); ?>
        <td <?php echo $marcar_parcial; ?>><span style='color:red;  font-weight: bold;'>#000<?php echo $pedido['Pedido']['id']; ?></span><br><span style='color:green;  font-weight: bold;'><?php echo $pedido_original; ?></td>
        <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal'];?></td>
        <td style="font-size: 10px;" ><b>Pedido:</b><br><?php echo substr($pedido['Pedido']['fecha_orden_pedido'], 0, 10); ?><br><b>Aprobado:</b><br><?php echo substr($pedido['Pedido']['fecha_aprobado_pedido'], 0, 10); ?></td>
        <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php  echo $pedido['Municipio2']['nombre_municipio']; ?><br><?php echo $pedido['Sucursale']['direccion_sucursal']; ?> </td>
        <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
        <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
        <td>
            <?php 
            if ($pedido['Pedido']['pedido_estado_pedido']== '1') {
            ?>
            <div title="Continuar Pedido"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
            <?php
            }else{
            ?>

            <div class="ver_pedido" title="Ver"><?php echo $this->Html->link(__(' ', true), array('action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
            
            <div class="rotulos" title="Rotulos Pedido"><a class="glyphicon glyphicon-barcode" onclick="rotulos(<?php echo $pedido['Pedido']['id']; ?>)"></a></div>
            <div class="entregas_parciales" title="Entregas Parciales"><?php echo $this->Html->link(__(' ', true), array('action' => 'entregas_parciales', base64_encode($pedido['Pedido']['id'])), array('class' => 'glyphicon glyphicon-resize-small', 'escape' => false)); ?></div>

            <?php 
            }
            ?>
        </td>
    </tr>
            <?php
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
        <?php echo $this->Form->button('MARCAR COMO DESPACHADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
</div>
<div>&nbsp;</div>
<?php
}
?>
<?php echo $this->Form->end(); ?>
<p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?>	</p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | 	<?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>

