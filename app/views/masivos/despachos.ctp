<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2>Cargador Masivo de Despachos</h2>
<?php echo $this->Form->create('Masivo', array('type' => 'file')); ?>
<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 50%;">
    <tr>
        <td colspan="2" class="text-center"><b>Seleccione un archivo para cargar las guias de los pedidos:</b><br>Tenga en cuenta lo siguiente:<br>
            <b>Extensión de Archivo:</b> CSV (separado por punto y coma ;) - ejemplo: <b>archivo_desapachos.csv</b><br><br>
                <b>Estructura interna del Archivo:</b><br>
                NoOrden;NoGuia;Transportadora<br>
                58658;GUIA_58658;NombreTransportadora<br>
                58657;GUIA_58657;NombreTransportadora<br>
                58656;GUIA_58656;NombreTransportadora<br>
                58655;GUIA_58655;NombreTransportadora<br>
                58654;GUIA_58654;NombreTransportadora<br></td>
    </tr>
    <tr>
        <td colspan="2" align='center'><?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file','label'=>false,'div'=>false)); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <?php echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            <?php echo $this->Form->input('fecha_hora_carga',array('type'=>'hidden', 'value'=>date('Y-m-d H:i:s')));?></td>
    </tr>
</table>
<?php if(count($pedidos_actualizados) > 0){ ?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <td colspan="6"  align='center'><h2>DETALLE DE LOS PEDIDOS ACTUALIZADOS</h2></td>
    </tr>

    <tr>
        <td><b>No.</b></td>
        <td><b>No. Pedido</b></td>
        <td><b>Guia</b></td>
        <td><b>Transportadora</b></td>
        <td><b>Procesado</b></td>
        <td><b>Error</b></td>
    </tr>
<?php
    $j = 0;
    foreach ($pedidos_actualizados as $pedidos_actualizado):
    if ($j++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
    <tr<?php echo $class; ?>>
        <td><?php echo $j;?></td>
        <td class="orden_pedido">#000<?php echo $pedidos_actualizado['0']['pedido_id']; ?></td>
        <td><?php echo $pedidos_actualizado['0']['guia_pedido']; ?></td>
        <td><?php echo $pedidos_actualizado['0']['transportadora']; ?></td>
        <td><?php  if($pedidos_actualizado['0']['estado']){ echo  $html->image('verde.png').' Procesado'; }else{ echo $html->image('rojo.png')." No Procesado"; } ?></td>
        <td><?php echo $pedidos_actualizado['0']['error_generado']; ?> </td>
    </tr>  
<?php
    endforeach;
?>
<?php } ?>
</table>
