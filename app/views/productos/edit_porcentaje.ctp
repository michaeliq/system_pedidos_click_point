<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo $this->Html->script(array('productos/productos_edit'));
?>
<h2>Actualización precios a <?php echo $plantillas_detalles[0]['Producto']['producto_completo']; ?> (<?php echo $porcentaje; ?> %)</h2>
<?php if(count($plantillas_detalles)>0){?>
<table class="table-striped table-bordered table-hover table-condensed"align="center">
    <tr>
        <th>Plantilla Afectada</th>
        <th>Categor&iacute;a</th>
        <th>Precio Antiguo</th>
        <th>Precio Nuevo</th>
        <th>IVA</th>
        <th>Medida</th>
    </tr>
        <?php
        foreach ($plantillas_detalles as $plantilla_detalle) {
        ?>
    <tr>
        <td><?php echo $plantilla_detalle['Plantilla']['nombre_plantilla']; ?></td>
        <td><?php echo $plantilla_detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
        <td style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto'] + ($plantilla_detalle['PlantillasDetalle']['precio_producto'] * $porcentaje), 2, ',', '.'); ?></td>
        <td><?php echo ($plantilla_detalle['PlantillasDetalle']['iva_producto']*100); ?> %</td>
        <td><?php echo $plantilla_detalle['PlantillasDetalle']['medida_producto']; ?></td>
    </tr>
            <?php
        }
        ?>
    <tr>
        <td colspan="8" class="text-center"><?php echo $this->Form->button('Regresar a Productos', array('type' => 'button', 'id' => 'regresar_edit2', 'class' => 'btn btn-warning')); ?></td>
    </tr>
</table>
<?php } ?>

