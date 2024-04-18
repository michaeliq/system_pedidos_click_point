<div class="bodegas view">
<h2><?php  __('Bodega');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bodega['Bodega']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre Bodega'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bodega['Bodega']['nombre_bodega']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Capacidad Bodega'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bodega['Bodega']['capacidad_bodega']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Municipio'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($bodega['Municipio']['id'], array('controller' => 'municipios', 'action' => 'view', $bodega['Municipio']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado Bodega'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bodega['Bodega']['estado_bodega']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Bodega', true), array('action' => 'edit', $bodega['Bodega']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Bodega', true), array('action' => 'delete', $bodega['Bodega']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $bodega['Bodega']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bodegas', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bodega', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Municipios', true), array('controller' => 'municipios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Municipio', true), array('controller' => 'municipios', 'action' => 'add')); ?> </li>
	</ul>
</div>
