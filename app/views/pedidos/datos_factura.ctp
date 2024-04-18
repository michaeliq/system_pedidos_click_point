<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($pedidos);
if($this->Session->read('Auth.User.id')=='1' || $this->Session->read('Auth.User.id')=='297'  || $this->Session->read('Auth.User.id')=='509'){ 
?>
<script>
$(function () {
    $('#regresar_add').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "../facturado";
        }
    });
});
</script>
<h2><span class="glyphicon glyphicon-star"></span> FACTURADO - ACTUALIZAR DATOS FACTURA <?php echo $pedidos['Pedido']['numero_factura']; ?></h2>
    <?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'datos_factura'))); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden','value'=>$pedidos['Pedido']['id'])); ?>
<table class="table-striped table-bordered table-condensed" align="center"> 
    <tr>
        <td><b>No. Orden:</b></td>
        <td><span style="color: red;"><b><h4>#000<?php echo $pedidos['Pedido']['id']; ?></h4></b></span></td>
    </tr>
    <tr>
        <td colspan="2"><b><?php echo $pedidos['Empresa']['nombre_empresa']; ?></b></td>
    </tr>
    <tr>
        <td><b>N. Factura:</b></td>
        <td><?php echo $this->Form->input('numero_factura', array('type' => 'text', 'label' => false, 'placeholder'=>'No. Factura', 'value'=>$pedidos['Pedido']['numero_factura'])); ?></td>
    </tr>
    <tr>
        <td><b>Valor Factura:</b></td>
        <td><?php echo $this->Form->input('valor_factura', array('type' => 'text', 'label' => false, 'placeholder'=>'($) Valor Factura', 'value'=>$pedidos['Pedido']['valor_factura'])); ?></td>
    </tr>
    <tr>
        <td><b>Valores Pedido:</b></td>
        <td>Total Sin IVA: $ <?php echo number_format($pedidos['Pedido']['pedido_valor_sin_iva'],0,",","."); ?><br>
            IVA: $ <?php echo number_format($pedidos['Pedido']['pedido_valor_iva'],0,",","."); ?><br>
            TOTAL: $ <?php echo number_format($pedidos['Pedido']['pedido_valor_total'],0,",","."); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning  btn-xs')); ?>
            <?php echo $this->Form->button('Actualizar Factura', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
        </td>
    </tr>
</table>
    <?php echo $this->Form->end(); ?>
<?php }else{ ?>
<div class="text-center"><h1>NO TIENE ACCESO A ESTE MODULO!</h1></div>
<?php } ?>
