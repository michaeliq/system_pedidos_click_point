<?php echo $this->Html->script(array('rutas/actualizar_sucursales_en_ruta')); ?>
<h2>Ruta - <?php echo $ruta["Ruta"]["nombre"]; ?></h2>
<fieldset>
    <?php echo $this->Form->input('ruta_id', array('type' => 'hidden', 'label' => false, 'value' => $ruta["Ruta"]["ruta_id"])); ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <th>ID</th>
            <th>Ruta</th>
            <th>Departamento</th>
            <th>Municipio</th>
        </tr>
        <tr>
            <td><?php echo $ruta['Ruta']['ruta_id']; ?></td>
            <td><?php echo $ruta['Ruta']['nombre']; ?></td>
            <td><?php echo $departamento; ?></td>
            <td><?php echo $municipio; ?></td>
        </tr>
    </table>
    <div class="text-center">
        <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
        <?php echo $this->Html->link(__('Actualizar', true), array('action' => 'edit', $ruta['Ruta']['ruta_id']), array('class' => 'btn btn-info', 'escape' => false)); ?>
    </div>
    <div>
        <br>
    </div>
</fieldset>