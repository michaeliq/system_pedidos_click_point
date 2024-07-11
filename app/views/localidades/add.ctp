<?php echo $this->Form->create('Localidades'); ?>
<fieldset>
    <legend><?php __('Crear Localidad'); ?></legend>
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <td>Localidad: </td>
            <td><?php echo $this->Form->input('nombre_localidad', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Ruta: </td>
            <td><?php echo $this->Form->input('ruta_id', array('type' => 'select', 'option' => $rutas, 'label' => false)); ?></td>
        </tr>
    </table>
    <div class="row">
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?>
        </div>
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Registrar Localidad', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div>&nbsp;</div>
</fieldset>
<?php echo $this->Form->end(); ?>