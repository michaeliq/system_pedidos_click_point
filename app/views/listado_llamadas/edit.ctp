<div class="listadoLlamadas form">
<?php echo $this->Form->create('ListadoLlamada');?>
	<fieldset>
		<legend><?php __('Edit Listado Llamada'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('bd_cliente_id');
		echo $this->Form->input('estado_llamada');
		echo $this->Form->input('fecha_registro');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ListadoLlamada.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ListadoLlamada.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Listado Llamadas', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Bd Clientes', true), array('controller' => 'bd_clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bd Cliente', true), array('controller' => 'bd_clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>