<?php

echo $this->Html->script(array('sucursales/sucursales_edit')); ?>
<?php echo $this->Form->create('Sucursale'); ?>
<h2>Ver Sucursal</h2>
<table class="table table-striped table-bordered table-condensed" align="center">
    <tr>
        <td>Sucursal:</td>
        <td><?php echo $sucursal['Sucursale']['nombre_sucursal']; ?></td>
        <td>Empresa:</td>
        <td><?php echo $sucursal['Empresa']['nombre_empresa']; ?></td>
    </tr>
    <tr>
        <td>Departamento:</td>
        <td><?php echo $sucursal['Departamento']['nombre_departamento']; ?></td>    
        <td>Municipio:</td>
        <td><?php echo $sucursal['Municipio']['nombre_municipio']; ?></td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Sucursal:</td>
        <td><?php echo $sucursal['Sucursale']['direccion_sucursal']; ?></td>
        <td>Tel. Sucursal:</td>
        <td><?php echo $sucursal['Sucursale']['telefono_sucursal']; ?></td>
    </tr>
    <tr>
        <td>E-mail Sucursal:</td>
        <td><?php echo $sucursal['Sucursale']['email_sucursal']; ?></td>
        <td>Regional:</td>
        <td><?php echo $sucursal['Sucursale']['regional_sucursal']; ?></td>
    </tr>
    <tr>
        <td>OI:</td>
        <td><?php echo $sucursal['Sucursale']['oi_sucursal']; ?></td>
        <td>CECO:</td>
        <td><?php echo $sucursal['Sucursale']['ceco_sucursal']; ?></td>
    </tr>
    <tr>
        <td>Plantilla: </td>
        <td><?php foreach ($plantillas as $plantilla) :
                echo $plantilla['Plantilla']['plantilla_tipo'];
                echo "<br>";
            endforeach; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4"><h2>Datos Contacto</h2></td>
    </tr>
    <tr>
        <td>Nombre Contacto:</td>
        <td><?php echo $sucursal['Sucursale']['nombre_contacto']; ?></td>
        <td>Tel. Contacto:</td>
        <td><?php echo $sucursal['Sucursale']['telefono_contacto']; ?></td>
    </tr>
    <tr>
        <td>E-mail Contacto:</td>
        <td><?php echo $sucursal['Sucursale']['email_contacto']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4" class="text-center" >
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_view', 'class' => 'btn btn-warning')); ?>
        </td>
    </tr>
    <?php
    echo $this->Form->input('id_empresa', array('type' => 'hidden', 'value' => $sucursal['Empresa']['id']));
    echo $this->Form->input('id', array('type' => 'hidden'));
    ?>
</table>

<?php echo $this->Form->end(); ?>