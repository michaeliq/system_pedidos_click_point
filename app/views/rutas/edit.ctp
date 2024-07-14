<?php echo $this->Html->script(array('rutas/municipios_por_departamento')); ?>
<?php echo $this->Form->create('Ruta'); ?>
<fieldset>
    <legend><?php __('Modificar Ruta ' . $ruta["Ruta"]["nombre"]); ?></legend>
    <?php echo $this->Form->hidden('ruta_id', array('value' => $ruta["Ruta"]["ruta_id"])); ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <td>Ruta: </td>
            <td><?php echo $this->Form->input('nombre', array('type' => 'text', 'label' => false, 'value' => $ruta["Ruta"]["nombre"])); ?></td>
            <td>Código SIRBE: </td>
            <td><?php echo $this->Form->input('codigo_sirbe', array('type' => 'text', 'label' => false, 'value' => $ruta["Ruta"]["codigo_sirbe"])); ?></td>
        </tr>
        <td>Departamento: </td>
        <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'option' => $departamentos, 'label' => false, 'default' => $ruta["Ruta"]["departamento_id"])); ?></td>
        <td>Municipio: </td>
        <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'option' => $municipios, 'label' => false, 'default' => $ruta["Ruta"]["municipio_id"])); ?></td>
    </table>
    <div class="row">
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?>
        </div>
        <div class="col-md-6" align="center">
            <?php echo $this->Form->button('Guardar', array('type' => 'submit', 'class' => 'btn btn-info')); ?>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>