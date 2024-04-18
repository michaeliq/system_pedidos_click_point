<?php

if (!empty($id)) {
    $var = 'regresar_edit2';
    $location = '../list_ordenes';
    $pdf = '../pedido_pdf/' . $detalles['0']['Pedido']['id'];
    $pdf_v2 = '../pedido_pdf_v2/' . $detalles['0']['Pedido']['id'];
} else {
    $var = 'regresar_edit';
    $location = 'list_ordenes';
    $pdf = 'pedido_pdf/' . $detalles['0']['Pedido']['id'];
    $pdf_v2 = 'pedido_pdf_v2/' . $detalles['0']['Pedido']['id'];
}
?>
<script type="text/javascript">
    window.open("<?php echo $pdf; ?>");
//    window.open("<?php echo $pdf_v2; ?>");
    $(function () {
        $('#<?php echo $var; ?>').click(function () {
            window.location = "<?php echo $location; ?>";
        });
    });
</script>
<table class="table-striped table-bordered table-condensed" align="center"> 
    <?php /*<tr>
        <td colspan="2"><?php echo $html->image('kopan_logo.png', array('alt' => 'Kopan&Coba')) ?></td>
        <td colspan="3">
            <div>Cra 28B # 77-12  <br>Tel: 606 84 33 / 484 91 20 </div>
            <div>kopancoba@kopancoba.com</div>
            <div>www.kopancoba.com</div>
        </td>
        <td colspan="3">
            <div>KOPANCOBA DELIVERY SASsdfsdfsfsfsfsd</div>
            <div>Nit 900.751.920-8</div>
            <div>Orden de Pedido: <span style='color:red;'>#000<?php echo $detalles['0']['Pedido']['id']; ?></span></div>
        </td>
    </tr> */ ?>
    <tr>
        <td colspan="8"><h4>Orden de Pedido: <span style='color:red;'>#000<?php echo $detalles['0']['Pedido']['id']; ?></span></h4></td>
    </tr>
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
        <td colspan="8">No. Guia: <?php echo $detalles['0']['Pedido']['guia_despacho']; ?></td>
    </tr>
    <tr>
        <td colspan="8">Tipo Pedido: <?php echo $detalles['0']['TipoPedido']['nombre_tipo_pedido'] ?></td>    
    </tr>
    <tr>
        <td colspan="8">Tipo Movimiento: <?php echo $detalles['0']['TipoMovimiento']['nombre_tipo_movimiento'] ?></td>    
    </tr>
    <tr>
        <td colspan="8"><b>Pedido creado por:</b> <?php echo $detalles['0']['User']['nombres_persona']; ?></td>
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
            $total_sin_iva = $total_sin_iva + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['cantidad_pedido']);
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
    ?>
    <tr>
        <td colspan="7">&nbsp; Total sin IVA >> </td>
        <td>$ <?php echo number_format($total_sin_iva, 2, ',', '.');?></td>
    </tr>
    <tr>
        <td colspan="7">&nbsp; IVA >> </td>
        <td>$ <?php echo number_format($total_final - $total_sin_iva, 2, ',', '.');?></td>
    </tr>
    <tr>
        <td colspan="7">&nbsp; Total >> </td>
        <td>$ <?php echo number_format($total_final, 2, ',', '.');?></td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8"><div>&nbsp;</div>
            <p>Recibido Por: _______________________________________________</p></td>
    </tr>
</table>
<div>&nbsp;</div>
<div class="text-center"><?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => $var, 'class' => 'btn btn-warning')); ?></div>