<?php
echo $this->Html->script(array('orden_compra/orden_compra_aprobar')); ?>
<h2><span class="glyphicon glyphicon-check"></span> ORDENES DE COMPRA - APROBAR ORDENES DE COMPRA</h2>
<?php echo $this->Form->create('OrdenCompra1', array('url' => array('controller' => 'ordenCompras', 'action' => 'aprobar_ordenes'))); ?>
<table class="table table-condensed ">
    <tr>
        <td>Proveedor:</td>
        <td colspan="3"><?php echo $this->Form->input('proveedor_id', array('type' => 'select', 'options' => $proveedores, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>No. Orden Compra:</td>
        <td><?php echo $this->Form->input('id', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        <td>Fecha Orden:</td>
        <td><?php echo $this->Form->input('fecha_orden_compra', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Tipo Orden:</td>
        <td><?php echo $this->Form->input('tipo_orden_id', array('type' => 'select', 'options' => $tipoOrden, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td>Tipo Estado Orden:</td>
        <td><?php echo $this->Form->input('tipo_estado_orden_id', array('type' => 'select', 'options' => $tipoEstadoOrden, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('OrdenCompra', array('url' => array('controller' => 'ordenCompras', 'action' => 'aprobar_ordenes'))); ?>
<?php 
if (count($ordenes) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('APROBAR ORDENES DE COMPRA SELECCIONADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
</div>
<?php
}
?>
<div>&nbsp;</div>
<div class="orden_compra"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-tags"></i> Nueva Orden de Compra', true), array('action' => 'orden_compra'), array('escape' => false)); ?> | <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-filter"></i> Consultar Ordenes de Compra', true), array('action' => 'listar_ordenes'), array('escape' => false)); ?></div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th><input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        <th>Orden Compra</th>
        <th>Proveedor</th>
        <th>Fechas Orden</th>
        <th>Tipo Orden</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
     if (count($ordenes) > 0) {
        foreach ($ordenes as $orden):
            ?>
    <tr>
        <td><?php echo $this->Form->input($orden['OrdenCompra']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $orden['OrdenCompra']['id'], 'class' => 'ck')); ?></td>
        <td><span style='color:blue;'><b>#000<?php echo $orden['OrdenCompra']['id']; ?></b></span></td>
        <td><?php echo $orden['Proveedore']['nombre_proveedor']; ?></td>
        <td><?php echo $orden['OrdenCompra']['fecha_orden_compra']; ?></td>
        <td><?php echo $orden['TipoOrden']['nombre_tipo_orden']; ?></td>
        <td><?php echo $orden['TipoEstadoOrden']['nombre_estado_orden']; ?></td>
        <td>
        <?php 
        if ($orden['OrdenCompra']['tipo_estado_orden_id']== '1') {
        ?>
            <div title="Continuar Orden"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_compra', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
        <?php
        }else{
        ?>
            <div class="ver_orden" title="Ver Orden de Compra"><?php echo $this->Html->link(__('', true), array('action' => 'ver_orden', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
            <div class="ver_aprobar_orden" title="Aprobar Orden de Compra"><?php echo $this->Html->link(__('', true), array('action' => 'ver_aprobar_orden', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false)); ?></div>
        <?php }?>

        </td>
    </tr>
    <?php
        endforeach;
    }else {
        ?>
    <tr>
        <td colspan="7" class="text-center">No existen ordenes de compra para aprobar en el sistema.</td>
    </tr>
        <?php
    }
    ?>
</table>
<?php 
if (count($ordenes) > 0) {
    ?>
<div class="text-center">
        <?php echo $this->Form->button('APROBAR ORDENES DE COMPRA SELECCIONADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
</div>
<?php
}
?>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
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