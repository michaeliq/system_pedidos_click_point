<?php

if (empty($id)) {
    ?>
<script>
    alert('Se ha realizado el copiado exitoso de la orden de pedido (#000<?php echo $id_nuevo;  ?>)');
    window.location = "search_orden";
</script>
<?php
}
?>
<script>
    $(function () {
        $('#PedidoEmpresaId').change(function () {
            $.ajax({
                url: '../../users/sucursales/',
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
                    $('#PedidoSucursalId').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Todos';
                            op_default.value = '0';
                            document.getElementById('PedidoSucursalId').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Sucursale.nombre_sucursal;
                        opcion.value = data[i].Sucursale.id;
                        document.getElementById('PedidoSucursalId').add(opcion, null);

                    }
                    if ($('#PedidoEmpresaId').val() == -1)
                        $('#PedidoSucursalId').attr({
                            disabled: true
                        });
                }
            }
        });
    });
</script>
<h2><span class="glyphicon glyphicon-transfer"></span> ORDENES DE PEDIDO - COPIAR PEDIDO</h2>
<table class="table-striped table-bordered table-condensed" align="center"> 
    <tr>
        <td colspan="4">
            <div><h4>DATOS DEL CLIENTE</h4></div>
            <div>Empresa: <?php echo $detalles['0']['Empresa']['nombre_empresa']; ?></div>
            <div>Nit: <?php echo $detalles['0']['Empresa']['nit_empresa']; ?></div>
            <div>Tel&eacute;fono: <?php echo $detalles['0']['Empresa']['telefono_empresa']; ?></div>
            <div>Direcci&oacute;n: <?php echo $detalles['0']['Empresa']['direccion_empresa']; ?></div>
        </td>
        <td colspan="4">
            <div><h4>DATOS DE SUCURSAL</h4></div>
            <div><b>Sucursal: <?php echo $detalles['0']['Sucursale']['nombre_sucursal']; ?></b></div>
            <div><b>Direcci&oacute;n: <?php echo $detalles['0']['Sucursale']['direccion_sucursal']; ?></b></div>
            <div>Tel&eacute;fono: <?php echo $detalles['0']['Sucursale']['telefono_sucursal']; ?></div>
            <div>Contacto: <?php echo $detalles['0']['Sucursale']['nombre_contacto'] . ' - ' . $detalles['0']['Sucursale']['telefono_contacto']; ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="8">Fecha pedido: <?php echo $detalles['0']['Pedido']['pedido_fecha'] . ' ' . $detalles['0']['Pedido']['pedido_hora']; ?></td>
    </tr>
    <tr>
        <td colspan="8">Observaciones: <?php echo $detalles['0']['Pedido']['observaciones']; ?></td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <th></th>
<!--        <th>C&oacute;digo</th>-->
        <th>Descripci&oacute;n</th>
        <th>Categor&iacute;a <?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $categorias, 'empty' => '', 'label' => false, 'default' => '1'));                                  ?></th>
        <th>Precio</th>
        <th>IVA</th>
        <th>Valor Producto</th>
        <th>Cantidad</th>
        <th>Total</th>        
    </tr>
    <?php
    $total_final = 0;
        foreach ($detalles as $detalle) :
            $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];
            ?>
    <tr>
        <td><?php echo $html->image('productos/'.$detalle['Producto']['codigo_producto'].'.jpg', array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $detalle['Producto']['nombre_producto'])) ?></td>
<!--        <td><?php // echo $detalle['Producto']['codigo_producto']; ?></td>-->
        <td><?php echo $detalle['Producto']['nombre_producto'].' '.$detalle['Producto']['marca_producto']; ?></td>
        <td><?php echo $detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td>$ <?php echo number_format($detalle['PedidosDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td><?php echo ($detalle['PedidosDetalle']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['PedidosDetalle']['cantidad_pedido'],0,",","."); ?></td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.'); ?></td>
    </tr>
            <?php
        endforeach;
    ?><tr>
        <td colspan="7">&nbsp; Total >> </td>
        <td>$ <?php echo number_format($total_final, 2, ',', '.');?></td>
    </tr>
</table>
<div>&nbsp;</div>
<div class="text-center">
    <?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'copiar_pedido'))); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden','value'=>$id)); ?>
    <?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => 'Empresa: ')); ?>
    <?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => 'Sucursal: ')); ?>
    <div>&nbsp;</div>
    <?php echo $this->Form->button('COPIAR PEDIDO', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
    <?php echo $this->Form->end(); ?>
</div>
