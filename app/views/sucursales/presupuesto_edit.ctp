<?php

echo $this->Form->create('SucursalesPresupuestosPedido', array('url' => array('controller' => 'sucursales', 'action' => 'presupuesto_edit/'.$id))); ?>
<fieldset>
    <legend><?php __('Editar Presupuesto para ' . strtoupper($presupuestos['0']['Empresa']['nombre_empresa'].' - '.$presupuestos['0']['Sucursale']['nombre_sucursal'])); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Tipo de Pedido: *</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>            
            <td>Presupuesto Asignado: *</td>
            <td><?php echo $this->Form->input('presupuesto_asignado', array('type' => 'text', 'label' => false, 'maxlength' => '12')); ?></td>
        </tr>
        <tr>
            <td>Presupuesto Utilizado:</td>
            <td>$ <?php echo number_format($presupuestos['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",","."); ?></td>
        </tr>
        <tr>
            <td colspan="6" class="text-center">
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Presupuesto', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>

        <?php
        echo $this->Form->input('fecha_presupuesto_pedido', array('type' => 'hidden','value'=>date('Y-m-d h:i:s')));
        echo $this->Form->input('id', array('type' => 'hidden'));
        echo $this->Form->input('sucursal_id', array('type' => 'hidden'));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>

<script>
    $(function () {
        $('#regresar_edit').click(function () {
            if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
                window.location = "../presupuesto/" + $('#SucursalesPresupuestosPedidoSucursalId').val();
            }
        });
    });
</script>