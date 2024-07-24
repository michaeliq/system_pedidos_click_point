<?php
?>

<h2>Cargador Masivo de Productos</h2>
<?php echo $this->Form->create('Producto', array('action' => (count($productos_validos) > 0) ? 'upload_products_file' : 'add_many_products', 'type' => 'file')); ?>
<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 50%;">
    <tr>
        <td colspan="2" class="text-center"><b>Seleccione un archivo para cargar la información de los productos:</b><br>Tenga en cuenta lo siguiente:</td>
    </tr>
    <tr>
        <td colspan="2" align='left'>
            <b>1.</b> El archivo a cargar debe ser de tipo CSV.<br>
            <b>2.</b> El separador de los datos debe ser punto y coma (;), si se tiene un separador diferente, los datos no se cargaran.<br>
            <b>3.</b> El archivo se validará antes de cargar los productos, si el archivo no está correcto, no se procesará.<br>
            <b>4.</b> Luego de cargar el archivo, se procederá a mostrar los errores encontrados en el mismo, para que sean validados por el usuario y se realice la carga del archivo nuevamente.<br>
        </td>
    </tr>
    <?php if (count($productos_validos) == 0) { ?>
    <tr>
        <td colspan="2" align='center'>
            <?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file', 'label' => false, 'div' => false)); ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="row text-center">
            <?php if (count($productos_validos) > 0) {
                echo $this->Form->button('Guardar Productos', array('type' => 'submit', 'class' => 'btn btn-success col-md-3 col-md-offset-2'));
                echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning col-md-3 col-md-offset-2'));
            } else {
                echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-info'));
                echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning col-md-3 col-md-offset-2'));

            } ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <div class="text-center"><a href="../<?php echo "plantillas/PlantillaProductosMasivo.csv"; ?>"> <i class="icon-download"></i> Descargar aquí la plantilla de cargue masivo de Productos</a></div>
        </td>
    </tr>
</table>
<br>
<?php if (count($productos_validos) > 0) { ?>
    <?php
        echo $this->Form->hidden('productos_ids', array("value" => implode(",",$productos_ids)));
    ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 80%;">
        <tr>
            <th>CÓDIGO</th>
            <th>PRODUCTO</th>
            <th>DESCRIPCIÓN</th>
            <th>UMI</th>
            <th>PRECIO_BG</th>
            <th>PRECIO_NC</th>
            <th>IVA</th>
            <th>PROVEEDOR</th>
        </tr>
        <?php foreach ($productos_validos as $producto_valido) : ?>
            <tr>
                <td><?php echo $producto_valido["COD"]; ?></td>
                <td><?php echo $producto_valido["PRODUCTO"]; ?></td>
                <td><?php echo $producto_valido["DESCRIPCION"]; ?></td>
                <td><?php echo $producto_valido["UMI"]; ?></td>
                <td><?php echo $producto_valido["PRECIO_BG"]; ?></td>
                <td><?php echo $producto_valido["PRECIO_NC"]; ?></td>
                <td><?php echo $producto_valido["IVA"]; ?></td>
                <td><?php echo $producto_valido["PROVEEDOR"]; ?></td>
                <?php if($producto_valido["existe"]){
                    echo "<td class='text-center'><i title='Está en base de datos, será actualizado' style='color:blue;' class='glyphicon glyphicon-arrow-up'></i></td>";
                }else{
                    echo "<td class='text-center'><i title='Será creado' style='color:green;' class='glyphicon glyphicon-plus-sign'></i></td>";
                } ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php }; ?>
<?php echo $this->Form->end(); ?>