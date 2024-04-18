<h2><?php __('Bodegas');?></h2>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Bodega', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="bodegas index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Bodega','nombre_bodega');?></th>
            <th><?php echo $this->Paginator->sort('Capacidad','capacidad_bodega');?></th>
            <th><?php echo $this->Paginator->sort('Ubicación','municipio_id');?></th>
            <th><?php echo $this->Paginator->sort('Estado','estado_bodega');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($bodegas as $bodega):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><?php echo $bodega['Bodega']['nombre_bodega']; ?></td>
            <td><?php echo $bodega['Bodega']['capacidad_bodega']; ?></td>
            <td><?php echo $bodega['Municipio']['nombre_municipio']; ?></td>
            <td><?php
                    if ($bodega['Bodega']['estado_bodega']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?></td>
            <td class="actions">
                <div class="view" title="Ver"><?php // echo $this->Html->link(__('', true), array('action' => 'view', $bodega['Bodega']['id']),array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $bodega['Bodega']['id']),array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ( $bodega['Bodega']['estado_bodega']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $bodega['Bodega']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la bodega %s?', true), $bodega['Bodega']['nombre_bodega']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $bodega['Bodega']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la bodega %s?', true), $bodega['Bodega']['nombre_bodega']));
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