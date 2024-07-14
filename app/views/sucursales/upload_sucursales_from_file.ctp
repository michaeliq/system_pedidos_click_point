<?php
//debug($rel_localidad_rutas);
?>

<h2>Crear Sucursales a empresa <?php echo $empresa["Empresa"]["nombre_empresa"]; ?></h2>
<?php echo $this->Form->create('Sucursale', array('action' => 'upload_sucursales_from_file', 'type' => 'file')); ?>
<?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa['Empresa']['id'])) ?>

<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 50%;">
    <tr>
        <td colspan="2" class="text-center"><b>Seleccione un archivo para cargar la información.</b><br>Tenga en cuenta lo siguiente:</td>
    </tr>
    <tr>
        <td colspan="2" align='left'>
            <b>1.</b> El archivo a cargar debe ser de tipo CSV.<br>
            <b>2.</b> El separador de los datos debe ser punto y coma (;), si se tiene un separador diferente, los datos no se cargaran.<br>
            <b>3.</b> El archivo se validará antes de cargar las rutas, si el archivo no está correcto, no se procesará.<br>
            <b>4.</b> Luego de cargar el archivo, se procederá a mostrar los errores encontrados en el mismo, para que sean validados por el usuario y se realice la carga del archivo nuevamente.<br>
        </td>
    </tr>
    <tr>
        <td align='center'>
            Regional:*
        </td>
        <td align='center'>
            <?php echo $this->Form->input('regional', array('type' => 'select', 'label' => false, "options" => $regionales)); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align='center'>
            <?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file', 'label' => false, 'div' => false)); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="row text-center">
            <?php
            echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-info'));
            echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning col-md-3 col-md-offset-2'));
            ?>
        </td>
    </tr>
</table>
<br>
<?php if (count($sucursales_validas) > 0) { ?>

    <table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 80%;">
        <tr>
            <th>RUTA</th>
            <th>LOCALIDAD</th>
            <th>EXISTE</th>
        </tr>
        <?php foreach ($sucursales_validas as $sucursal_valida) : ?>
            <tr>
                <td><?php echo $sucursal_valida["SUCURSAL"]; ?></td>
                <td><?php echo $sucursal_valida["DIRECCION_SUCURSAL"]; ?></td>
                <?php if ($sucursal_valida["existe"]) {
                    echo "<td class='text-center'><i title='Está en base de datos, fué actualizado' style='color:blue;' class='glyphicon glyphicon-arrow-up'></i></td>";
                } else {
                    echo "<td class='text-center'><i title='Se ha creado' style='color:green;' class='glyphicon glyphicon-plus-sign'></i></td>";
                } ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php }; ?>
<?php echo $this->Form->end(); ?>