<div class="bdClientes form">
<?php echo $this->Form->create('BdCliente');?>
	<fieldset>
		<legend><?php __('Edit Bd Cliente'); ?></legend>
	<?php
		echo $this->Form->input('bd_razon_social');
		echo $this->Form->input('bd_identificacion');
		echo $this->Form->input('bd_sigla');
		echo $this->Form->input('bd_matricula');
		echo $this->Form->input('bd_fecha_matricula');
		echo $this->Form->input('bd_fecha_renovacion');
		echo $this->Form->input('bd_direccion');
		echo $this->Form->input('bd_telefonos');
		echo $this->Form->input('bd_zona_postal');
		echo $this->Form->input('bd_nombre_municipio');
		echo $this->Form->input('bd_pagina_web');
		echo $this->Form->input('bd_fax');
		echo $this->Form->input('bd_apartado_aereo');
		echo $this->Form->input('bd_organizacion_juridica');
		echo $this->Form->input('bd_categoria');
		echo $this->Form->input('bd_representante_legal');
		echo $this->Form->input('bd_codigo_ciiu_1');
		echo $this->Form->input('bd_descripcion_1');
		echo $this->Form->input('bd_codigo_ciiu_2');
		echo $this->Form->input('bd_descripcion_2');
		echo $this->Form->input('bd_codigo_ciiu_3');
		echo $this->Form->input('bd_descripcion_3');
		echo $this->Form->input('bd_codigo_ciiu_4');
		echo $this->Form->input('bd_descripcion_4');
		echo $this->Form->input('bd_activo_corriente');
		echo $this->Form->input('bd_activo_fijo');
		echo $this->Form->input('bd_otros_activos');
		echo $this->Form->input('bd_valorizaciones');
		echo $this->Form->input('bd_total_activos');
		echo $this->Form->input('bd_con_ajustes_por_inflacion');
		echo $this->Form->input('bd_total_activos_sin_ajustes_por_inflacion');
		echo $this->Form->input('bd_pasivo_corriente');
		echo $this->Form->input('bd_pasivo_largo_plazo');
		echo $this->Form->input('bd_total_pasivo');
		echo $this->Form->input('bd_patrimonio');
		echo $this->Form->input('bd_total_pasivo_patrimonio');
		echo $this->Form->input('bd_ventas_netas');
		echo $this->Form->input('bd_costo_ventas');
		echo $this->Form->input('bd_utilidad_perdida_neta');
		echo $this->Form->input('bd_utilidad_perdida_operacional');
		echo $this->Form->input('bd_personal_ocupado');
		echo $this->Form->input('bd_cantidad_establecimientos');
		echo $this->Form->input('bd_capital_autorizado');
		echo $this->Form->input('bd_capital_suscrito');
		echo $this->Form->input('bd_capital_pagado');
		echo $this->Form->input('bd_inscripcion_proponentes');
		echo $this->Form->input('bd_fecha_inscripcion_pro');
		echo $this->Form->input('bd_fecha_renovacion_pro');
		echo $this->Form->input('bd_capacidad_contratacion_constructor');
		echo $this->Form->input('bd_capacidad_contratacion_consultor');
		echo $this->Form->input('bd_capacidad_contratacion_proveedor');
		echo $this->Form->input('bd_descripcion_multas_y_sanciones');
		echo $this->Form->input('bd_valor_multas_y_sanciones');
		echo $this->Form->input('bd_vendedor');
		echo $this->Form->input('bd_gestion');
		echo $this->Form->input('id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('BdCliente.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('BdCliente.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Bd Clientes', true), array('action' => 'index'));?></li>
	</ul>
</div>