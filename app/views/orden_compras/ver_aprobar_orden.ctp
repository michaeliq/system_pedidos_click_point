<?php

//  print_r($ordenes); ?>
<h2>APROBAR ORDEN DE COMPRA <span style="color: blue;"><b> #000<?php echo $ordenes['0']['OrdenCompra']['id']; ?></b></span></h2>
<table class="table table-striped table-bordered table-condensed" align="center" style="width: 70%;">
    <tr>
        <td><b>Proveedor:</b></td>
        <td><?php echo $ordenes['0']['Proveedore']['nombre_proveedor']; ?></td>
        <td><b>Fecha Orden:</b></td>
        <td><?php echo $ordenes['0']['OrdenCompra']['fecha_orden_compra']; ?></td>
    </tr>   
    <tr>
        <td><b>Forma de Pago:</b></td>
        <td><?php echo $ordenes['0']['TipoFormasPago']['nombre_forma_pago']; ?></td>
        <td><b>Tipo de Orden:</b></td>
        <td><?php echo $ordenes['0']['TipoOrden']['nombre_tipo_orden']; ?></td>
    </tr>   
    <tr>
        <td><b>% ReteFuente:</b></td>
        <td><?php echo $ordenes['0']['OrdenCompra']['rte_fuente']; ?> %</td>
        <td><b>% ReteICA:</b></td>
        <td><?php echo $ordenes['0']['OrdenCompra']['rte_ica']; ?> %</td>
    </tr>
    <tr>
        <td><b>Observaciones:</b></td>
        <td><?php echo $ordenes['0']['OrdenCompra']['observaciones']; ?></td>
        <td><b>Estado Orden:</b></td>
        <td><?php echo $ordenes['0']['TipoEstadoOrden']['nombre_estado_orden']; ?></td>
    </tr>
</table>
<?php echo $this->Form->create('OrdenCompra', array('url' => array('controller' => 'ordenCompras', 'action' => 'aprobar_ordenes'))); ?>
<table class="table table-hover">
    <tr>
        <th>C&oacute;digo</th>
        <th>Descripci&oacute;n</th>
        <th>Und. Medida</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>IVA</th>
        <th>Valor IVA</th>
        <th>Valor Producto</th>
        <th>Total</th>        
    </tr>
    <?php
    $sub_total_final = 0;
    $precio_iva = 0;
    $total_final = 0;
    if (count($detalles) > 0) {
        foreach ($detalles as $detalle) :
            $sub_total_final = $sub_total_final + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['cantidad_orden']);
            $precio_iva = $precio_iva + (($detalle['OrdenComprasDetalle']['precio_producto']*$detalle['OrdenComprasDetalle']['iva_producto']) * $detalle['OrdenComprasDetalle']['cantidad_orden'] );
            $total_final = $total_final + ($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])) * $detalle['OrdenComprasDetalle']['cantidad_orden'];
    ?>
    <tr>
        <td><?php echo $detalle['Producto']['codigo_producto']; ?></td>
        <td><?php echo $detalle['Producto']['nombre_producto']; ?></td>
        <td><?php echo $detalle['Producto']['medida_producto']; ?></td>
        <td class="text-right"><?php echo $detalle['OrdenComprasDetalle']['cantidad_orden']; ?></td>
        <td class="text-right">$ <?php echo number_format($detalle['OrdenComprasDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td class="text-right"><?php echo ($detalle['OrdenComprasDetalle']['iva_producto']*100); ?> %</td>
        <td class="text-right">$ <?php echo number_format(($detalle['OrdenComprasDetalle']['precio_producto'] * ($detalle['OrdenComprasDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td class="text-right">$ <?php echo number_format(($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td class="text-right">$ <?php echo number_format(($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])) * $detalle['OrdenComprasDetalle']['cantidad_orden'], 2, ',', '.'); ?></td>
    </tr>
    <?php
        endforeach;
    }
    ?>
    <tr>
        <td colspan="8" class="text-right"><b>&nbsp; SubTotal >> </b></td>
        <td class="text-right"><b>$ <?php echo number_format($sub_total_final, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8" class="text-right"><b>&nbsp; IVA >> </b></td>
        <td class="text-right"><b>$ <?php echo number_format($precio_iva, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8" class="text-right"><b>&nbsp; RETEFUENTE  >> </b></td>
        <?php $rtefuente = $sub_total_final*($ordenes['0']['OrdenCompra']['rte_fuente']); ?>
        <td class="text-right"><b>$ <?php echo number_format($rtefuente, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8" class="text-right"><b>&nbsp; RETEICA   >> </b></td>
        <?php $rteica = ($sub_total_final*$ordenes['0']['OrdenCompra']['rte_ica'])/1000; ?>
        <td class="text-right"><b>$ <?php echo number_format($rteica, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8" class="text-right"><b>&nbsp; Total >> </b></td>
        <td class="text-right"><b>$ <?php echo number_format($sub_total_final+$precio_iva-$rtefuente-$rteica, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
</table>
<div class="text-center">
    <div class="text-center">
        <?php echo $this->Form->input($ordenes['0']['OrdenCompra']['id'], array('type' => 'hidden','value'=>'true', 'label' => false, 'value' => $ordenes['0']['OrdenCompra']['id'], 'class' => 'ck')); ?>
        <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning','onclick'=>'history.back();')); ?>
        <?php echo $this->Form->button('Aprobar Orden de Compra', array('type' => 'submit', 'class' => 'btn btn-info')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
