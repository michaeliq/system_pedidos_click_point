<div class="cronogramasInventarios view">
<h2><?php  __('Cronogramas Inventario');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre Cronograma'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['nombre_cronograma']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Inicio'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['fecha_inicio']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Fin'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['fecha_fin']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo Categoria'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cronogramasInventario['TipoCategoria']['id'], array('controller' => 'tipo_categorias', 'action' => 'view', $cronogramasInventario['TipoCategoria']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Bodega'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cronogramasInventario['Bodega']['id'], array('controller' => 'bodegas', 'action' => 'view', $cronogramasInventario['Bodega']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado Cronograma'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['estado_cronograma']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Detalle Cronograma'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['detalle_cronograma']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Registro'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cronogramasInventario['CronogramasInventario']['fecha_registro']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cronogramas Inventario', true), array('action' => 'edit', $cronogramasInventario['CronogramasInventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Cronogramas Inventario', true), array('action' => 'delete', $cronogramasInventario['CronogramasInventario']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cronogramasInventario['CronogramasInventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cronogramas Inventarios', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cronogramas Inventario', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Categorias', true), array('controller' => 'tipo_categorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Categoria', true), array('controller' => 'tipo_categorias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Bodegas', true), array('controller' => 'bodegas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bodega', true), array('controller' => 'bodegas', 'action' => 'add')); ?> </li>
	</ul>
</div>
