<?php

echo $this->Html->script(array('cronogramas/cronogramas_add')); ?>
<?php echo $this->Form->create('Cronograma'); ?>
<fieldset>
    <legend><?php __('Editar Cronograma'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombre Cronograma: *</td>
            <td><?php echo $this->Form->input('nombre_cronograma', array('type' => 'text', 'label' => false, 'maxlength' => '60')); ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Empresa: *</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>Tipo Pedido: *</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'hidden', 'label' => false)); ?>
                <?php echo $this->Form->input('tipo_pedido_id_2', array('type' => 'select', 'multiple'=>'multiple', 'size' => '6', 'options' => $tipo_pedido, 'selected' => $selected, 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Fecha Inicio: *</td>
            <td><?php echo $this->Form->input('fecha_inicio', array('type' => 'text', 'label' => false)); ?></td>
            <td>Fecha Fin: *</td>
            <td><?php echo $this->Form->input('fecha_fin', array('type' => 'text', 'label' => false)); ?></td>
        </tr>


        <tr>
            <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Cronograma', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>