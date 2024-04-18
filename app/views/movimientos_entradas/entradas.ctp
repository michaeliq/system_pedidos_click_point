<h2><?php __('Movimientos de Entrada');?></h2>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Movimiento Entrada', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="movimientosEntradas index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('Tipo Movimiento','tipo_movimiento_id');?></th>
            <th><?php echo $this->Paginator->sort('Proveedor','proveedor_id');?></th>
            <!-- <th><?php // echo $this->Paginator->sort('fecha_movimiento');?></th>
            <th><?php // echo $this->Paginator->sort('tipo_categoria_id');?></th>
            <th><?php // echo $this->Paginator->sort('bodega_id');?></th> -->
            <th><?php echo $this->Paginator->sort('Datos Factura','factura_numero');?></th>
            <!-- <th><?php // echo $this->Paginator->sort('tipo_formas_pago_id');?></th>
            <th><?php //echo $this->Paginator->sort('factura_subtotal');?></th>
            <th><?php // echo $this->Paginator->sort('factura_iva');?></th>
            <th><?php // echo $this->Paginator->sort('factura_total');?></th>
            <th><?php // echo $this->Paginator->sort('factura_fecha_vencimiento');?></th>
            <th><?php // echo $this->Paginator->sort('fecha_registro_movimiento');?></th>
            <th><?php // echo $this->Paginator->sort('user_id');?></th>
            <th><?php // echo $this->Paginator->sort('estado_movimiento');?></th> -->
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($movimientosEntradas as $movimientosEntrada):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td>#000<?php echo $movimientosEntrada['MovimientosEntrada']['id']; ?></td>
            <td><?php echo $movimientosEntrada['TipoMovimiento']['nombre_tipo_movimiento']; ?></td>
            <td><b>Proveedor:</b> <?php echo $movimientosEntrada['Proveedore']['nombre_proveedor']; ?><br>
                <b>Fecha:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['fecha_movimiento']; ?><br>
                <b>Categoría:</b> <?php echo $movimientosEntrada['TipoCategoria']['tipo_categoria_descripcion']; ?><br>
                <b>Bodega:</b> <?php echo $movimientosEntrada['Bodega']['nombre_bodega']; ?>
            </td>
            <td><b># Factura:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['factura_numero']; ?><br>
                <b>Forma Pago:</b> <?php echo $movimientosEntrada['TipoFormasPago']['id']; ?><br>
                <b>Subtotal:</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_subtotal'],0,",","."); ?> <br>
                <b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_iva'],0,",","."); ?> <br>
                <b>Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_total'],0,",","."); ?> <br>
                <b>Vencimiento:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['factura_fecha_vencimiento']; ?>
            </td>
            <td class="actions">
                <?php if($movimientosEntrada['MovimientosEntrada']['estado_movimiento']) { ?>
                    <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <?php }else{ ?>
                    <div class="edit" title="Terminar Entrada"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_movimiento', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
                <?php } ?>

			<?php // echo $this->Html->link(__('View', true), array('action' => 'view', $movimientosEntrada['MovimientosEntrada']['id'])); ?>
			<?php // echo $this->Html->link(__('Edit', true), array('action' => 'edit', $movimientosEntrada['MovimientosEntrada']['id'])); ?>
			<?php // echo $this->Html->link(__('Delete', true), array('action' => 'delete', $movimientosEntrada['MovimientosEntrada']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $movimientosEntrada['MovimientosEntrada']['id'])); ?>
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