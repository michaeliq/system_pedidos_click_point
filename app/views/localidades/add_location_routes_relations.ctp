<?php
//debug($rel_localidad_rutas);
?>

<h2>Crear Vinculos entre Localidades y Rutas</h2>
<?php echo $this->Form->create('Localidades', array('action' => 'add_location_routes_relations', 'type' => 'file')); ?>
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
    <?php if (count($localidades_validas) == 0) { ?>
    <tr>
        <td colspan="2" align='center'>
            <?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file', 'label' => false, 'div' => false)); ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="row text-center">
            <?php if (count($localidades_validas) > 0) {
                echo $this->Form->button('Procesar Datos', array('type' => 'submit', 'class' => 'btn btn-success col-md-3 col-md-offset-2'));
                echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning col-md-3 col-md-offset-2'));
            } else {
                echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-info'));
                echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning col-md-3 col-md-offset-2'));

            } ?>
        </td>
    </tr>
</table>
<br>
<?php if (count($localidades_validas) > 0) { ?>
    
    <table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 80%;">
        <tr>
            <th>RUTA</th>
            <th>LOCALIDAD</th>
            <th>EXISTE</th>
        </tr>
        <?php foreach ($localidades_validas as $localidad_valida) : ?>
            <tr>
                <td><?php echo $localidad_valida["RUTA_N"]; ?></td>
                <td><?php echo $localidad_valida["LOCALIDAD_N"]; ?></td>
                <?php if($localidad_valida["existe"]){
                    echo "<td class='text-center'><i title='Está en base de datos, fué actualizado' style='color:blue;' class='glyphicon glyphicon-arrow-up'></i></td>";
                }else{
                    echo "<td class='text-center'><i title='Se ha creado' style='color:green;' class='glyphicon glyphicon-plus-sign'></i></td>";
                } ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php }; ?>
<?php echo $this->Form->end(); ?>