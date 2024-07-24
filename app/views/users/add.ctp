<?php

echo $this->Html->script(array('users/users_add.js?cache=1')); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend><?php __('Nuevo Usuario'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombres: *</td>
            <td><?php echo $this->Form->input('nombres_persona', array('type' => 'text', 'label' => false, 'required'=>true)); ?></td>
<!--            <td>Apellidos: *</td>-->
            <td></td>                
            <td><?php //echo $this->Form->input('apellidos_persona', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Tipo de Documento: *</td>
            <td><?php echo $this->Form->input('tipo_documento_id', array('type' => 'select', 'options' => $tipoDocumentos, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
            <td>No. Documento: *</td>
            <td><?php echo $this->Form->input('no_identificacion_persona', array('type' => 'text', 'label' => false, 'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>G&eacute;nero: *</td>
            <td><?php echo $this->Form->input('tipo_sexo_id', array('type' => 'select', 'options' => $tipoSexos, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
            <td>Fecha Nacimiento: *</td>
            <td><?php echo $this->Form->input('fecha_nacimiento', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Departamento: *</td>
            <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
            <td>Municipio: *</td>
            <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => '', 'empty' => 'Seleccione una Opción', 'label' => false,'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n: *</td>
            <td><?php echo $this->Form->input('direccion_residencia', array('type' => 'text', 'label' => false)); ?></td>
            <td>Tel&eacute;fono: *</td>
            <td><?php echo $this->Form->input('telefono_residencia', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Celular: *</td>
            <td><?php echo $this->Form->input('celular_persona', array('type' => 'text', 'label' => false)); ?></td>
            <td>E-mail: *</td>
            <td><?php echo $this->Form->input('email_persona', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td colspan="4"><h2>Datos Ingreso</h2></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center"><b>La constraseña inicial del usuario ser&aacute; el No. Documento</b></td>
        </tr>
        <tr>
            <td>Usuario: *</td>
            <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'required'=>true)); ?></td>
            <td>Rol: *</td>
            <td><?php echo $this->Form->input('rol_id', array('type' => 'select', 'options' => $roles, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>Empresa: *</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>Sucursal: *</td>
            <td colspan="3"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false, 'disabled'=>'disabled', 'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>Asociado: *</td>
            <td colspan="3"><?php echo $this->Form->input('asociado_id', array('type' => 'select', 'options' => $asociados, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
        </tr>
        <tr>
            <td>Parametro Precio de Plantillas:</td>
            <td><?php echo $this->Form->input('parametro_precio', array('type' => 'select', 'options' => $parametro_precio, 'label' => false)); //31052018 ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">¿Puede el usuario cambiar de sucursal cuando esta realizando el pedido? <?php echo $this->Form->input('cambiar_sucursal', array('type' => 'checkbox', 'label' => false,'div'=>false)); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Usuario', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('password', array('type' => 'hidden'));
        echo $this->Form->input('fecha_registro', array('type' => 'hidden', 'value' => date('Y-m-d')));
        echo $this->Form->input('fecha_ultimo_ingreso', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
        echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>