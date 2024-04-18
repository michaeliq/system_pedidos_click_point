<h2><?php __('Cronogramas Inventarios');?></h2>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Crongrama', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="cronogramasInventarios index">

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Cronograma','nombre_cronograma');?></th>
            <th><?php echo $this->Paginator->sort('Inicio','fecha_inicio');?></th>
            <th><?php echo $this->Paginator->sort('Fin','fecha_fin');?></th>
            <th><?php echo $this->Paginator->sort('Categorias','tipo_categoria_id');?></th>
            <th><?php echo $this->Paginator->sort('Bodega','bodega_id');?></th>
            <th><?php echo $this->Paginator->sort('Detalle','detalle_cronograma');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($cronogramasInventarios as $cronogramasInventario):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><?php echo $cronogramasInventario['CronogramasInventario']['nombre_cronograma']; ?></td>
            <td><?php echo $cronogramasInventario['CronogramasInventario']['fecha_inicio']; ?></td>
            <td><?php echo $cronogramasInventario['CronogramasInventario']['fecha_fin']; ?></td>
            <td><?php 
                    foreach ($tipoCategorias as $key => $value) {
                        if(in_array($key,json_decode($cronogramasInventario['CronogramasInventario']['tipo_categoria_id']))){
                            echo $value; 
                            echo "<br>";
                        }
                    }
            // print_r(json_decode($cronogramasInventario['CronogramasInventario']['tipo_categoria_id'])); ?></td>
            <td><?php echo $cronogramasInventario['Bodega']['nombre_bodega']; ?></td>
            <td><?php echo $cronogramasInventario['CronogramasInventario']['detalle_cronograma']; ?></td>
            <td class="actions">
                <div class="view" title="Ver"><?php // echo $this->Html->link(__('', true), array('action' => 'view', $cronogramasInventario['CronogramasInventario']['id']),array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $cronogramasInventario['CronogramasInventario']['id']),array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ($cronogramasInventario['CronogramasInventario']['estado_cronograma']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $cronogramasInventario['CronogramasInventario']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la bodega %s?', true), $cronogramasInventario['CronogramasInventario']['nombre_cronograma']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $cronogramasInventario['CronogramasInventario']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la bodega %s?', true), $cronogramasInventario['CronogramasInventario']['nombre_cronograma']));
                        }
                        ?>
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
    ?>	</p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | 	<?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
</div>