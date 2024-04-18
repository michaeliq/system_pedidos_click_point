<div class="bdClientes form">
<?php echo $this->Form->create('BdCliente');?>
    <fieldset>
        <legend><?php __('Nuevo Cliente'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td><b>Razón Social:</b></td>
                <td><?php echo $this->Form->input('bd_razon_social', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '60')); ?></td>
            </tr>
            <tr>
                <td><b>Nit - Identificación:</b></td>
                <td><?php echo $this->Form->input('bd_identificacion', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '60')); ?></td>
            </tr>
            <tr>
                <td><b>Dirección:</b></td>
                <td><?php echo $this->Form->input('bd_direccion', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Teléfono:</b></td>
                <td><?php echo $this->Form->input('bd_telefonos', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '60')); ?></td>
            </tr>
            <tr>
                <td><b>E-mail:</b></td>
                <td><?php echo $this->Form->input('bd_email', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Nombre Contacto:</b></td>
                <td><?php echo $this->Form->input('bd_nombre_contacto', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Página Web:</b></td>
                <td><?php echo $this->Form->input('bd_pagina_web', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b>Representante Legal:</b></td>
                <td><?php echo $this->Form->input('bd_representante_legal', array('type' => 'text', 'label' => '', 'size' => '30', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Cliente', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
	<?php
		
		//echo $this->Form->input('bd_sigla');
		//echo $this->Form->input('bd_matricula');
		//echo $this->Form->input('bd_fecha_matricula');
		//echo $this->Form->input('bd_fecha_renovacion');
		//echo $this->Form->input('bd_zona_postal');
		//echo $this->Form->input('bd_nombre_municipio');
		// echo $this->Form->input('bd_fax');
		//echo $this->Form->input('bd_apartado_aereo');
		//echo $this->Form->input('bd_organizacion_juridica');
		//echo $this->Form->input('bd_categoria');
    		/*echo $this->Form->input('bd_codigo_ciiu_1');
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
		echo $this->Form->input('bd_gestion');*/
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>