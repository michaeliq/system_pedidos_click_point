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

        $('#PedidoDespachoPedidoFechaFactura').datepicker({
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

<h2><span class="glyphicon glyphicon-star"></span> FACTURADO</h2>
<?php echo $this->Form->create('PedidoDespacho', array('url' => array('controller' => 'pedidos', 'action' => 'facturado'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Empresas:</b></td>
        <td colspan="4"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Regional:</b></td>
        <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select','options' => $regional,'empty' => 'Todos', 'label' => false)); ?></td>
        <td><b>Sucursales:</b></td>
        <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Orden:</b></td>
        <td><?php echo $this->Form->input('id', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        <td><b>Fecha Orden:</b></td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>No. Factura:</b></td>
        <td><?php echo $this->Form->input('numero_factura', array('type' => 'text', 'size' => '15', 'maxlength' => '15', 'label' => false,'placeholder'=>'No. Factura')); ?></td>
        <td><b>Fecha Factura:</b></td>
        <td><?php echo $this->Form->input('fecha_factura', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Tipo Pedido:</b></td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td colspan="2"></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'facturado'))); ?>
<?php 
if (count($pedidos) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('MARCAR COMO FACTURADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
    <div class="text-center"><b>Recuerde que debe colocar un No. Factura para el pedido.</b></div>
</div>
<div>&nbsp;</div>
<?php
}
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th><input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        <th>No. Factura</th>
        <th>No. Orden</th>
        <th>Datos Empresa</th>
        <th>Fecha Orden</th>
        <th>Fecha Aprobado</th>
        <th>Fecha Despachado</th>
        <th>Fecha Facturado</th>
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido):
            ?>
    <tr>
        
        <?php if(!empty($pedido['Pedido']['numero_factura'])){ ?>
        <td><span class="glyphicon glyphicon-ok" style="color: #0a0;"></span></td>
        <td style="text-align: right; color: #0a0;"><b><?php echo $pedido['Pedido']['numero_factura']; ?></b></td>
        <?php }else{ ?>
        <td><?php echo $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => 'ck')); ?></td>
        <td><?php echo $this->Form->input('factura_'.$pedido['Pedido']['id'], array('type' => 'text', 'label' => false, 'maxlength' => '20','size'=>'20', 'placeholder' => 'No. Factura')); ?></td>
        <?php } ?>
        <td><span style='color:red;'><b>#000<?php echo $pedido['Pedido']['id']; ?></b></span></td>
        <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal'];?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_orden_pedido'], 0, 10); ?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_aprobado_pedido'], 0, 10); ?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_despacho'], 0, 10); ?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_factura'], 0, 10); ?></td>
        <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
        <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
        <td><div class="ver_pedido" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div></td>
    </tr>
            <?php
        endforeach;
    } else {
        ?>
    <tr>
        <td colspan="9" class="text-center">No existen ordenes de pedido pendientes para facturar.</td>
    </tr>
        <?php
    }
    ?>
</table>
<?php 
if (count($pedidos) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('MARCAR COMO FACTURADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
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

