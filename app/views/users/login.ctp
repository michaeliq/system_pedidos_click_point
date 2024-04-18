<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="text-center"><?php echo $this->Session->flash('auth'); ?></div>
<?php echo $this->Form->create('User'); ?>
<table class="table-striped table-bordered table-condensed" align="center">
    <tr>
        <th colspan="2">Ingresar al Sistema</th>
    </tr>
    <tr>
        <th>Usuario: <span class="required">*</span></th>
        <td><?php echo $this->Form->input('username', array('type' => 'text', 'size' => '30', 'maxlength' => '120', 'label' => false)); ?></td>
    </tr>
    <tr>
        <th>Contrase&ntilde;a: <span class="requerido">*</span></th>
        <td><?php echo $this->Form->input('password', array('type' => 'password', 'size' => '30', 'maxlength' => '50', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right"><?php echo $this->Form->button('<i class="icon-user icon-white"></i> Ingresar', array('type' => 'submit', 'class' => 'btn btn-success')); ?></td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>