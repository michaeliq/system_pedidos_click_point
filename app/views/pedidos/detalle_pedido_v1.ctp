<?php

echo $this->Html->script(array('pedidos/pedidos_detalle'));
/*
echo $this->Session->read('Pedido.pedido_id'); echo "<br>";    
echo $this->Session->read('User.sucursal_id'); echo "<br>";  
echo $this->Session->read('Pedido.tipo_pedido_id'); 
print_r($productos_sucursal);*/

$nombre_producto = "";
$num = count($productos);
$i = 1;
$mensaje_advertencia = null;
$multiplo = null;
foreach ($productos as $value) {
    if(!empty($value['Producto']['mensaje_advertencia'])){
        $mensaje_advertencia = '"'.$value['Producto']['codigo_producto'].'":"'.$value['Producto']['mensaje_advertencia'].'",'.$mensaje_advertencia;
    }
    if(!empty($value['Producto']['multiplo'])){
        $multiplo = '"'.$value['Producto']['codigo_producto'].'":"'.$value['Producto']['multiplo'].'",'.$multiplo;
    }
    $nombre_producto.="'" . $value['Producto']['producto_completo'] .' '.$value['Producto']['marca_producto'] . "'";
    if ($num > $i) {
        $nombre_producto.=",";
    }
    $i++;
}
//05032018
$categorias = null;
foreach ($tipo_categorias as $key => $tipo_categoria) {
    $categorias = $tipo_categoria.', '.$categorias;
}
?>
<script>
    $(function () {
        var availableTags = [<?php echo $nombre_producto; ?>];
        $("#PedidosDetalleProductoId2").autocomplete({
            source: availableTags
        });
    });

    function mensajes_advertencia() {
        var mensajes_advertencia = {<?php echo $mensaje_advertencia; ?>};
        var producto = $('#PedidosDetalleProductoId2').val();
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

    function verificar_cantidad() {
        var multiplo = {<?php echo $multiplo; ?>};
        var cantidad = $('#PedidosDetalleCantidadPedido').val();
        var producto = $('#PedidosDetalleProductoId2').val();
        var codigo = producto.split(' ');
        if (typeof multiplo[codigo[0]] === 'undefined') {
            $("#div_mensaje_cantidad").hide();
            document.getElementById('div_mensaje_cantidad').innerHTML = "";
        } else {
            var resto = cantidad % multiplo[codigo[0]];
            if (resto == 0) {
                $("#div_mensaje_cantidad").hide();
                document.getElementById('div_mensaje_cantidad').innerHTML = "";
            } else {
                $("#div_mensaje_cantidad").show();
                document.getElementById('div_mensaje_cantidad').innerHTML = "<b>La cantidad del producto debe ser multiplo de " + multiplo[codigo[0]] + "</b>";
                // alert(resto);
                $('#PedidosDetalleCantidadPedido').val('');
            }
        }
    }
</script>
<h2>ORDEN DE PEDIDO <span style='color:red;'>#000<?php echo $this->Session->read('Pedido.pedido_id'); ?></span></h2>
<div><b>Empresa:</b> <?php echo $presupuestos_info['0']['Empresa']['nombre_empresa']; ?> - <b>Contrato:</b> <?php echo $presupuestos_info['0']['Empresa']['contrato_empresa']; ?></div>
<div><b>Sucursal:</b> <?php echo $presupuestos_info['0']['Sucursale']['nombre_sucursal']; ?> - <b>Auxiliares:</b> <?php echo $presupuestos_info['0']['Sucursale']['numero_auxiliares']; ?></div>
<div><b>Regional:</b> <?php echo $presupuestos_info['0']['Sucursale']['v_regional_sucursal']; ?></div>
<div><b>Tipo de Pedido:</b> <?php echo $plantilla['0']['TipoPedido']['nombre_tipo_pedido']; ?></div>
<div><b>Plantilla:</b> <?php echo $plantilla['0']['Plantilla']['nombre_plantilla']; ?>(<?php echo $plantilla['0']['Plantilla']['id']; ?>)</div>
<div><b>Presupuesto Asignado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'],0,",",".");  ?></div>
<?php if ($parametro_presupuesto_iva){ // BBVA ?>
<div><b>Presupuesto Utilizado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado']+$presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_iva'],2,",",".");  ?> (CON IVA)</div>    
<?php }else{ ?>
<div><b>Presupuesto Utilizado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",",".");  ?> (Sin IVA)</div>    
<?php } ?>

<div><b>Categoria: </b> <?php echo $categorias; //05032018 ?></div>
<?php if(!empty($this->Session->read('Pedido.fecha_entrega_1'))){ ?>
<div><b>Fechas de Entrega:</b> Desde el <b><?php echo $this->Session->read('Pedido.fecha_entrega_1');?></b> hasta el <b><?php echo $this->Session->read('Pedido.fecha_entrega_2');  ?></b></div>
<?php } ?>

