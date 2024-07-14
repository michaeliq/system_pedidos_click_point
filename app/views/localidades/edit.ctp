<?php echo $this->Form->create('Localidades'); ?>
<fieldset>
    <legend><?php __('Actualizar Localidad'); ?></legend>
    <?php echo $this->Form->hidden('localidad_id', array('value' => $localidad["Localidad"]["localidad_id"])); ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <td>Localidad: </td>
            <td><?php echo $this->Form->input('nombre_localidad', array('type' => 'text', 'label' => false, 'value' => $localidad["Localidad"]["nombre_localidad"])); ?></td>
        </tr>
    </table>
    <div class="row">
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?>
        </div>
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Actualizar Localidad', array('type' => 'submit', 'class' => 'btn btn-info')); ?>
        </div>
    </div>
    <div>&nbsp;</div>
</fieldset>
<?php echo $this->Form->end(); ?>