<?php

$file_name = 'informes/InformeSugeridoCompra_'.date('Y_m_d').'.csv';
$file = fopen($file_name, 'w');
$data_csv = utf8_decode("Codigo;Producto;SaldoFisico;PedidosAprobados;StockMinino;OrdenCompra;SugeridoCompra;UltimoPrecio;FechaGeneracion\n");
fwrite($file, $data_csv);    
?>
<script>

    function search_nombre_producto() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("nombre_producto_sh");
        filter = input.value.toUpperCase();
        table = document.getElementById("productos_inventario");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME SUGERIDO DE COMPRA</h2>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<div>&nbsp;</div>
<table id="productos_inventario" class="table table-striped table-bordered table-hover table-condensed">
<!--    <tr>
        <td colspan="8"><b>Formula para sugerido de compra: (<span style="color: red;">SALDO FISICO</span> + <span style="color: green;">PEDIDOS ESTADO APROBADO</span> + <span style="color: orange;">STOCK MINIMO</span>) - <span style="color: blue;">ORDEN DE COMPRA (APROBADAS - ENTRADA PARCIAL)</span></b></td> 
    </tr>-->
    <tr>
        <th>C&oacute;digo</th>
        <th>Descripci&oacute;n</th>
        <th>Saldo Fisico</th>
        <th>Pedidos<br>Aprobados</th>
        <th>Ordenes<br>Compra</th>
        <th>Stock Minimo</th>
        <th>Sugerido<br>Compra</th>
<!--        <th>Último Precio</th>-->
    </tr>
    <tr>
        <th>&nbsp;</th>
        <th><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Producto / Proveedor ..." title="Digite un nombre de producto"></th>
        <th colspan="5">&nbsp;</th>
    </tr>
    <?php
    foreach ($inventarios as $inventario):
        //$enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_aprobado'];
        $enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_despachado'];
        // if($enBodega <= 0){ // $enBodega < 0
            $cantidad_aprobada = 0;
            foreach ($productos_aprobados as $producto):
                if($inventario['Producto']['id'] == $producto[0]['producto_id']){
                    $cantidad_aprobada = $producto[0]['sum'];
                }
            endforeach;
            
            $cantidad_ordenada = 0;
            foreach ($detalles_aprobados as $orden_compra):
                if($inventario['Producto']['id'] == $orden_compra['OrdenComprasDetalle']['producto_id']){
                    $cantidad_ordenada = $cantidad_ordenada + $orden_compra['OrdenComprasDetalle']['cantidad_orden'] - $orden_compra['OrdenComprasDetalle']['cantidad_orden_parcial'];
                }
            endforeach;

            // FORMULA DE SUGERIDO DE COMPRA
            $stock = 0; 
            // Verificar el stock minimo
            if(($enBodega + $cantidad_ordenada) < abs($inventario['Producto']['stock_minimo'])){
                $stock = abs($inventario['Producto']['stock_minimo']) - ($enBodega + $cantidad_ordenada);
            }

            //  Si saldo fisico + cantidad ordenada es menor a los cantidad aprobada en los pedidos.
            if(($enBodega + $cantidad_ordenada) < $cantidad_aprobada){
                if($enBodega > 0){
                    // $sugerido_compra = $cantidad_aprobada - ($enBodega - $cantidad_ordenada) + $stock;
		    $sugerido_compra = $cantidad_aprobada - ($enBodega + $cantidad_ordenada) + $stock;
                    $procedimiento = '1. (Saldo Fisico + Cantidad Ordenada < Pedidos Aprobados) = Pedidos Aprobados - (Saldo Fisico + Cantidad Ordenada) + Faltante Stock Minimo';
                }else{
                        if($enBodega < 0){
                            $sugerido_compra = $cantidad_aprobada + (abs($enBodega) - $cantidad_ordenada) + abs($inventario['Producto']['stock_minimo']);
                            $procedimiento = 'Si saldo fisico es negativo, (Pedidos Aprobados + Saldo Fisico Positivo - Cantidad Ordenada) + Stock Minimo';  
                        }else{
                            $sugerido_compra = $cantidad_aprobada - ($enBodega + $cantidad_ordenada) + $stock;
                            $procedimiento = 'Cantidad Aprobada - (Saldo fisico + Cantidad Ordenada) + Faltante Stock Minimo';
                        }
                }
            }
            // Si saldo fisico + cantidad ordenada es mayor igual a la cantidad aprobado
            if( ($enBodega + $cantidad_ordenada) >= $cantidad_aprobada){
                if(($enBodega + $cantidad_ordenada) < abs($inventario['Producto']['stock_minimo'])){
                    $sugerido_compra = $stock;
                    $procedimiento = '(Saldo Fisico + Cantidad Ordenada >= Pedidos Aprobados) = Stock Minimo - (Saldo Fisico + Cantidad Ordenada)';
                }else{
                   $sugerido_compra = 0; 
                   $procedimiento = 'Saldo Fisico > Pedidos Aprobados y mayor a Stock Minimo = Cero';
                }
            }
            // FIN FORMULA SUGERIDO DE COMPRA
            
            // $sugerido_compra = abs($enBodega) + abs($cantidad_aprobada)+ abs($inventario['Producto']['stock_minimo']) - abs($cantidad_ordenada);

            ?>
    <tr class="altrow" title="<?php echo $procedimiento; ?>">
        <td><?php echo $inventario['Producto']['codigo_producto']; ?></td>
        <td><?php echo $inventario['Producto']['nombre_producto']; ?> - <span style="font-size: 10px;"><?php echo $inventario['Proveedore']['nombre_proveedor']; ?></span></td>
        <td class="text-center"  <?php if ($enBodega < 0){ ?> style="color: red; font-weight: bold;" <?php } ?>><?php echo $enBodega; ?></td>
        <td class="text-center"><?php echo $cantidad_aprobada; ?></td>
        <td class="text-center" style="color: blue; font-weight: bold;"><?php echo $cantidad_ordenada; ?></td>
        <td class="text-center" style="color: orange; font-weight: bold;"><?php echo $inventario['Producto']['stock_minimo']; ?></td>
        <td class="text-center" style="color: green; font-weight: bold;"><?php echo abs($sugerido_compra); ?></td>
<!--        <td class="text-center">$<?php // echo  number_format($inventario['Producto']['precio_producto'], 2, ',', '.');  ?></td>-->
    </tr>
    <?php
            $data_csv =  utf8_decode($inventario['Producto']['codigo_producto']). ';' .
                utf8_decode($inventario['Producto']['nombre_producto']). ';' .
                utf8_decode($enBodega). ';' .
                utf8_decode($cantidad_aprobada). ';' .
                utf8_decode($inventario['Producto']['stock_minimo']). ';' .
                utf8_decode($cantidad_ordenada). ';' .
                utf8_decode($sugerido_compra). ';' .
                utf8_decode(date('Y-m-d H:i:s'));
        fwrite($file, $data_csv);
        fwrite($file, chr(13) . chr(10));   
        //}

    endforeach;
    fclose($file);
    ?>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<div>&nbsp;</div>