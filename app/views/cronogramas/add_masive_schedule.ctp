<?php
echo $this->Html->script(array('cronogramas/cronogramas_add')); ?>
<?php echo $this->Form->create('Cronograma'); ?>
<fieldset>
    <legend><?php __('Nuevo Cronograma Masivo'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombre Cronograma: *</td>
            <td><?php echo $this->Form->input('nombre_cronograma', array('type' => 'text', 'label' => false, 'maxlength' => '60')); ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Empresa: *</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select','multiple'=>'multiple', 'options' => $empresas, 'label' => false, 'required' => true, 'style' => "height:250px;")); ?></td>
            <td>Tipo Pedido: *</td>
            <td><?php echo $this->Form->input('tipo_pedido_id_2', array('type' => 'select', 'multiple'=>'multiple', 'options' => $tipo_pedido, /*'empty' => 'Seleccione una Opción',*/'title'=>'Para seleccionar varios, presione la tecla Control y con el puntero del mouse, seleccione los elementos.', 'label' => false)); ?></td>

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
        echo $this->Form->input('estado_cronograma', array('type' => 'hidden', 'value' => true));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>