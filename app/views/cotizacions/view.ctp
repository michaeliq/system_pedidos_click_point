<div class="cotizacions view">
<h2><?php  __('Cotizacion');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Listado Llamada'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cotizacion['ListadoLlamada']['id'], array('controller' => 'listado_llamadas', 'action' => 'view', $cotizacion['ListadoLlamada']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado Pedido'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cotizacion['EstadoPedido']['id'], array('controller' => 'estado_pedidos', 'action' => 'view', $cotizacion['EstadoPedido']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cotizacion['User']['id'], array('controller' => 'users', 'action' => 'view', $cotizacion['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['observaciones']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Cotizacion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['fecha_cotizacion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo Pedido'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($cotizacion['TipoPedido']['id'], array('controller' => 'tipo_pedidos', 'action' => 'view', $cotizacion['TipoPedido']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Departamento Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['departamento_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Municipio Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['municipio_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cotizacion Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['cotizacion_email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cotizacion Direccion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['cotizacion_direccion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cotizacion Telefono'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['cotizacion_telefono']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cotizacion Estado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['cotizacion_estado']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cotizacion Envio Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cotizacion['Cotizacion']['cotizacion_envio_email']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cotizacion', true), array('action' => 'edit', $cotizacion['Cotizacion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Cotizacion', true), array('action' => 'delete', $cotizacion['Cotizacion']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cotizacion['Cotizacion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cotizacions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cotizacion', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Listado Llamadas', true), array('controller' => 'listado_llamadas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Listado Llamada', true), array('controller' => 'listado_llamadas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estado Pedidos', true), array('controller' => 'estado_pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estado Pedido', true), array('controller' => 'estado_pedidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Pedidos', true), array('controller' => 'tipo_pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Pedido', true), array('controller' => 'tipo_pedidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
