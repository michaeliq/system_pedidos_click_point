<div class="listadoLlamadas view">
<h2><?php  __('Listado Llamada');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $listadoLlamada['ListadoLlamada']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Bd Cliente'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($listadoLlamada['BdCliente']['id'], array('controller' => 'bd_clientes', 'action' => 'view', $listadoLlamada['BdCliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado Llamada'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $listadoLlamada['ListadoLlamada']['estado_llamada']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Registro'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $listadoLlamada['ListadoLlamada']['fecha_registro']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($listadoLlamada['User']['id'], array('controller' => 'users', 'action' => 'view', $listadoLlamada['User']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Listado Llamada', true), array('action' => 'edit', $listadoLlamada['ListadoLlamada']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Listado Llamada', true), array('action' => 'delete', $listadoLlamada['ListadoLlamada']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $listadoLlamada['ListadoLlamada']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Listado Llamadas', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Listado Llamada', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Bd Clientes', true), array('controller' => 'bd_clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bd Cliente', true), array('controller' => 'bd_clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
