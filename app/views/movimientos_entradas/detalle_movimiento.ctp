<?php

echo $this->Html->script(array('movimientos_entradas/movimientos_entradas_detalle'));
?>
<?php echo $this->Form->create('MovimientosEntradasDetalle', array('url' => array('controller' => 'movimientos_entradas', 'action' => 'detalle_movimiento'))); ?>
<div><b>Movimiento de Entrada:</b> <span style="color: #0a0; font-weight: bold; ">#000<?php echo $movimiento['MovimientosEntrada']['id']; ?></span> <?php echo $this->Html->link(__('[ Modificar ]', true), array('action' => 'edit', $movimiento['MovimientosEntrada']['id']), array('escape' => false)); ?></div>
    <!-- <b>Categoría:</b> <?php //echo  $movimiento['TipoCategoria']['tipo_categoria_descripcion']; ?>  </div> -->
<div><b>Subtotal:</b> $ <?php echo number_format($movimiento['MovimientosEntrada']['factura_subtotal'],2,",","."); ?>  </div>
<div><b>IVA:</b> $ <?php echo number_format($movimiento['MovimientosEntrada']['factura_iva'],2,",","."); ?>  </div>
<div><b>Total Factura:</b> $ <?php echo number_format($movimiento['MovimientosEntrada']['factura_total'],2,",","."); ?>  </div>
<div><b>Tipo Movimiento:</b> <?php echo $movimiento['TipoMovimiento']['nombre_tipo_movimiento']; ?>  </div>
<?php if(!empty($movimiento['Proveedore']['nit_proveedor'])){ ?>
<div><b>Proveedor:</b> <?php echo $movimiento['Proveedore']['nombre_proveedor']; ?>  </div>
<div><b>Nit:</b> <?php echo $movimiento['Proveedore']['nit_proveedor']; ?>  </div>
<?php }else{  ?>
<div><b>Empresa:</b> <?php echo $movimiento['Empresa']['nombre_empresa']; ?>  - 
    <b>Nit:</b> <?php echo $movimiento['Empresa']['nit_empresa']; ?>  </div>
<?php } ?>
<div><b>Bodega</b>: <?php echo $movimiento['Bodega']['nombre_bodega']; ?>  </div>
<div><b>Orden de Compra</b>: <span style="color: blue; font-weight: bold; "><?php echo (!empty($movimiento['MovimientosEntrada']['orden_compra_id']))?'#000'.$movimiento['MovimientosEntrada']['orden_compra_id']:''; ?> </span> </div>
<div>&nbsp;</div>

