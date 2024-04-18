<div class="cotizacions form">
<?php echo $this->Form->create('Cotizacion');?>
	<fieldset>
		<legend><?php __('Add Cotizacion'); ?></legend>
	<?php
		echo $this->Form->input('listado_llamada_id');
		echo $this->Form->input('cotizacion_estado_pedido');
		echo $this->Form->input('user_id');
		echo $this->Form->input('observaciones');
		echo $this->Form->input('fecha_cotizacion');
		echo $this->Form->input('tipo_pedido_id');
		echo $this->Form->input('departamento_id');
		echo $this->Form->input('municipio_id');
		echo $this->Form->input('cotizacion_email');
		echo $this->Form->input('cotizacion_direccion');
		echo $this->Form->input('cotizacion_telefono');
		echo $this->Form->input('cotizacion_estado');
		echo $this->Form->input('cotizacion_envio_email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Cotizacions', true), array('action' => 'index'));?></li>
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