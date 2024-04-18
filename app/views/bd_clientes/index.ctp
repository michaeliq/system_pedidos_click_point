<div class="bdClientes index">
	<h2><span class="glyphicon glyphicon-hdd"></span> Base de Datos Clientes</h2>
        <?php // echo $this->Html->link(__('New Bd Cliente', true), array('action' => 'add')); ?>
	<table class="table table-striped table-bordered table-hover table-condensed">
	<tr>
			<th><?php echo $this->Paginator->sort('Razon Social','bd_razon_social');?></th>
			<th><?php echo $this->Paginator->sort('NIT','bd_identificacion');?></th>
			<th><?php echo $this->Paginator->sort('Sigla','bd_sigla');?></th>
			<th><?php echo $this->Paginator->sort('Matricula','bd_matricula');?></th>
			<th><?php echo $this->Paginator->sort('Fecha Matricula','bd_fecha_matricula');?></th>
			<th><?php echo $this->Paginator->sort('bd_fecha_renovacion');?></th>
			<th><?php echo $this->Paginator->sort('bd_direccion');?></th>
			<th><?php echo $this->Paginator->sort('bd_telefonos');?></th>
			<th><?php echo $this->Paginator->sort('bd_zona_postal');?></th>
			<th><?php echo $this->Paginator->sort('bd_nombre_municipio');?></th>
			<th><?php echo $this->Paginator->sort('bd_pagina_web');?></th>
			<th><?php echo $this->Paginator->sort('bd_fax');?></th>
			<th><?php echo $this->Paginator->sort('bd_apartado_aereo');?></th>
			<th><?php echo $this->Paginator->sort('bd_organizacion_juridica');?></th>
			<th><?php echo $this->Paginator->sort('bd_categoria');?></th>
			<th><?php echo $this->Paginator->sort('bd_representante_legal');?></th>
			<th><?php echo $this->Paginator->sort('bd_codigo_ciiu_1');?></th>
			<th><?php echo $this->Paginator->sort('bd_descripcion_1');?></th>
			<th><?php echo $this->Paginator->sort('bd_codigo_ciiu_2');?></th>
			<th><?php echo $this->Paginator->sort('bd_descripcion_2');?></th>
			<th><?php echo $this->Paginator->sort('bd_codigo_ciiu_3');?></th>
			<th><?php echo $this->Paginator->sort('bd_descripcion_3');?></th>
			<th><?php echo $this->Paginator->sort('bd_codigo_ciiu_4');?></th>
			<th><?php echo $this->Paginator->sort('bd_descripcion_4');?></th>
			<th><?php echo $this->Paginator->sort('bd_activo_corriente');?></th>
			<th><?php echo $this->Paginator->sort('bd_activo_fijo');?></th>
			<th><?php echo $this->Paginator->sort('bd_otros_activos');?></th>
			<th><?php echo $this->Paginator->sort('bd_valorizaciones');?></th>
			<th><?php echo $this->Paginator->sort('bd_total_activos');?></th>
			<th><?php echo $this->Paginator->sort('bd_con_ajustes_por_inflacion');?></th>
			<th><?php echo $this->Paginator->sort('bd_total_activos_sin_ajustes_por_inflacion');?></th>
			<th><?php echo $this->Paginator->sort('bd_pasivo_corriente');?></th>
			<th><?php echo $this->Paginator->sort('bd_pasivo_largo_plazo');?></th>
			<th><?php echo $this->Paginator->sort('bd_total_pasivo');?></th>
			<th><?php echo $this->Paginator->sort('bd_patrimonio');?></th>
			<th><?php echo $this->Paginator->sort('bd_total_pasivo_patrimonio');?></th>
			<th><?php echo $this->Paginator->sort('bd_ventas_netas');?></th>
			<th><?php echo $this->Paginator->sort('bd_costo_ventas');?></th>
			<th><?php echo $this->Paginator->sort('bd_utilidad_perdida_neta');?></th>
			<th><?php echo $this->Paginator->sort('bd_utilidad_perdida_operacional');?></th>
			<th><?php echo $this->Paginator->sort('bd_personal_ocupado');?></th>
			<th><?php echo $this->Paginator->sort('bd_cantidad_establecimientos');?></th>
			<th><?php echo $this->Paginator->sort('bd_capital_autorizado');?></th>
			<th><?php echo $this->Paginator->sort('bd_capital_suscrito');?></th>
			<th><?php echo $this->Paginator->sort('bd_capital_pagado');?></th>
			<th><?php echo $this->Paginator->sort('bd_inscripcion_proponentes');?></th>
			<th><?php echo $this->Paginator->sort('bd_fecha_inscripcion_pro');?></th>
			<th><?php echo $this->Paginator->sort('bd_fecha_renovacion_pro');?></th>
			<th><?php echo $this->Paginator->sort('bd_capacidad_contratacion_constructor');?></th>
			<th><?php echo $this->Paginator->sort('bd_capacidad_contratacion_consultor');?></th>
			<th><?php echo $this->Paginator->sort('bd_capacidad_contratacion_proveedor');?></th>
			<th><?php echo $this->Paginator->sort('bd_descripcion_multas_y_sanciones');?></th>
			<th><?php echo $this->Paginator->sort('bd_valor_multas_y_sanciones');?></th>
			<th><?php echo $this->Paginator->sort('bd_vendedor');?></th>
			<th><?php echo $this->Paginator->sort('bd_gestion');?></th>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($bdClientes as $bdCliente):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $bdCliente['BdCliente']['bd_razon_social']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_identificacion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_sigla']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_matricula']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_fecha_matricula']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_fecha_renovacion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_direccion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_telefonos']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_zona_postal']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_nombre_municipio']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_pagina_web']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_fax']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_apartado_aereo']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_organizacion_juridica']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_categoria']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_representante_legal']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_codigo_ciiu_1']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_descripcion_1']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_codigo_ciiu_2']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_descripcion_2']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_codigo_ciiu_3']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_descripcion_3']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_codigo_ciiu_4']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_descripcion_4']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_activo_corriente']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_activo_fijo']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_otros_activos']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_valorizaciones']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_total_activos']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_con_ajustes_por_inflacion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_total_activos_sin_ajustes_por_inflacion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_pasivo_corriente']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_pasivo_largo_plazo']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_total_pasivo']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_patrimonio']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_total_pasivo_patrimonio']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_ventas_netas']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_costo_ventas']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_utilidad_perdida_neta']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_utilidad_perdida_operacional']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_personal_ocupado']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_cantidad_establecimientos']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capital_autorizado']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capital_suscrito']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capital_pagado']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_inscripcion_proponentes']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_fecha_inscripcion_pro']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_fecha_renovacion_pro']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capacidad_contratacion_constructor']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capacidad_contratacion_consultor']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_capacidad_contratacion_proveedor']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_descripcion_multas_y_sanciones']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_valor_multas_y_sanciones']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_vendedor']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['bd_gestion']; ?>&nbsp;</td>
		<td><?php echo $bdCliente['BdCliente']['id']; ?>&nbsp;</td>
		<td class="actions">
			<?php // echo $this->Html->link(__('Ver', true), array('action' => 'view', $bdCliente['BdCliente']['id'])); ?>
			<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $bdCliente['BdCliente']['id'])); ?>
			<?php // echo $this->Html->link(__('Delete', true), array('action' => 'delete', $bdCliente['BdCliente']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $bdCliente['BdCliente']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
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
</div>