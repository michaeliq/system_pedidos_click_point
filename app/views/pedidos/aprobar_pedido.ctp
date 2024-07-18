<?php

echo $this->Html->script(array('pedidos/pedidos_detalle'));
// echo $this->Session->read('Pedido.pedido_id');
$nombre_producto = "";
$num = count($productos);
$i = 1;
foreach ($productos as $value) {
    $nombre_producto.="'" . $value['Producto']['producto_completo'].' '.$value['Producto']['marca_producto'] . "'";
    if ($num > $i) {
        $nombre_producto.=",";
    }
    $i++;
}
?>

<script>
    $(function () {
        var availableTags = [<?php echo $nombre_producto; ?>];
        $("#PedidosDetalleProductoId2").autocomplete({
            source: availableTags
        });
    });
</script> 
<h2>ORDEN DE PEDIDO <span style='color:red;'>#000<?php echo $id; ?></span></h2>
<table class="table table-hover">
    <tr>
        <th>
<!--        <th>C&oacute;digo</th>-->
            Descripci&oacute;n</th>
        <th>Categor&iacute;a <?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $categorias, 'empty' => '', 'label' => false, 'default' => '1'));                                  ?></th>
        <th>Precio</th>
        <th>IVA</th>
        <th>Valor Producto</th>
        <th>Cant.</th>
        <th>Total</th>        
        <th>Quitar</th>
    </tr>
    <?php
    $total_final = 0;
        foreach ($detalles as $detalle) :
            $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];
            ?>
    <tr>
        <td><?php echo $html->image('productos/'.$detalle['Producto']['codigo_producto'].'.jpg', array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $detalle['Producto']['nombre_producto'])) ?> 
<!--        <td><?php //echo $detalle['Producto']['codigo_producto']; ?></td>-->



            <?php echo $detalle['Producto']['nombre_producto'].' '.$detalle['Producto']['marca_producto']; ?></td>
        <td><?php echo $detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td>$ <?php echo number_format($detalle['PedidosDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td><?php echo ($detalle['PedidosDetalle']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['PedidosDetalle']['cantidad_pedido'],0,",","."); ?></td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.'); ?></td>
        <td align="center"><?php echo $this->Form->button('Modificar', array('type' => 'button', 'class' => 'btn btn-info  btn-xs', 'onclick' => "modificar2('".$detalle['Producto']['codigo_producto']."','".$detalle['PedidosDetalle']['cantidad_pedido']."','" . $detalle['PedidosDetalle']['id'] . "')")); ?>
            <?php echo $this->Form->button('Quitar', array('type' => 'button', 'class' => 'btn btn-warning  btn-xs', 'onclick' => 'quitar_2(' . $detalle['PedidosDetalle']['id'] . ')')); ?><br>
            <?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'aprobar_pedido/'.$id))); ?>
            <div align="center" id="edit_producto_<?php echo $detalle['PedidosDetalle']['id']; ?>" style="display: none;"><br>
                <?php echo $this->Form->input('cantidad_pedido_edit_'.$detalle['PedidosDetalle']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '5', 'maxlength' => '5')); ?>
                <?php echo $this->Form->input('id_edit_'.$detalle['PedidosDetalle']['id'], array('type' => 'hidden')); ?>
                <?php echo $this->Form->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cerrar_actualizar2('.$detalle['PedidosDetalle']['id'].');')); ?>
                <?php echo $this->Form->button('Actualizar', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => 'actualizar_pedido_3('.$detalle['PedidosDetalle']['id'].');')); ?>            
            </div>
            <?php echo $this->Form->end(); ?>
        </td>
    </tr>
        <?php
    endforeach;
    ?>
    <tr>
        <td colspan="6" class="text-center"><b>&nbsp; Total >> </b></td>
        <td><b>$ <?php echo number_format($total_final, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
</table>
<?php /*echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>
<table class="table-striped table-bordered table-condensed" align="center" id="edit_producto" style="display: none;"> 
    <tr>
        <td colspan="3" class="text-center"><h4>EDITAR PRODUCTO DEL PEDIDO</h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2_edit', array('type' => 'text', 'label' => false, 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120', 'disabled'=>'disabled')); ?></td>
        <td><?php echo $this->Form->input('cantidad_pedido_edit', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5')); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <?php echo $this->Form->input('id_edit', array('type' => 'hidden')); ?>
            <?php echo $this->Form->button('Cerrar', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cerrar_actualizar();')); ?>
            <?php echo $this->Form->button('Actualizar Pedido', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => 'actualizar_pedido_2();')); ?>            
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); */?>

<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido_aprobacion'))); ?>
<table class="table-striped table-bordered table-condensed" align="center" id="add_producto"> 
    <tr>
        <td colspan="3" class="text-center"><h4>AGREGAR PRODUCTO AL PEDIDO</h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2', array('type' => 'text', 'label' => false, 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120')); ?></td>
        <td><?php echo $this->Form->input('cantidad_pedido', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '5', 'maxlength' => '4')); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs')); ?></td>
        <!-- <td><?php echo $this->Form->input("fecha_expiracion", array('type' => 'text', 'label' => false, 'class' => 'fecha_vencimiento', "placeholder" => "Ingresa una fecha valida"))?></td>
        <td><?php echo $this->Form->input("lote", array('type' => 'text', 'label' => false)) ?></td> -->
    </tr>
    <tr>
        <td colspan="3" class="text-center">
            <?php echo $this->Form->input('sucursal_id', array('type' => 'hidden','value'=>$detalles['0']['Sucursale']['id'])); ?>
            <?php echo $this->Form->input('id_pedido', array('type' => 'hidden','value'=>$id)); ?>
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning  btn-sm', 'onclick' => 'history.back();')); ?>            
            <?php echo $this->Form->button('Cancelar Pedido', array('type' => 'button', 'class' => 'btn btn-danger  btn-sm', 'onclick' => 'cancelar_pedido_2(' . $id . ');')); ?>
            <?php echo $this->Form->button('Aprobar Pedido', array('type' => 'button', 'class' => 'btn btn-primary  btn-sm', 'onclick' => 'aprobar_pedido_ok(' . $id . ');')); ?>            
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>