<?php if ($parametro_presupuesto_iva){ // BBVA ?>
<div style="color: <?php echo $presupuesto_disponible;?> "><b>Presupuesto Disponible: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'] - ($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado']+$presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_iva']),2,",",".");  ?></div>
    <?php }else{ ?>
<div style="color: <?php echo $presupuesto_disponible;?> "><b>Presupuesto Disponible: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'] - $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",",".");  ?></div>
<?php } ?>


<?php if(count($detalles)>0){ ?>
<div  title="Cambiar Observaciones"> <b>Observaciones: </b> <?php echo  $detalles[0]['Pedido']['observaciones'];?>  <?php  echo $this->Html->link(__('', true), array('action' => 'observaciones', $detalles[0]['Pedido']['id']), array('target'=>'_blank','class' => 'glyphicon glyphicon-list-alt', 'escape' => false)); ?></div> 
<?php } ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>

<table class="table-striped table-bordered table-condensed" align="center" id="add_producto"> 
    <tr>
        <td colspan="3" class="text-center"><h4>AGREGAR PRODUCTO AL PEDIDO</h4></td>
    </tr>
    <tr>
        <td><?php echo $this->Form->input('producto_id2', array('type' => 'text', 'label' => false, 'onchange'=>'mensajes_advertencia()', 'placeholder' => 'Digite el producto', 'size' => '80', 'maxlength' => '120')); ?><div id="div_mensaje_advertencia" style="color: #e32;"></div><div id="div_mensaje_cantidad" style="color: #e32;"></div></td>
        <td><?php echo $this->Form->input('cantidad_pedido', array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '7', 'maxlength' => '5', 'onchange'=>'verificar_cantidad()')); ?></td>
        <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs')); ?></td>
    </tr>
    <tr>
        <td colspan="3"  class="text-center"><b>Observaciones:</b> <br> <?php echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite sus observaciones generales del pedido.')); ?></td>
    </tr>
    <tr>
        <td colspan="3" class="text-center">
            <?php
            if (count($detalles) > 0) {
                if(count($presupuestos) == 0){
                    $disabled = 'disabled';
                }else{
                    $disabled = '';    
                }
                
            }else{
                $disabled = 'disabled';
            }
            ?>
            <?php echo $this->Form->button('Cancelar Pedido', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cancelar_pedido(' . $this->Session->read('Pedido.pedido_id') . ');')); ?>
            <?php echo $this->Form->button('Terminar Pedido', array('type' => 'button', 'class' => 'btn btn-primary  btn-xs', 'disabled'=>$disabled, 'onclick' => 'terminar_pedido(' . $this->Session->read('Pedido.pedido_id') . ');')); ?>            
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
            $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];
            ?>
    <tr>
        <td><?php echo $html->image('productos/'.$detalle['Producto']['codigo_producto'].'.jpg', array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $detalle['Producto']['nombre_producto'])) ?></td>
<!--        <td><?php //echo $detalle['Producto']['codigo_producto']; ?></td>-->
        <td><?php echo $detalle['Producto']['nombre_producto'].' '.$detalle['Producto']['marca_producto']; ?></td>
        <td><?php echo $detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td>$ <?php echo number_format($detalle['PedidosDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td><?php echo ($detalle['PedidosDetalle']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['PedidosDetalle']['cantidad_pedido'],0,",","."); ?></td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.'); ?></td>
        <td><?php echo $this->Form->button('Modificar', array('type' => 'button', 'class' => 'btn btn-info  btn-xs', 'onclick' => "modificar2('".$detalle['Producto']['producto_completo']."','".$detalle['PedidosDetalle']['cantidad_pedido']."','" . $detalle['PedidosDetalle']['id'] . "')")); ?>
            <?php echo $this->Form->button('Quitar', array('type' => 'button', 'class' => 'btn btn-warning  btn-xs', 'onclick' => 'quitar(' . $detalle['PedidosDetalle']['id'] . ')')); ?>
            <?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>
            <div align="center" id="edit_producto_<?php echo $detalle['PedidosDetalle']['id']; ?>" style="display: none;"><br>
                <?php echo $this->Form->input('cantidad_pedido_edit_'.$detalle['PedidosDetalle']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '5', 'maxlength' => '5')); ?>
                <?php echo $this->Form->input('id_edit_'.$detalle['PedidosDetalle']['id'], array('type' => 'hidden')); ?>
                <?php echo $this->Form->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cerrar_actualizar2('.$detalle['PedidosDetalle']['id'].');')); ?>
                <?php echo $this->Form->button('Actualizar', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => 'actualizar_pedido_4('.$detalle['PedidosDetalle']['id'].');')); ?>            
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
<?php /* echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>
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
            <?php echo $this->Form->button('Actualizar Pedido', array('type' => 'button', 'class' => 'btn btn-success  btn-xs', 'onclick' => 'actualizar_pedido();')); ?>            
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); */ ?>

