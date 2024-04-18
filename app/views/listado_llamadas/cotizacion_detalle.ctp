<?php

echo $this->Html->script(array('cotizacion/cotizacion_detalle'));

/*echo $this->Session->read('Cotizacion.cotizacion_id'); echo "<br>";    
echo $this->Session->read('User.sucursal_id'); echo "<br>";  
echo $this->Session->read('Pedido.tipo_pedido_id'); 
print_r($productos_sucursal);*/

$nombre_producto = "";
$num = count($productos);
$i = 1;
$mensaje_advertencia = null;
foreach ($productos as $value) {
    if(!empty($value['Producto']['mensaje_advertencia'])){
        $mensaje_advertencia = '"'.$value['Producto']['codigo_producto'].'":"'.$value['Producto']['mensaje_advertencia'].'",'.$mensaje_advertencia;
    }
    $nombre_producto.="'" . $value['Producto']['producto_completo'] ."'";
    if ($num > $i) {
        $nombre_producto.=",";
    }
    $i++;
}
?>
<script>

    $(function () {
        $("#div_mensaje_advertencia").hide();
        var availableTags = [<?php echo $nombre_producto; ?>];
        $("#CotizacionDetalleProductoId2").autocomplete({
            source: availableTags
        });
    });

    function mensajes_advertencia() {
        var mensajes_advertencia = {<?php echo $mensaje_advertencia; ?>};
        var producto = $('#CotizacionDetalleProductoId2').val();
        var codigo = producto.split(' ');
        if (typeof mensajes_advertencia[codigo[0]] === 'undefined') {
            $("#div_mensaje_advertencia").hide();
            document.getElementById('div_mensaje_advertencia').innerHTML = "";
        } else {
            $("#div_mensaje_advertencia").show();
            document.getElementById('div_mensaje_advertencia').innerHTML = "<b>" + mensajes_advertencia[codigo[0]] + "</b>";
            alert(mensajes_advertencia[codigo[0]]);
        }
    }
</script>
<h2>COTIZACIÓN DE PEDIDO <span style='color:red;'>#000<?php echo $this->Session->read('Cotizacion.cotizacion_id'); ?></span></h2>
<div>&nbsp;</div>
<?php echo $this->Form->create('CotizacionDetalle', array('url' => array('controller' => 'listadoLlamadas', 'action' => 'cotizacion_detalle'))); ?>

<table class="table-striped table-bordered table-condensed" align="center" id="add_producto"> 
    <tr>
        <td colspan="3" class="text-center"><h4>AGREGAR PRODUCTO A LA COTIZACIÓN</h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2', array('type' => 'text', 'label' => false, 'onchange'=>'mensajes_advertencia()', 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120')); ?><div id="div_mensaje_advertencia" style="color: #e32;"></div></td>
        <td><?php echo $this->Form->input('cantidad_pedido', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5')); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs')); ?></td>
    </tr>
    <tr>
        <td colspan="3"  class="text-center"><b>Observaciones:</b> <br> <?php  echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite sus observaciones generales de la cotización.')); ?></td>
    </tr>
    <tr>
        <td colspan="3" class="text-center">
            <?php
            $llamada_id = "'".base64_encode($this->Session->read('Cotizacion.listado_llamada_id'))."'";
            ?>
            <?php echo $this->Form->button('Cancelar Cotización', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cancelar_cotizacion(' . $this->Session->read('Cotizacion.cotizacion_id') . ','.$llamada_id.');')); ?>
            <?php echo $this->Form->button('Terminar Cotización', array('type' => 'button', 'class' => 'btn btn-primary  btn-xs', 'onclick' => 'terminar_cotizacion(' . $this->Session->read('Cotizacion.cotizacion_id') . ','.$llamada_id.');')); ?>            
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table table-hover">
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
        <th>Quitar</th>
    </tr>
    <?php
    $total_final = 0;
    if (count($detalles) > 0) {
        foreach ($detalles as $detalle) :
            $total_final = $total_final + ($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])) * $detalle['CotizacionDetalle']['cantidad_pedido'];
            ?>
    <tr>
        <td><?php echo $html->image('productos/'.$detalle['Producto']['codigo_producto'].'.jpg', array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $detalle['Producto']['nombre_producto'])) ?></td>
<!--        <td><?php //echo $detalle['Producto']['codigo_producto']; ?></td>-->
        <td><?php echo $detalle['Producto']['nombre_producto']; ?></td>
        <td><?php echo $detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td>$ <?php echo number_format($detalle['CotizacionDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td><?php echo ($detalle['CotizacionDetalle']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['CotizacionDetalle']['cantidad_pedido'],0,",","."); ?></td>
        <td>$ <?php echo number_format(($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])) * $detalle['CotizacionDetalle']['cantidad_pedido'], 2, ',', '.'); ?></td>
        <td><?php echo $this->Form->button('Modificar', array('type' => 'button', 'class' => 'btn btn-info  btn-xs', 'onclick' => "modificar('".$detalle['Producto']['producto_completo']."','".$detalle['CotizacionDetalle']['cantidad_pedido']."','" . $detalle['CotizacionDetalle']['id'] . "')")); ?>
            <?php echo $this->Form->button('Quitar', array('type' => 'button', 'class' => 'btn btn-warning  btn-xs', 'onclick' => 'quitar(' . $detalle['CotizacionDetalle']['id'] . ')')); ?>
            <?php echo $this->Form->create('CotizacionDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>
            <div align="center" id="edit_producto_<?php echo $detalle['CotizacionDetalle']['id']; ?>" style="display: none;"><br>
                <?php echo $this->Form->input('cantidad_pedido_edit_'.$detalle['CotizacionDetalle']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '5', 'maxlength' => '5')); ?>
                <?php echo $this->Form->input('id_edit_'.$detalle['CotizacionDetalle']['id'], array('type' => 'hidden')); ?>
                <?php echo $this->Form->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cerrar_actualizar('.$detalle['CotizacionDetalle']['id'].');')); ?>
                <?php echo $this->Form->button('Actualizar', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => 'actualizar_cotizacion('.$detalle['CotizacionDetalle']['id'].');')); ?>            
            </div>
            <?php echo $this->Form->end(); ?>

        </td>
    </tr>
            <?php
        endforeach;
    }else {
        ?>
    <tr>
        <td colspan="9" class="text-center">No ha seleccionado ningún articulo para su pedido</td>
    </tr>
        <?php
    }
    ?><tr>
        <td colspan="7" class="text-center"><b>&nbsp; Total >> </b></td>
        <td><b>$ <?php echo number_format($total_final, 2, ',', '.');?></b></td>
        <td></td>
    </tr>
</table>