<table class="table-striped table-bordered table-condensed" align="center" id="add_producto"> 
    <tr>
        <td colspan="4" class="text-center"><h4>AGREGAR PRODUCTO AL MOVIMIENTO DE ENTRADA <span style="color: #0a0; font-weight: bold; ">#000<?php echo $this->Session->read('MovimientosEntrada.movimiento_id'); ?></span></h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2', array('type' => 'text', 'label' => false, 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120','onchange'=>'ultimo_precio()')); ?></td>
        <td><?php echo $this->Form->input('precio_producto', array('type' => 'text', 'label' => false, 'placeholder' => 'Precio', 'size' => '10', 'maxlength' => '20','title'=>'Utilice el punto (0.00) como separador decimal.')); ?></td>
        <td><?php echo $this->Form->input('cantidad_entrada', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5')); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs')); ?></td>
    </tr>
    <tr>
        <td colspan="4" class="text-center">
            <?php
                $mensaje_ordenes = null;
                $total_final = 0;
                foreach ($movimientosEntradas as $movimientosEntrada) :
                    $total_final = $total_final + ($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'] * $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada']);    
                endforeach;
                $saldo = $movimiento['MovimientosEntrada']['factura_subtotal'] - $total_final;
              
            if (count($movimientosEntradas) > 0) {
                /*echo round($total_final);
                echo "-";
                echo floor($total_final);
                echo "-";
                echo ceil($total_final);*/
                if(ceil($total_final) == ceil($movimiento['MovimientosEntrada']['factura_subtotal']) || floor($total_final) == ceil($movimiento['MovimientosEntrada']['factura_subtotal'])){
                    $disabled = '';        
                }else{
                    $disabled = 'disabled';   
                    if(!empty($movimiento['MovimientosEntrada']['orden_compra_id'])){
                        $mensaje_ordenes = "<br>Si no puede terminar el movimiento de entrada por ingreso de ordenes parciales, <br>debe modificar los valores del encabezado del movimiento.". $this->Html->link(__('[ Modificar ]', true), array('action' => 'edit', $movimiento['MovimientosEntrada']['id']), array('escape' => false));
                    }
                }
            }else{
                $disabled = 'disabled';
            }
            ?>
            <?php // echo $this->Form->button('Cancelar Entrada', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cancelar_entrada(' . $this->Session->read('MovimientosEntrada.movimiento_id') . ');')); ?>
            <?php echo $this->Form->button('Terminar Entrada', array('type' => 'button', 'class' => 'btn btn-primary  btn-xs', 'disabled'=>$disabled, 'onclick' => 'terminar_entrada(' . $this->Session->read('MovimientosEntrada.movimiento_id') . ');')); ?>            
        </td>
    </tr>
    <tr>
        <td colspan="1"><div style="color: #e32;"> Faltan por registrar <b>$ <?php echo number_format($saldo,2,",","."); echo $mensaje_ordenes; ?></b></div></td>
        <td colspan="3"><div> Total registrado; <b>$ <?php echo number_format($total_final,2,",","."); ?></b></div></td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table table-condensed table-striped table-hover table-bordered">
    <tr>
        <th>Producto</th>
        <th class="text-center">Precio</th>
        <th class="text-center">Cantidad</th>
        <th class="text-center">Total</th>
        <th class="text-center">Quitar</th>
    </tr>
    <?php
     //print_r($ordenCompras);
     if (count($ordenCompras) > 0) {
         // print_r($ordenCompras);
        foreach ($ordenCompras as $ordenCompra) : 
            $mensaje_parcial = null;
            $sugerir = true;
            $cantidad = $ordenCompra['OrdenComprasDetalle']['cantidad_orden'] - $ordenCompra['OrdenComprasDetalle']['cantidad_orden_parcial'];
	    if($cantidad == '0'){
	    	$sugerir = false;
//		echo '0';
	    }

            foreach ($movimientosEntradas as $movimientosEntrada) :
                if($ordenCompra['Producto']['id'] == $movimientosEntrada['Producto']['id']){
                    $sugerir = false;
                    // Si la cantidad parcial es 0, no sugerir. Si tiene valor, sugerir producto
                    if($ordenCompra['OrdenComprasDetalle']['cantidad_orden_parcial'] == '0'){
                        $sugerir = false;
				//echo '1';
                    }else{
                        $sugerir = true;
                        $cantidad = $ordenCompra['OrdenComprasDetalle']['cantidad_orden'] - $ordenCompra['OrdenComprasDetalle']['cantidad_orden_parcial'];
			if($cantidad > 0){
                        	$mensaje_parcial = "[ Cantidad pacial. Se han agregado ".$ordenCompra['OrdenComprasDetalle']['cantidad_orden_parcial']. " de ".$ordenCompra['OrdenComprasDetalle']['cantidad_orden'].". Falta ".$cantidad." para completar. ]";
	                        if($cantidad == '0'){
	                             $sugerir = false;
					//echo '2';
	                        }
			}else{
	                        $sugerir = false;
				//echo '3';
			}
                    }
                }
            endforeach;
            if($sugerir){
    ?>
    <tr>
        <td><?php echo $ordenCompra['Producto']['codigo_producto'].' - '. $ordenCompra['Producto']['nombre_producto']; ?> <span style="color:red;"><b><?php echo $mensaje_parcial; ?></b></span></td>
        <td class="text-right"><?php echo $this->Form->input('precio_producto_'.$ordenCompra['Producto']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Precio', 'size' => '7', 'maxlength' => '5', 'onchange'=>'', 'value'=>$ordenCompra['OrdenComprasDetalle']['precio_producto'])); ?></td>
        <td class="text-right"><?php echo $this->Form->input('cantidad_orden_'.$ordenCompra['Producto']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5', 'onchange'=>'', 'value'=>$cantidad)); ?></td>
        <td class="text-right">$ <?php echo number_format($ordenCompra['OrdenComprasDetalle']['precio_producto'] * $ordenCompra['OrdenComprasDetalle']['cantidad_orden'],2,",","."); ?> </td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => "agregar_movimiento(document.getElementById('cantidad_orden_".$ordenCompra['Producto']['id']."').value,document.getElementById('precio_producto_".$ordenCompra['Producto']['id']."').value,'".$ordenCompra['Producto']['id']."','".$ordenCompra['OrdenComprasDetalle']['orden_compra_id']."','".$ordenCompra['OrdenComprasDetalle']['cantidad_orden']."');")); ?></td>
    </tr>
    <?php
            }
        endforeach;
     }
     ?>
    <?php
    if (count($movimientosEntradas) > 0) {
        foreach ($movimientosEntradas as $movimientosEntrada) :
            ?>
    <tr>
        <td><?php echo $movimientosEntrada['Producto']['codigo_producto'].' - '. $movimientosEntrada['Producto']['nombre_producto']; ?></td>
        <td class="text-right">$<?php echo number_format($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'],2,",","."); ?></td>
        <td class="text-right"><?php echo $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada']; ?></td>
        <td class="text-right">$ <?php echo number_format($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'] * $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada'],2,",","."); ?> </td>
        <td class="text-center"><?php echo $this->Form->button('Quitar', array('type' => 'button', 'class' => 'btn btn-warning  btn-xs', 'onclick' => 'quitar_entrada(' . $movimientosEntrada['MovimientosEntradasDetalle']['id'] . ','.$movimientosEntrada['Producto']['id'].')')); ?></td>
    </tr>
    <?php
            // echo $movimientosEntrada['MovimientosEntradasDetalle']['producto_id'];
        endforeach;
    }
?>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td  class="text-center"><b>Total:</b></td>
        <td  class="text-right"><b>$<?php echo number_format($total_final,2,",","."); ?></b></td>
        <td>&nbsp;</td>
    </tr>
</table>

<?php
$nombre_producto = "";
$num = count($productos);
$i = 1;
foreach ($productos as $value) {
    // $nombre_producto.="'" . $value['Producto']['producto_completo'] . "'";
    $nombre_producto.="'" . $value['Producto']['codigo_producto'].' | '.substr($value['Producto']['nombre_producto'],0,30) . "'";
    if ($num > $i) {
        $nombre_producto.=",";
    }
    $i++;
}
?>
<script>
    $(function () {
        var availableTags = [<?php echo $nombre_producto; ?>];
        $("#MovimientosEntradasDetalleProductoId2").autocomplete({
            source: availableTags
        });
    });
</script>