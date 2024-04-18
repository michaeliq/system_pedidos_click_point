<?php

echo $this->Html->script(array('sucursales/sucursales_add')); ?>
<script>
$(function(){
    $('#regresar_presupuesto').click(function(){
	history.back();
    });
});
</script>
<h2><span class="glyphicon glyphicon-usd"></span> PRESUPUESTO SUCURSAL</h2>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Sucursal</th>
            <th>Tipo de Pedido</th>
            <th>Presupuesto Asignado</th>
            <th>Presupuesto Utilizado</th>
            <th>Fecha Asignación</th>
            <th>Acciones</th>
        </tr>
        <?php 
        $empresa = null;
        foreach ($presupuestos as $presupuesto) :
            $empresa = $presupuesto['Sucursale']['id_empresa'];
        ?>        
        <tr>
            <td><?php echo $presupuesto['Empresa']['nombre_empresa']; ?><br> - <?php echo $presupuesto['Sucursale']['nombre_sucursal']; ?></td>
            <td><?php echo $presupuesto['TipoPedido']['nombre_tipo_pedido']; ?></td>
            <td>$ <?php echo number_format($presupuesto['SucursalesPresupuestosPedido']['presupuesto_asignado'],0,",","."); ?></td>
            <td>$ <?php echo number_format($presupuesto['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",","."); ?></td>
            <td><?php echo $presupuesto['SucursalesPresupuestosPedido']['fecha_presupuesto_pedido']; ?></td>
            <td><div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'presupuesto_edit', $presupuesto['SucursalesPresupuestosPedido']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div></td>
        </tr>
            <?php
        endforeach;
    ?>
    </table>
            <?php echo $this->Form->create('SucursalesPresupuestosPedido', array('url' => array('controller' => 'sucursales', 'action' => 'presupuesto/'.$sucursal_id))); ?>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Tipo de Pedido</th>
            <th>Presupuesto Asignado</th>
        </tr>
        <tr>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td><?php echo $this->Form->input('presupuesto_asignado', array('type' => 'text', 'label' => false, 'maxlength' => '12')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_presupuesto', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar ', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php echo $this->Form->input('sucursal_id', array('type' => 'hidden', 'value' => $sucursal_id));?>
        <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa));?>
    </table>
        <?php echo $this->Form->end(); ?>

</div>