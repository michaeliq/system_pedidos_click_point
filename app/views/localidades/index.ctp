<h2><span class="glyphicon glyphicon-map-marker"></span> Localidades</h2>
<div class="add row">
    <div class="col-md-2">
        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Localidad', true), array('action' => 'add'), array('escape' => false)); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-circle-arrow-up"></i> Subida Masiva', true), array('action' => 'upload_file_locations'), array('escape' => false)); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-circle-arrow-up"></i> Vincular Localidad - Rutas', true), array('action' => 'add_location_routes_relations'), array('escape' => false)); ?>
    </div>
</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('ID', 'localidad_id'); ?></th>
            <th><?php echo $this->Paginator->sort("Localidad", 'nombre_localidad'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr>

        <?php foreach ($localidades as $localidad) : ?>
            <tr>
                <td><?= $localidad["Localidad"]["localidad_id"] ?></td>
                <td><?= $localidad["Localidad"]["nombre_localidad"] ?></td>
                <td class="actions">
                    <div class="edit" title="Editar">
                        <?php echo $this->Html->link(__('', true), array('action' => 'edit', $localidad["Localidad"]['localidad_id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?>
                    </div>
                    <div class="delete" title="Borrar">
                        <?php echo $this->Html->link(__('', true), array('action' => 'delete', $localidad["Localidad"]['localidad_id']), array('class' => 'glyphicon glyphicon-remove', 'escape' => false)); ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?>	
</p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | 	<?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
</div>
<?php ?>