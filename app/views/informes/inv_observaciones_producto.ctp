<?php

// print_r($detalles);
$file_name = 'informes/InformeObservaciones_'.$detalles[0]['Producto']['codigo_producto'].'_'.date('Y_m_d').'.csv';
$file = fopen($file_name, 'w');
$data_csv = utf8_decode("No.Orden;Fecha Aprobado;Producto;Cantidad;Observacion\n");
fwrite($file, $data_csv);    
 ?>
<h2>Observaciones para <?php echo $detalles[0]['Producto']['producto_completo'];?></h2>
<span style="text-align: center; color: red;">Se muestra información para pedidos en estado aprobado.</span>
<table id="productos_observaciones" class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>No. Orden</th>
        <th>Fecha Aprobado</th>
        <th>Cantidad</th>
        <th>Observaciones</th>
    </tr>
    <?php
    $i = 0;
    foreach ($detalles as $detalle):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        ?>
    <tr <?php echo $class; ?>>
        <td><b>#000<?php echo $detalle['PedidosDetalle']['pedido_id']; ?></b></td>
        <td><?php echo $detalle['Pedido']['fecha_aprobado_pedido']; ?></td>
        <td><?php echo $detalle['PedidosDetalle']['cantidad_pedido']; ?></td>
        <td><?php echo $detalle['PedidosDetalle']['observacion_producto']; ?></td>
    </tr>
    <?php  $data_csv =  utf8_decode($detalle['PedidosDetalle']['pedido_id']). ';' .
                utf8_decode($detalle['Pedido']['fecha_aprobado_pedido']). ';' .
                utf8_decode($detalle['Producto']['producto_completo']). ';' .
                utf8_decode($detalle['PedidosDetalle']['cantidad_pedido']). ';' .
                utf8_decode($detalle['PedidosDetalle']['observacion_producto']);
        fwrite($file, $data_csv);
        fwrite($file, chr(13) . chr(10));
        endforeach;  
        fclose($file);
        ?>
</table>
<div class="text-center"><a href="../../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<div>&nbsp;</div>


