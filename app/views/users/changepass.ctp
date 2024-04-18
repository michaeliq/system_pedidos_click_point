<?php echo $this->Html->script(array('users/users_add')); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend><?php __('Cambiar Contraseña'); ?></legend>
    <table class="table  table-bordered table-condensed">
        <tr>
            <th>Usuario:</th>
            <td><?php echo $this->Form->input('username', array('label' => false)); ?></td>
        </tr>
        <tr>
            <th>Contraseña Actual:</th>
            <td><?php echo $this->Form->input('password_old', array('label' => false, 'type' => 'password')); ?></td>
        </tr>
        <tr>
            <th>Nueva Contraseña:</th>
            <td><?php echo $this->Form->input('password1', array('label' => false, 'type' => 'password')); ?></td>
        </tr>
        <tr>
            <th>Repetir Contraseña:</th>
            <td><?php echo $this->Form->input('password2', array('label' => false, 'type' => 'password')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <?php echo $this->Form->input('id', array('label' => '', 'type' => 'hidden', 'value' => $id)); ?>
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Cambiar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>