<?php

echo $this->Html->script(array('orden_compra/orden_compra_detalle'));

$nombre_producto = "";
$num = count($productos);
$i = 1;
// $mensaje_advertencia = null;
// $multiplo = null;
foreach ($productos as $value) {
//    if(!empty($value['Producto']['mensaje_advertencia'])){
//        $mensaje_advertencia = '"'.$value['Producto']['codigo_producto'].'":"'.$value['Producto']['mensaje_advertencia'].'",'.$mensaje_advertencia;
//    }
//    if(!empty($value['Producto']['multiplo'])){
//        $multiplo = '"'.$value['Producto']['codigo_producto'].'":"'.$value['Producto']['multiplo'].'",'.$multiplo;
//    }
    // $nombre_producto.="'" . $value['Producto']['producto_completo'] . "'";
    $nombre_producto.="'" . $value['Producto']['codigo_producto'].' | '.substr($value['Producto']['nombre_producto'],0,30) . "'";
    // $nombre_producto.="'" . $value['Producto']['codigo_producto']. "'";
    if ($num > $i) {
        $nombre_producto.=",";
    }
    $i++;
}
?>
<script>
    $(function () {
        var availableTags = [<?php echo $nombre_producto; ?>];
        $("#OrdenComprasDetalleProductoId2").autocomplete({
            source: availableTags
        });
    });
</script>
<h2>ORDEN DE COMPRA <span style='color:blue;'><b>#000<?php echo $this->Session->read('OrdenCompra.id'); ?></b></span></h2>
<?php echo $this->Session->read('OrdenCompra.encabezado'); ?>
<?php echo $this->Form->create('OrdenComprasDetalle', array('url' => array('controller' => 'ordenCompras', 'action' => 'detalle_compra'))); ?>

