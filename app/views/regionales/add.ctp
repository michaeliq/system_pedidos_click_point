<?php

echo $this->Form->create('Regionale'); ?>
<fieldset>
    <legend><?php __('Nueva Regional'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Regional: *</b></td>
            <td><?php echo $this->Form->input('nombre_regional', array('type' => 'text', 'label' => false, 'maxlength' => '200','size'=>'100')); ?></td>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Regional', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $this->Session->read('Regional.empresa_id')));
        echo $this->Form->input('estado_regional', array('type' => 'hidden', 'value' => true));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>