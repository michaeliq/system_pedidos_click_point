<h2><span class="glyphicon glyphicon-transfer"></span> <?php __('Tipos de Movimiento');?></h2>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Tipo Movimiento', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="tipoMovimientos index">	
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Tipo Movimiento','nombre_tipo_movimiento');?></th>
            <th><?php echo $this->Paginator->sort('Desde','flujo_inicial');?></th>
            <th><?php echo $this->Paginator->sort('Hacia','flujo_final');?></th>
            <th><?php echo $this->Paginator->sort('Descripción','descripcion_tipo_movimiento');?></th>
            <th><?php echo $this->Paginator->sort('Tipo','tipo_movimiento');?></th>
            <th><?php echo $this->Paginator->sort('Estado','estado_tipo_movimiento');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($tipoMovimientos as $tipoMovimiento):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><?php echo $tipoMovimiento['TipoMovimiento']['nombre_tipo_movimiento']; ?></td>
            <td><?php echo $tipoMovimiento['TipoMovimiento']['flujo_inicial']; ?></td>
            <td><?php echo $tipoMovimiento['TipoMovimiento']['flujo_final']; ?></td>
            <td><?php echo $tipoMovimiento['TipoMovimiento']['descripcion_tipo_movimiento']; ?></td>
            <td><?php echo $tipoMovimiento['TipoMovimiento']['tipo_movimiento']; ?></td>
            <td><?php
                    if ($tipoMovimiento['TipoMovimiento']['estado_tipo_movimiento']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td class="actions">
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $tipoMovimiento['TipoMovimiento']['id']),array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ($tipoMovimiento['TipoMovimiento']['estado_tipo_movimiento']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $tipoMovimiento['TipoMovimiento']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el proveedor %s?', true), $tipoMovimiento['TipoMovimiento']['nombre_tipo_movimiento']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $tipoMovimiento['TipoMovimiento']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el proveedor %s?', true), $tipoMovimiento['TipoMovimiento']['nombre_tipo_movimiento']));
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
