<h2><?php  __('Movimientos Entrada');?></h2>
<?php echo $this->Form->create('MovimientosEntradasDetalle', array('url' => array('controller' => 'movimientos_entradas', 'action' => 'detalle_movimiento'))); ?>
<div><b>Movimiento de Entrada:</b> <span style="color: #0a0; font-weight: bold; ">#000<?php echo $movimiento['MovimientosEntrada']['id']; ?></span>  - 
    <!-- <b>Categoría:</b> <?php // echo  $movimiento['TipoCategoria']['tipo_categoria_descripcion']; ?>  </div> -->
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
    <div><b>Bodega:</b> <?php echo $movimiento['Bodega']['nombre_bodega']; ?>  </div>
    <div><b>Fecha Movimiento:</b> <?php echo $movimiento['MovimientosEntrada']['fecha_movimiento']; ?></div>	
    <div><b>Fecha y hora de Registro:</b> <?php echo $movimiento['MovimientosEntrada']['fecha_registro_movimiento']; ?></div>	
    <div><b>Orden de Compra</b>: <span style="color: blue; font-weight: bold; "><?php echo (!empty($movimiento['MovimientosEntrada']['orden_compra_id']))?'#000'.$movimiento['MovimientosEntrada']['orden_compra_id']:''; ?> </span> </div>
    <div>&nbsp;</div>
</div>
<div><?php echo $this->Form->button('Regresar', array('type' => 'button', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?></div>
<div>&nbsp;</div>	
<table class="table table-condensed table-striped table-hover table-bordered">
    <tr>
        <th class="text-center">Producto</th>
        <th class="text-center">Precio</th>
        <th class="text-center">Cantidad</th>
        <th class="text-center">Total</th>
        <th class="text-center">Usuario Registro</th>
        <th class="text-center">Fecha Registro</th>
    </tr>
<?php
    $total_final = 0;
    // print_r($movimientosEntradas);
    if (count($movimientosEntradas) > 0) {
        foreach ($movimientosEntradas as $movimientosEntrada) :
            $total_final = $total_final + ($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'] * $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada']);
            ?>
    <tr>
        <td><?php echo $movimientosEntrada['Producto']['producto_completo']; ?></td>
        <td class="text-right">$<?php echo number_format($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'],2,",","."); ?></td>
        <td class="text-right"><?php echo $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada']; ?></td>
        <td class="text-right">$<?php echo number_format(($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'] * $movimientosEntrada['MovimientosEntradasDetalle']['cantidad_entrada']),2,",","."); ?></td>
        <td class="text-center"><?php echo $movimientosEntrada['User']['nombres_persona']; ?></td>
        <td class="text-center"><?php echo $movimientosEntrada['MovimientosEntradasDetalle']['fecha_registro_entrada']; ?></td>
    </tr>
    <?php
            // echo $movimientosEntrada['MovimientosEntradasDetalle']['producto_id'];
        endforeach;
    }
?>
    <tr>
        <td colspan="2"></td>
        <td><b>Total:</b></td>
        <td class="text-right"><b>$ <?php echo number_format($total_final,2,",",".");?></b></td>
        <td colspan="2"></td>
    </tr>
</table>
<div><?php echo $this->Form->button('Regresar', array('type' => 'button', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?></div>