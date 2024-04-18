<?php

echo $this->Html->script(array('movimientos_entradas/movimientos_entradas')); ?>
<div class="movimientosEntradas form">
<?php echo $this->Form->create('MovimientosEntrada');?>
    <fieldset>
        <legend><?php __('Movimientos de Entrada'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center" style="width: 70%;">
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
            <!-- 
            <tr>
                <td><b>Categoria: </b></td>
                <td><?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => '', 'label' => false, 'empty'=>'Seleccione una Opción')); ?>
                <?php //  echo $this->Form->radio('', $tipoCategorias, array('legend' => false,'separator' => '<br>')); ?></td>
            </tr>
            -->
            <tr>
                <td><b>Bodega: </b></td>
                <td><?php echo $this->Form->input('bodega_id', array('type' => 'select', 'options' => $bodegas, 'label' => false)); ?></td>
            </tr>
            <tr>
                <td><b>Fecha Movimiento:</b></td>
                <td><?php echo $this->Form->input('fecha_movimiento', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10', 'readonly'=>true,'value'=>date('Y-m-d')));?></td>
            </tr>
        </table>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td colspan="4"><h4>Información de Factura</h4></td>
            </tr>
            <tr>
                <td id="inv_no_factura_label"><b>Número Factura:</b></td>
                <td id="inv_no_factura_value"><?php echo $this->Form->input('factura_numero', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10')); ?></td>
                <td id="inv_no_pedido_label"><b>No. de Orden: </b></td>
                <td id="inv_no_pedido_value"><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '8')); ?><div id="sucursal"></div></td>
                <td><b>Forma de Pago:</b></td>
                <td><?php echo $this->Form->input('tipo_formas_pago_id', array('type' => 'select', 'options' => $tipoFormasPagos, 'label' => false, 'empty'=>'Seleccione una Opción')); ?>
            </tr>
            <tr>
                <td><b>Subtotal: </b></td>
                <td><?php echo $this->Form->input('factura_subtotal', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20','placeholder'=>'Ej: 69293.23','title'=>'Utilice el punto (0.00) como separador decimal.')); ?></td>
                <td><b>IVA: </b></td>
                <td><?php echo $this->Form->input('factura_iva', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20','placeholder'=>'Ej: 12452.23','title'=>'Utilice el punto (0.00) como separador decimal.')); ?></td>
            </tr>
            <tr>
                <td><b>Total: </b></td>
                <td><?php echo $this->Form->input('factura_total', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20','disable'=>true)); ?></td>
                <td><b>Fecha de Vencimiento:</b></td>
                <td><?php echo $this->Form->input('factura_fecha_vencimiento', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '10')); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Registrar', array('type' => 'submit', 'class' => 'btn btn-success','id'=>'registrarMovimiento')); ?>
                </td>
            </tr>
        </table>
	<?php
		echo $this->Form->input('tipo_categoria_id', array('type' => 'hidden', 'value' => '1'));
		echo $this->Form->input('fecha_registro_movimiento', array('type' => 'hidden', 'value' => 'now()'));
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' =>  $this->Session->read('Auth.User.id')));
		echo $this->Form->input('estado_movimiento', array('type' => 'hidden', 'value' => false));
	?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>
