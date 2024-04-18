<?php

ini_set('max_execution_time','300');
ini_set('memory_limit', '912M'); ?>
<?php

// Crear archivo plano csv
    $file_name = 'informes/InformePreciosPlantillaVsOperadores_'.date('Ymdhis').'.csv';
    $file = fopen($file_name, 'w');
?>
<h2><span class="glyphicon glyphicon-book"></span> Informe Precios Plantilla Vs Operadores</h2>
<?php echo $this->Form->create('Plantilla', array('url' => array('controller' => 'informes', 'action' => 'info_plantilla_precio'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombre plantilla: </td>
            <td><?php echo $this->Form->input('id', array('type' => 'select', 'options' => $plantillas, 'empty' => array('0'=>'Seleccione'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>

    <?php if(count($detalles)>0){ ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<table class="table table-hover">
    <tr>
        <th>Codigo</th>
        <th>Producto</th>
        <th>Precio Centro Aseo</th>
        <th>Precio Venta</th>
        <th>IVA Plantilla 2</th>
        <th>Precio</th>
        <th>IVA</th>
        <th>Op.Log. 1</th>
        <th>Op.Log. 2</th>
        <th>Op.Log. 3</th>
        <th>Op.Log. 4</th>
        <th>Op.Log. 5</th>
        <th>Op.Log. 6</th>
    </tr>
    <?php 
    $data_csv = utf8_decode("Plantilla;CodigoProducto;NombreProducto;PrecioCentroAseo;PrecioVenta;IvaPlantilla;Precio;Iva;Op.Log_1;Op.Log_2;Op.Log_3;Op.Log_4;Op.Log_5;Op.Log_6\n");
    fwrite($file, $data_csv);    
    foreach ($detalles as $detalle) : 
        $color_venta = 'green';
        if($detalle['VInformePlantillaPrecio']['plantilla_precio_producto_2'] < $detalle['VInformePlantillaPrecio']['plantilla_precio_producto']){
            $color_venta = 'red';
        }
        ?>
    <tr>
        <td><?php echo $detalle['VInformePlantillaPrecio']['codigo_producto']; ?></td>
        <td><?php echo $detalle['VInformePlantillaPrecio']['nombre_producto']; ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['plantilla_precio_producto'], 2, ',', '.'); ?></td>
        <td style="color: <?php echo $color_venta;?>; font-weight: bold; "><?php echo number_format($detalle['VInformePlantillaPrecio']['plantilla_precio_producto_2'], 2, ',', '.'); ?></td>
        <td><?php echo $detalle['VInformePlantillaPrecio']['plantilla_iva_producto']; ?> %</td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['precio_producto'], 2, ',', '.'); ?></td>
        <td><?php echo $detalle['VInformePlantillaPrecio']['iva_producto']; ?> %</td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa1_precio'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa2_precio'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa3_precio'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa4_precio'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa5_precio'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($detalle['VInformePlantillaPrecio']['empresa6_precio'], 2, ',', '.'); ?></td>
    </tr>
    <?php  
    $data_csv = 
            utf8_decode($detalle['VInformePlantillaPrecio']['nombre_plantilla']) . ';' .
            utf8_decode($detalle['VInformePlantillaPrecio']['codigo_producto']) . ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['nombre_producto']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['plantilla_precio_producto']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['plantilla_precio_producto_2']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['plantilla_iva_producto']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['precio_producto']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['iva_producto']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa1_precio']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa2_precio']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa3_precio']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa4_precio']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa5_precio']). ';' .
                utf8_decode($detalle['VInformePlantillaPrecio']['empresa6_precio']);
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));

            endforeach;
            fclose($file);
            ?>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
    <?php } ?>
