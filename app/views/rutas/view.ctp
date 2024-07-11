<?php echo $this->Html->script(array('rutas/actualizar_sucursales_en_ruta')); ?>
<h2><?php echo $ruta["Ruta"]["nombre"]; ?> - <?php echo $ruta["Ruta"]["codigo_sirbe"]; ?></h2>
<fieldset>
    <?php echo $this->Form->input('ruta_id', array('type' => 'hidden', 'label' => false, 'value' => $ruta["Ruta"]["ruta_id"])); ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <th>Cod. SIRBE</th>
            <th>Ruta</th>
            <th>Departamento</th>
            <th>Municipio</th>
        </tr>
        <tr>
            <td><?php echo $ruta['Ruta']['codigo_sirbe']; ?></td>
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
    <table class="table table-striped table-bordered table-hover table-condensed" align="center">
        <tr>
            <th>Sucursal</th>
            <th>Empresa</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($sucursales as $sucursale) : ?>
            <tr>

                <td><?= $sucursale["Sucursale"]["nombre_sucursal"] ?></td>
                <td><?= $sucursale["Empresa"]["nombre_empresa"] ?></td>
                <td><?= $sucursale["Departamento"]["nombre_departamento"] ?></td>
                <td><?= $sucursale["Municipio"]["nombre_municipio"] ?></td>
                <td class="actions">
                <div id=<?= $sucursale["Sucursale"]["id"] ?> class="edit RutaSucursal" title="Vincular/Desvincular Sucursal">
                        <?php if($sucursale["Sucursale"]["ruta_id"]) { ?>
                            <a href="#" class="glyphicon glyphicon-remove"></a>
                        <?php } else { ?>
                            <a  href="#" class="glyphicon glyphicon-ok"></a>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</fieldset>