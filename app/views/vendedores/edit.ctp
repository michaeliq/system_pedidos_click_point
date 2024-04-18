<?php

echo $this->Form->create('Vendedore'); ?>
<fieldset>
    <legend><?php __('Nuevo Vendedor'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Nombres y Apellidos: *</b></td>
            <td><?php echo $this->Form->input('nombre_vendedor', array('type' => 'text', 'label' => false, 'maxlength' => '120')); ?></td>
        </tr>
        <tr>
            <td><b>No. Identificación: *</b></td>
            <td><?php echo $this->Form->input('no_identificacion', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
        </tr>
        <tr>
            <td><b>Dirección Residencia: </b></td>
            <td><?php echo $this->Form->input('direccion_vendedor', array('type' => 'text', 'label' => false, 'maxlength' => '120')); ?></td>
        </tr>
        <tr>
            <td><b>No. Celular: </b></td>
            <td><?php echo $this->Form->input('telefono_vendedor', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
        </tr>
        <tr>
            <td><b>E-mail: </b></td>
            <td><?php echo $this->Form->input('correo_vendedor', array('type' => 'text', 'label' => false, 'maxlength' => '120')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Vendedor', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('estado_vendedor', array('type' => 'hidden', 'value' => true));
                echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>