<?php

$file_name = 'informes/InformeInvGeneral_'.date('Y_m_d').'.csv';
$file = fopen($file_name, 'w');
$data_csv = utf8_decode("Codigo;Producto;Entrada;Salida Aprobados;Salida Despachados;En Bodega;FechaGeneracion\n");
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

    function search_stock_producto() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("stock_producto_sh");
        filter = input.value.toUpperCase();
        table = document.getElementById("productos_inventario");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[6];
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
<h2><span class="glyphicon glyphicon-book"></span> INFORME INVENTARIOS</h2>
<div class="index">
    <?php echo $this->Form->create('MovimientosProducto', array('url' => array('controller' => 'informes', 'action' => 'inv_general'))); ?>
    <fieldset>
        <legend><?php __('Filtro del Informe'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Productos:</td>
                <td colspan="3"><?php echo $this->Form->input('producto_id', array('type' => 'select', 'options' => $productos, 'empty' => array('0'=>'Todos'), 'label' => false,'multiple'=>'multiple', 'size'=>'10')); ?></td>
            </tr>
        </table>        
    </fieldset>
    <div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    </div>
    <div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
    <div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
    <div>&nbsp;</div>
    <table id="productos_inventario" class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>No.</th>
            <th>Producto</th>
            <th>Entrada</th>
<!--            <th>Salida Aprobados</th>-->
            <th>Salida Despachados</th>
            <th>En Bodega</th>
            <th>Stock Minimo</th>
            <th>Detalle</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th colspan="4*"><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Producto ..." title="Digite un nombre de producto"></th>
            <th colspan="1"><select id="stock_producto_sh" onchange="search_stock_producto()">
                    <option>Seleccione</option>
                    <option value="verde">En Verde</option>
                    <option value="amarillo">En Amarillo</option>
                    <option value="rojo">En Rojo</option>
                </select></th>
        </tr>
<?php
    $i = 0;
    foreach ($inventarios as $inventario):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        
        $stock_minimo = 'verde.png';
        $observaciones = null;
        //$enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_aprobado'];
        $enBodega  = $inventario['MovimientosProducto']['cantidad_entrada'] - $inventario['MovimientosProducto']['cantidad_salida_despachado'];
        if($enBodega < 0){
            $class = ' class="altrow danger"';
            $stock_minimo = 'rojo.png';
            $observaciones = $this->Html->link(__('', true), array('action' => 'inv_observaciones_producto', base64_encode($inventario['MovimientosProducto']['producto_id'])), array('class' => 'glyphicon glyphicon-dashboard', 'escape' => false, 'title'=>'Observaciones para el producto','target' => '_blank'));
        }else{
            // Si es menor del 100%, esta menor al stock minimo del producto
            $calculoStock = ($enBodega * 100)/$inventario['Producto']['stock_minimo'];
            if( ($calculoStock) < 100){
                $stock_minimo = 'amarillo.png';
            }else{
                $stock_minimo = 'verde.png';
            }
        }
      
?>
        <tr <?php echo $class; ?>>
            <td><?php echo $i; ?></td>
            <td><?php echo $inventario['Producto']['codigo_producto']; ?> - <?php echo $inventario['Producto']['nombre_producto']; ?></td>
            <td><?php echo $inventario['MovimientosProducto']['cantidad_entrada']; ?></td>
<!--            <td><?php //echo $inventario['MovimientosProducto']['cantidad_salida_aprobado']; ?></td>-->
            <td><?php echo $inventario['MovimientosProducto']['cantidad_salida_despachado']; ?></td>
            <td title="Cantidad Entrada - Salida Despachados"><?php echo $enBodega; ?></td>
            <td title="<?php echo $inventario['Producto']['stock_minimo']; ?>"><?php echo $html->image($stock_minimo); ?></td>
            <td><?php echo $this->Html->link(__('', true), array('action' => 'inv_detalle_producto', base64_encode($inventario['MovimientosProducto']['producto_id'])), array('class' => 'glyphicon glyphicon-list', 'escape' => false, 'title'=>'Detalles para el producto', 'target' => '_blank')); ?>
            <?php echo $observaciones; ?></td>
        </tr>    

<?php 
        $data_csv =  utf8_decode($inventario['Producto']['codigo_producto']). ';' .
                utf8_decode($inventario['Producto']['nombre_producto']). ';' .
                utf8_decode($inventario['MovimientosProducto']['cantidad_entrada']). ';' .
                utf8_decode($inventario['MovimientosProducto']['cantidad_salida_aprobado']). ';' .
                utf8_decode($inventario['MovimientosProducto']['cantidad_salida_despachado']). ';' .
                utf8_decode($enBodega). ';' .
                utf8_decode(date('Y-m-d H:i:s'));
        fwrite($file, $data_csv);
        fwrite($file, chr(13) . chr(10));
    endforeach; 
    fclose($file);
?>
    </table>
    <div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
    <div>&nbsp;</div>
</div>