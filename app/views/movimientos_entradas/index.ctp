<?php

/* ?>
<h2><span class="glyphicon glyphicon-tasks"></span> INVENTARIO DEL SISTEMA</h2>
<div class="text-center">
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = 'entradas';">
        <span class="glyphicon glyphicon-arrow-down"></span> Entradas
    </button>
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = 'salidas';">
        <span class="glyphicon glyphicon-arrow-up"></span> Salidas
    </button>
</div> */ ?>
<?php echo $this->Html->script(array('movimientos_entradas/movimientos_entradas')); ?>
<h2><?php __('Movimientos de Entrada');?></h2>
<?php echo $this->Form->create('MovimientosEntrada', array('url' => array('controller' => 'movimientos_entradas', 'action' => 'index/'))); ?>
<table class="table table-condensed ">
    <table class="table table-striped table-bordered table-condensed" align="center" style="width: 70%;">
        <tr>
            <td><b>Movimiento: *</b></td>
            <td><?php echo $this->Form->input('id', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '5','value'=>''));?></td>                
        </tr>
        <tr>
            <td><b>Orden de Compra:</b></td>
            <td><?php echo $this->Form->input('orden_compra_id', array('type' => 'select', 'options' => $ordenCompra, 'label' => false, 'empty'=>'Seleccione una Opción')); ?> </td>            
        </tr>
        <tr>
            <td><b>Tipo de Movimiento: *</b> <a id="tooltip_tipo_movimiento" title="...">(?)</a></td>
            <td><?php echo $this->Form->input('tipo_movimiento_id', array('type' => 'select', 'options' => $tipoMovimientos, 'label' => false, 'empty'=>'Seleccione una Opción')); ?> </td>            
        </tr>
        <tr>
            <td id="inv_proveedor_label"><b>Proveedor: *</b></td>
            <td id="inv_proveedor_value"><?php echo $this->Form->input('proveedor_id', array('type' => 'select', 'options' => $proveedores, 'label' => false, 'empty'=>'Seleccione una Opción')); ?></td>
            <td id="inv_empresa_label"><b>Empresa: *</b></td>
            <td id="inv_empresa_value"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => '', 'label' => false, 'empty'=>'Seleccione una Opción')); ?></td>
        </tr>
        <?php /*<tr>
            <td><b>Categoria: </b></td>
            <td><?php echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => '', 'label' => false, 'empty'=>'Seleccione una Opción')); ?>
                <?php //  echo $this->Form->radio('', $tipoCategorias, array('legend' => false,'separator' => '<br>')); ?></td>
        </tr> */ ?>
        <tr>
            <td><b>Bodega: </b></td>
            <td><?php echo $this->Form->input('bodega_id', array('type' => 'select', 'options' => $bodegas, 'label' => false)); ?></td>
        </tr>
        <tr>
            <td><b>Fecha Movimiento:</b></td>
            <td><?php echo $this->Form->input('fecha_movimiento', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10', 'readonly'=>true,'value'=>''));?></td>
        </tr>
    </table>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Movimiento Entrada', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="movimientosEntradas index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('Tipo Movimiento','tipo_movimiento_id');?></th>
            <th><?php echo $this->Paginator->sort('Proveedor/Empresa','proveedor_id');?></th>
            <!-- <th><?php // echo $this->Paginator->sort('fecha_movimiento');?></th>
            <th><?php // echo $this->Paginator->sort('tipo_categoria_id');?></th>
            <th><?php // echo $this->Paginator->sort('bodega_id');?></th> -->
            <th><?php echo $this->Paginator->sort('Datos Factura','factura_numero');?></th>
            <!-- <th><?php // echo $this->Paginator->sort('tipo_formas_pago_id');?></th>
            <th><?php //echo $this->Paginator->sort('factura_subtotal');?></th>
            <th><?php // echo $this->Paginator->sort('factura_iva');?></th>
            <th><?php // echo $this->Paginator->sort('factura_total');?></th>
            <th><?php // echo $this->Paginator->sort('factura_fecha_vencimiento');?></th>
            <th><?php // echo $this->Paginator->sort('fecha_registro_movimiento');?></th>
            <th><?php // echo $this->Paginator->sort('user_id');?></th>
            <th><?php // echo $this->Paginator->sort('estado_movimiento');?></th> -->
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($movimientosEntradas as $movimientosEntrada):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><span style="color: #0a0; font-weight: bold; " title="Movimiento de Entrada">#000<?php echo $movimientosEntrada['MovimientosEntrada']['id']; ?></span></td>
            <td><?php echo $movimientosEntrada['TipoMovimiento']['nombre_tipo_movimiento']; ?></td>
            <td><?php echo (!empty($movimientosEntrada['Proveedore']['nombre_proveedor'])?'<b>Proveedor:</b>':'<b>Empresa:</b>'); ?>
                 <?php echo $movimientosEntrada['Proveedore']['nombre_proveedor']; ?><?php echo $movimientosEntrada['Empresa']['nombre_empresa'];?><br>
                <b>Fecha:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['fecha_movimiento']; ?><br>
                <!-- <b>Categoría:</b> <?php // echo $movimientosEntrada['TipoCategoria']['tipo_categoria_descripcion']; ?><br>-->
                <b>Bodega:</b> <?php echo $movimientosEntrada['Bodega']['nombre_bodega']; ?><br>
                <span style="color: blue; font-weight: bold; " title="Orden de Compra"><?php echo (!empty($movimientosEntrada['MovimientosEntrada']['orden_compra_id']))?'<b>Orden de Compra:</b>&nbsp;#000'.$movimientosEntrada['MovimientosEntrada']['orden_compra_id']:''; ?> </span>
            </td>
            <td><b># Factura:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['factura_numero']; ?><br>
                <b>Forma Pago:</b> <?php echo $movimientosEntrada['TipoFormasPago']['nombre_forma_pago']; ?><br>
                <b>Subtotal:</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_subtotal'],2,",","."); ?> <br>
                <b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_iva'],2,",","."); ?> <br>
                <b>Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> $<?php echo number_format($movimientosEntrada['MovimientosEntrada']['factura_total'],2,",","."); ?> <br>
                <b>Vencimiento:</b> <?php echo $movimientosEntrada['MovimientosEntrada']['factura_fecha_vencimiento']; ?>
            </td>
            <td class="actions">
                <?php if($movimientosEntrada['MovimientosEntrada']['estado_movimiento']) { ?>
                <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Modificar Movimiento Entrada"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Activar Movimiento Entrada"><?php echo $this->Html->link(__('', true), array('action' => 'delete', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-repeat', 'escape' => false)); ?></div>
                <?php }else{ ?>
                <div class="edit" title="Terminar Entrada"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_movimiento', $movimientosEntrada['MovimientosEntrada']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
                <?php } ?>

			<?php // echo $this->Html->link(__('View', true), array('action' => 'view', $movimientosEntrada['MovimientosEntrada']['id'])); ?>
			<?php // echo $this->Html->link(__('Edit', true), array('action' => 'edit', $movimientosEntrada['MovimientosEntrada']['id'])); ?>
			<?php // echo $this->Html->link(__('Delete', true), array('action' => 'delete', $movimientosEntrada['MovimientosEntrada']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $movimientosEntrada['MovimientosEntrada']['id'])); ?>
            </td>
        </tr>
<?php endforeach; ?>
    </table>
</div>
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