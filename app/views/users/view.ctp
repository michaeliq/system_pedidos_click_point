<?php

echo $this->Html->script(array('users/users_edit')); ?>
<fieldset>
    <legend><?php __('Ver Usuario ');
echo $user['User']['username']; ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombres:</td>
            <td><?php echo $user['User']['nombres_persona']; ?></td>
            <td></td>
            <td></td>
<!--            <td>Apellidos:</td>
            <td><?php //echo $user['User']['apellidos_persona']; ?></td>-->
        </tr>
        <tr>
            <td>Tipo de Documento:</td>
            <td><?php echo $user['TipoDocumento']['nombre_tipo_documento']; ?></td>
            <td>No. Documento:</td>
            <td><?php echo $user['User']['no_identificacion_persona']; ?></td>
        </tr>
        <tr>
            <td>G&eacute;nero:</td>
            <td><?php echo $user['TipoSexo']['nombre_tipo_sexo']; ?></td>
            <td>Fecha Nacimiento:</td>
            <td><?php echo $user['User']['fecha_nacimiento']; ?></td>
        </tr>
        <tr>
            <td>Departamento:</td>
            <td><?php echo $user['Departamento']['nombre_departamento']; ?></td>    
            <td>Municipio:</td>
            <td><?php echo $user['Municipio']['nombre_municipio']; ?></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n:</td>
            <td><?php echo $user['User']['direccion_residencia']; ?></td>
            <td>Tel&eacute;fono:</td>
            <td><?php echo $user['User']['telefono_residencia']; ?></td>
        </tr>
        <tr>
            <td>Celular:</td>
            <td><?php echo $user['User']['celular_persona']; ?></td>
            <td>E-mail:</td>
            <td><?php echo $user['User']['email_persona']; ?></td>
        </tr>
        <tr>
            <td colspan="4"><h2>Datos Ingreso</h2></td>
        </tr>
        <tr>
            <td>Usuario:</td>
            <td><?php echo $user['User']['username']; ?></td>
            <td>Rol:</td>
            <td><?php echo $user['Role']['rol_nombre']; ?></td>
        </tr>
        <tr>
            <td>Empresa:</td>
            <td colspan="3"><?php echo $user['Empresa']['nombre_empresa']; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;" >
<?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_view', 'class' => 'btn btn-warning')); ?>
            </td>
        </tr>
    </table>
</fieldset>