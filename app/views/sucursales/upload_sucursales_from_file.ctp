<?php
//debug($rel_localidad_rutas);
$file_name = "plantilla/PlantillaSucursalesMasiva.csv";
$file = fopen($file_name, 'w');
$data_csv = utf8_decode("ID;CECO;OI;SUCURSAL;DEPARTAMENTO;MUNICIPIO;DIRECCION_SUCURSAL;TELEFONO_SUCURSAL;CORREO_SUCURSAL;NOMBRE_CONTACTO;TELEFONO_CONTACTO;CORREO_CONTACTO;Aseo;Cafeteria;Higienicos;EPP;Dotación;Maquinaria;ResPescado;Pollo\n");
fwrite($file, $data_csv);
foreach ($sucursales as $sucursal) {
    $data_csv = "";
    $id = utf8_decode($sucursal['Sucursale']['id']);
    $ceco = utf8_decode($sucursal['Sucursale']['ceco_sucursal']);
    $oi = utf8_decode($sucursal['Sucursale']['oi_sucursal']);
    $nombre_sucursal = utf8_decode($sucursal['Sucursale']['nombre_sucursal']);
    $departamento = utf8_decode($sucursal['Sucursale']['departamento_id']);
    $municipio = utf8_decode($sucursal['Sucursale']['municipio_id']);
    $direccion_s = str_replace(array("\r", "\n"), '', utf8_decode($sucursal['Sucursale']['direccion_sucursal']));
    $telefono_s = utf8_decode($sucursal['Sucursale']['telefono_sucursal']);
    $email_s = utf8_decode($sucursal['Sucursale']['email_sucursal']);
    $direccion_c = utf8_decode($sucursal['Sucursale']['nombre_contacto']);
    $telefono_c = utf8_decode($sucursal['Sucursale']['telefono_contacto']);
    $email_c = utf8_decode($sucursal['Sucursale']['email_contacto']);
    $data_csv .= "$id;";
    $data_csv .= "$ceco;";
    $data_csv .= "$oi;";
    $data_csv .= "$nombre_sucursal;";
    $data_csv .= "$departamento;";
    $data_csv .= "$municipio;";
    $data_csv .= "$direccion_s;";
    $data_csv .= "$telefono_s;";
    $data_csv .= "$email_s;";
    $data_csv .= "$direccion_c;";
    $data_csv .= "$telefono_c;";
    $data_csv .= "$email_c;";
    $data_csv .= ";;;;;;";
    fwrite($file, $data_csv);
    fwrite($file, chr(13) . chr(10));
}
fclose($file);
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
        <td align='center'>
            Plantilla de productos:*
        </td>
        <td align='center'><?php echo $this->Form->input('plantillas', array('type' => 'select', 'multiple' => 'multiple', 'options' => $plantillas, 'label' => false, "required" => true)); ?></td>
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
    <tr>
        <td colspan="2">
            <div class="text-center"><a href="../../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar aquí la plantilla de cargue masivo de Sucursales</a></div>
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