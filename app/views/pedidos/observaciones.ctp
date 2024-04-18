<?php

echo $this->Html->script(array('pedidos/fechas_entrega'));  

$meses_pedidos = array('0' => 'INSTALACION');
$meses = array('1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
$mes_actual = date('n');
$mes_siguiente = date('n', strtotime('+1 month'));
$meses_pedidos[$mes_actual] = $meses[$mes_actual];
$meses_pedidos[$mes_siguiente] = $meses[$mes_siguiente];

$clasificacion = array('Tarifa integral'=>'Tarifa integral','Facturacion sobre pedido'=>'Facturacion sobre pedido');
?>
<h2><span class="glyphicon glyphicon-list-alt"></span> OBSERVACIONES DEL PEDIDO #000<?php echo $pedidos['0']['Pedido']['id']; ?></h2>
<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'observaciones'))); ?>
<div class="text-center"><?php echo $this->Form->button('Actualizar Observaciones', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?></div>
<div>&nbsp;</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Producto</th>
        <th>Categoria</th>
        <th colspan="2">Observaciones</th>
    </tr>
    <?php
    $i = 0 ;
    foreach ($pedidos as $pedido):
        if($i == 0){
    ?>
    <tr>
        <td><b>Fecha Desde:</b></td>
        <td><?php echo $this->Form->input('fecha_entrega_1', array('type' => 'text', 'label' => false,'maxlength'=>'10','placeholder'=>'Desde ... ','value'=>$pedido['Pedido']['fecha_entrega_1'])); ?> </td>
        <td><b>Fecha Hasta:</b></td>
        <td><?php echo $this->Form->input('fecha_entrega_2', array('type' => 'text', 'label' => false,'maxlength'=>'10','placeholder'=>'Hasta ... ','value'=>$pedido['Pedido']['fecha_entrega_2'])); ?> </td>
    </tr>
    <tr>
        <td><b>Mes Pedido: </b></td>
        <td><?php echo $this->Form->input('mes_pedido', array('type' => 'select', 'options' => $meses_pedidos,'value'=>$pedido['Pedido']['mes_pedido'], 'label' => false, 'empty'=>'Seleccione una Opción','required'=>true)); //2021-07-28 ?><br>
        <td><b>Clasificación:</b></td>
        <td><?php echo $this->Form->input('clasificacion_pedido', array('type' => 'select', 'options' => $clasificacion, 'value'=>$pedido['Pedido']['clasificacion_pedido'], 'label' => false, 'empty'=>'Seleccione una Opción','required'=>true)); //2021-07-28 ?></td>
    </tr>
    <tr>
        <td><b>Guia Despacho:</b></td>
        <td><?php echo $this->Form->input('guia_despacho', array('type' => 'text', 'label' => false,'maxlength'=>'25','placeholder'=>'Desde ... ','value'=>$pedido['Pedido']['guia_despacho'])); ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Observaciones Generales:</b></td>
        <td colspan="3"><?php echo $this->Form->input('observaciones_'.$pedido['Pedido']['id'], array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','value'=>$pedido['Pedido']['observaciones'])); ?></td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td><?php echo $pedido['Producto']['codigo_producto']; ?> - <?php echo $pedido['Producto']['nombre_producto'].' '.$pedido['Producto']['marca_producto']; ?></td>
        <td><?php echo $pedido['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td colspan="2"><?php echo $this->Form->input('observacion_producto_'.$pedido['PedidosDetalle']['id'], array('type' => 'textarea', 'label' => false,'rows'=>'2','cols'=>'80','value'=>$pedido['PedidosDetalle']['observacion_producto'])); ?></td>
    </tr>
    <?php
    $i++;
    echo $this->Form->input('pedido_id', array('type' => 'hidden', 'value'=> $pedido['Pedido']['id'])); 
    endforeach;
    ?>
</table>
<div>&nbsp;</div>
<div class="text-center"><?php echo $this->Form->button('Actualizar Observaciones', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?></div>

<?php echo $this->Form->end(); ?>