<table class="table-striped table-bordered table-condensed" align="center" id="add_producto"> 
    <tr>
        <td colspan="4" class="text-center"><h4>AGREGAR PRODUCTO A LA ORDEN</h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2', array('type' => 'text', 'label' => false, 'onchange'=>'ultimo_precio()', 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120')); ?></td>
        <td><?php echo $this->Form->input('precio_producto', array('type' => 'text', 'label' => false, 'placeholder' => 'Precio', 'size' => '7', 'maxlength' => '8', 'onchange'=>'')); ?></td>
        <td><?php echo $this->Form->input('cantidad_orden', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5', 'onchange'=>'')); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs')); ?></td>
    </tr>
    <tr>
        <td colspan="4"  class="text-center"><b>Observaciones:</b> <br> <?php echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite sus observaciones para el producto.')); ?></td>
    </tr>
    <tr>
        <td colspan="4" class="text-center">
            <?php
            if (count($detalles) > 0) {
                $disabled = '';    
            }else{
                $disabled = 'disabled';
            }
            ?>
            <?php echo $this->Form->button('Cancelar Orden', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cancelar_orden(' . $this->Session->read('OrdenCompra.id') . ');')); ?>
            <?php echo $this->Form->button('Terminar Orden', array('type' => 'button', 'class' => 'btn btn-primary  btn-xs', 'disabled'=>$disabled, 'onclick' => 'terminar_orden(' . $this->Session->read('OrdenCompra.id') . ');')); ?>            
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table table-hover">
<!--    <tr>
        <td colspan="9"><b>Formula para sugerido de compra: (<span style="color: red;">SALDO FISICO</span> + <span style="color: green;">PEDIDOS ESTADO APROBADO</span> + <span style="color: orange;">STOCK MINIMO</span>) - <span style="color: blue;">ORDEN DE COMPRA (APROBADAS - ENTRADA PARCIAL)</span></b></td> 
    </tr>-->
    <tr>
        <th>C&oacute;digo</th>
        <th colspan="2">Descripci&oacute;n</th>
        <th>Saldo Fisico</th>
        <th>Pedidos<br>Aprobados</th>
        <th>Ordenes<br>Compra</th>
        <th>Stock Minimo</th>
        <th>Sugerido<br>Compra</th>
        <th>Último Precio</th>
        <th>Agregar</th>
    </tr>

    <?php
    // print_r($detalles_aprobados);
    foreach ($inventarios as $inventario):
        $sugerir = true;
        foreach ($detalles as $detalle) :
            if($inventario['Producto']['id'] == $detalle['Producto']['id']){
                $sugerir = false;
            }
        endforeach;
        //$enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_aprobado'];
        $enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_despachado'];
        if($enBodega < 0 && $sugerir == true){
            $cantidad_aprobada = 0;
            foreach ($productos_aprobados as $producto):
                if($inventario['Producto']['id'] == $producto[0]['producto_id']){
                    $cantidad_aprobada = $producto[0]['sum'];
                }
            endforeach;
            
            $cantidad_ordenada = 0;
            foreach ($detalles_aprobados as $orden_compra):
                if($inventario['Producto']['id'] == $orden_compra['OrdenComprasDetalle']['producto_id']){
                    $cantidad_ordenada = $cantidad_ordenada + $orden_compra['OrdenComprasDetalle']['cantidad_orden'] - $orden_compra['OrdenComprasDetalle']['cantidad_orden_parcial'];
                }
            endforeach;
            
            
           // FORMULA DE SUGERIDO DE COMPRA
            $stock = 0; 
            // Verificar el stock minimo
            if(($enBodega + $cantidad_ordenada) < abs($inventario['Producto']['stock_minimo'])){
                $stock = abs($inventario['Producto']['stock_minimo']) - ($enBodega + $cantidad_ordenada);
            }

            //  Si saldo fisico + cantidad ordenada es menor a los cantidad aprobada en los pedidos.
            if(($enBodega + $cantidad_ordenada) < $cantidad_aprobada){
                if($enBodega > 0){
                    $sugerido_compra = $cantidad_aprobada - ($enBodega + $cantidad_ordenada) + $stock;
                    $procedimiento = '1. (Saldo Fisico + Cantidad Ordenada < Pedidos Aprobados) = Pedidos Aprobados - (Saldo Fisico + Cantidad Ordenada) + Faltante Stock Minimo';
                }else{
                        if($enBodega < 0){
                            $sugerido_compra = $cantidad_aprobada + (abs($enBodega) - $cantidad_ordenada) + abs($inventario['Producto']['stock_minimo']);
                            $procedimiento = 'Si saldo fisico es negativo, (Pedidos Aprobados + Saldo Fisico Positivo - Cantidad Ordenada) + Stock Minimo';  
                        }else{
                            $sugerido_compra = $cantidad_aprobada - ($enBodega + $cantidad_ordenada) + $stock;
                            $procedimiento = 'Cantidad Aprobada - (Saldo fisico + Cantidad Ordenada) + Faltante Stock Minimo';
                        }
                }
            }
            // Si saldo fisico + cantidad ordenada es mayor igual a la cantidad aprobado
            if( ($enBodega + $cantidad_ordenada) >= $cantidad_aprobada){
                if(($enBodega + $cantidad_ordenada) < abs($inventario['Producto']['stock_minimo'])){
                    $sugerido_compra = $stock;
                    $procedimiento = '(Saldo Fisico + Cantidad Ordenada >= Pedidos Aprobados) = Stock Minimo - (Saldo Fisico + Cantidad Ordenada)';
                }else{
                   $sugerido_compra = 0; 
                   $procedimiento = 'Saldo Fisico > Pedidos Aprobados y mayor a Stock Minimo = Cero';
                }
            }
            // FIN FORMULA SUGERIDO DE COMPRA
    

            ?>
    <tr class="altrow" title="<?php echo $procedimiento; ?>">
        <td><?php echo $inventario['Producto']['codigo_producto']; ?></td>
        <td colspan="2" title="Proveedor: <?php echo $inventario['Proveedore']['nombre_proveedor']; ?>"><?php echo $inventario['Producto']['nombre_producto']; ?></td>
        <td class="text-center" style="color: red; font-weight: bold;"><?php echo $enBodega; ?></td>        
        <td class="text-center" style="color: green; font-weight: bold;"><?php echo $cantidad_aprobada; ?></td>
        <td class="text-center" style="color: blue; font-weight: bold;"><?php echo $cantidad_ordenada; ?></td>
        <td class="text-center" style="color: orange; font-weight: bold;"><?php echo $inventario['Producto']['stock_minimo']; ?></td>
        <td><?php echo $this->Form->input('cantidad_orden_'.$inventario['Producto']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5', 'onchange'=>'', 'value'=>$sugerido_compra)); ?></td>
        <td><?php echo $this->Form->input('precio_producto_'.$inventario['Producto']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Precio', 'size' => '7', 'maxlength' => '5', 'onchange'=>'', 'value'=>$inventario['Producto']['precio_producto'])); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => "agregar_sugerido(document.getElementById('cantidad_orden_".$inventario['Producto']['id']."').value,document.getElementById('precio_producto_".$inventario['Producto']['id']."').value,'".$inventario['Producto']['id']."');")); ?></td>
    </tr>
    <?php
        }
    endforeach;
    ?>
    <tr>
        <td colspan="10"></td>
    </tr>
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
        <th>Quitar</th>
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
        <td><?php echo $this->Form->button('Quitar', array('type' => 'button', 'class' => 'btn btn-warning  btn-xs', 'onclick' => 'quitar_producto(' . $detalle['OrdenComprasDetalle']['id'] . ')')); ?></td>
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
        <td colspan="8" class="text-right"><b>&nbsp; Total >> </b></td>
        <td class="text-right"><b>$ <?php echo number_format($total_final, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
</table>