<script>
$(document).ready(function(){
    $("#toggle_proceso").click(function(){
        $("#pedidos_proceso").toggle();
    });
});
</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME PEDIDOS POR ESTADOS (CANTIDADES)</h2>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr class="text-center">
        <th>Usuario</th>
        <th>Nombres</th>
        <th>En Proceso</th>
        <th>Cancelado</th>
        <th>Pendiente</th>
        <th>Aprobado</th>
        <th>Despachado</th>
        <th>Entregado</th>
    </tr>
<?php
    $en_proceso = 0;
    $en_cancelado = 0;
    $en_pendiente = 0;
    $en_aprobado = 0; 
    $en_despachado = 0;
    $en_entregado = 0;
    
    foreach ($datas as $data) :
        $pedidos_proceso ='';
        $pedidos_cancelado = '';
        $pedidos_pendiente = '';
        $pedidos_aprobado = '';
        $pedidos_despachado ='';
        $pedidos_entregado = '';
        
        $en_proceso = $en_proceso + $data['0']['en_proceso'];
        $en_cancelado = $en_cancelado + $data['0']['en_cancelado'];
        $en_pendiente = $en_pendiente + $data['0']['en_pendiente'];
        $en_aprobado = $en_aprobado + $data['0']['en_aprobado']; 
        $en_despachado = $en_despachado + $data['0']['en_despachado'];
        $en_entregado = $en_entregado + $data['0']['en_entregado'];

        foreach($datas1 as $data1):
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 1){
                        $pedidos_proceso = $data1['0']['pedidos_id'];
                }
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 2){
                        $pedidos_cancelado = $data1['0']['pedidos_id'];
                }
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 3){
                        $pedidos_pendiente = $data1['0']['pedidos_id'];
                }
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 4){
                        $pedidos_aprobado = $data1['0']['pedidos_id'];
                }
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 5){
                        $pedidos_despachado = $data1['0']['pedidos_id'];
                }
                if($data['0']['user_id'] == $data1['0']['user_id'] AND $data1['0']['pedido_estado_pedido'] == 6){
                        $pedidos_entregado = $data1['0']['pedidos_id'];
                }
        endforeach;
?>
    <tr class="text-center">
        <td><?php echo $data['0']['username']; ?></td>
        <td><?php echo $data['0']['nombres_persona']; ?></td>
        <td><?php echo $data['0']['en_proceso']; ?><div id="pedidos_proceso"><?php //echo $pedidos_proceso; ?></div><div id="toggle_proceso"></div></td>
        <td><?php echo $data['0']['en_cancelado']; ?><div><?php //echo $pedidos_cancelado; ?></div></td>
        <td><?php echo $data['0']['en_pendiente']; ?><div><?php //echo $pedidos_pendiente; ?></div></td>
        <td><?php echo $data['0']['en_aprobado']; ?><div><?php //echo $pedidos_aprobado; ?></div></td>
        <td><?php echo $data['0']['en_despachado']; ?><div><?php //echo $pedidos_despachado; ?></div></td>
        <td><?php echo $data['0']['en_entregado']; ?><div><?php //echo $pedidos_entregado; ?></div></td>
    </tr>
<?php
endforeach;
?>
    <tr class="text-center">
        <td></td>
        <td>TOTAL</td>
        <td><?php echo $en_proceso; ?></td>
        <td><?php echo $en_cancelado; ?></td>
        <td><?php echo $en_pendiente; ?></td>
        <td><?php echo $en_aprobado; ?></td>
        <td><?php echo $en_despachado; ?></td>
        <td><?php echo $en_entregado; ?></td>
    </tr>
    <tr>
        <td colspan="8" class="text-center"><?php echo $en_proceso + $en_cancelado + $en_pendiente + $en_aprobado + $en_despachado + $en_entregado; ?> Pedidos</td>
    </tr>
</table>