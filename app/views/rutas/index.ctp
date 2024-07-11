<h2><span class="glyphicon glyphicon-map-marker"></span> Rutas</h2>
<div class="add row">
    <div class="col-md-2">
        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Ruta', true), array('action' => 'add'), array('escape' => false)); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-circle-arrow-up"></i> Subida Masiva', true), array('action' => 'upload_file_routes'), array('escape' => false)); ?>
    </div>
</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Código SIRBE', 'codigo_sirbe'); ?></th>
            <th><?php echo $this->Paginator->sort('Ruta', 'nombre'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr>

        <?php foreach ($rutas as $ruta) : ?>
            <tr>
                <td><?= $ruta["Ruta"]["codigo_sirbe"] ?></td>
                <td><?= $ruta["Ruta"]["nombre"] ?></td>
                <td class="actions">
                    <div class="view" title="Ver">
                        <?php echo $this->Html->link(__('', true), array('action' => 'view', $ruta['Ruta']['ruta_id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?>
                    </div>
                    <div class="edit" title="Editar">
                        <?php echo $this->Html->link(__('', true), array('action' => 'edit', $ruta['Ruta']['ruta_id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?>
                    </div>
                    <div class="delete" title="Borrar">
                        <?php echo $this->Html->link(__('', true), array('action' => 'delete', $ruta['Ruta']['ruta_id']), array('class' => 'glyphicon glyphicon-remove', 'escape' => false)); ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php ?>