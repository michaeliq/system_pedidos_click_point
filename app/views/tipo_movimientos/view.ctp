<div class="tipoMovimientos view">
<h2><?php  __('Tipo Movimiento');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre Tipo Movimiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['nombre_tipo_movimiento']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Flujo Inicial'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['flujo_inicial']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Flujo Final'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['flujo_final']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Descripcion Tipo Movimiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['descripcion_tipo_movimiento']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo Movimiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['tipo_movimiento']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado Tipo Movimiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMovimiento['TipoMovimiento']['estado_tipo_movimiento']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tipo Movimiento', true), array('action' => 'edit', $tipoMovimiento['TipoMovimiento']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Tipo Movimiento', true), array('action' => 'delete', $tipoMovimiento['TipoMovimiento']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tipoMovimiento['TipoMovimiento']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Movimientos', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Movimiento', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
