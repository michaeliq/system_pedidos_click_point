<?php

echo $this->Html->script(array('users/users_add.js?cache=1')); 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->Form->create('User'); ?>
<table class="table table-striped table-bordered table-condensed" align="center" style="width: 60%">
    <tr>
        <td colspan="2"><h2>Reestablecer Contraseña</h2></td>
    </tr>
    <tr>
        <td colspan="4" class="text-center"><b>Por favor digite la contraseña que desea usar para ingresar:</b></td>
    </tr>
    <tr>
        <td><b>Usuario: *</b></td>
        <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'required'=>true,'placeholder'=>'Nombre de usuario')); ?></td>
    </tr>
    <tr>
        <td><b>Empresa: *</b></td>
        <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false, 'required'=>true)); ?></td>
    </tr>
    <tr>
        <td><b>Nueva Contraseña: *</b></td>
        <td><?php echo $this->Form->input('password2', array('type' => 'password', 'size' => '30', 'maxlength' => '20', 'label' => false, 'placeholder'=>'Nueva Contraseña')); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="display: none;"><?php echo $this->Form->input('query', array('type' => 'textarea', 'label' => false, 'placeholder'=>'Nueva Contrase�a')); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;"><?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
            <?php echo $this->Form->button('Reestablecer Contraseña', array('type' => 'submit', 'class' => 'btn btn-success')); ?></td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>