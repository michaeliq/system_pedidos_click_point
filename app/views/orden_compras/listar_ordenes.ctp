<?php ?>
<style>
    .orden_compra, .listar_ordenes,.aprobar_ordenes, .ver_orden, .cambiar_estado{
        display: none;
    }
</style>
<h2><span class="glyphicon glyphicon-filter"></span> ORDENES DE COMPRA - CONSULTAR ORDENES DE COMPRA</h2>
<?php echo $this->Form->create('OrdenCompra'); ?>
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
<div class="orden_compra"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-tags "></i> Nueva Orden de Compra', true), array('action' => 'orden_compra'), array('escape' => false)); ?> | <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-check"></i> Aprobar Ordenes de Compra', true), array('action' => 'aprobar_ordenes'), array('escape' => false)); ?></div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Orden Compra</th>
        <th>Proveedor</th>
        <th>Fecha Orden</th>
        <th>Fecha Aprobado</th>
        <th>Fecha Inventario</th>
        <th>Tipo Orden</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
     if (count($ordenes) > 0) {
        foreach ($ordenes as $orden):
        if($orden['TipoEstadoOrden']['id'] < '3')
            $tipo_orden = 'rojo.png';
        
        if($orden['TipoEstadoOrden']['id'] == '3' || $orden['TipoEstadoOrden']['id'] == '4' || $orden['TipoEstadoOrden']['id'] == '5')
            $tipo_orden = 'amarillo.png';
        
        if($orden['TipoEstadoOrden']['id'] > '5')
            $tipo_orden = 'verde.png';
     ?>
    <tr>
        <td><span style='color:blue;'><b>#000<?php echo $orden['OrdenCompra']['id']; ?></b></span></td>
        <td><?php echo $orden['Proveedore']['nombre_proveedor']; ?></td>
        <?php /* <td style="font-size: 10px;" ><b>Solicitud:</b><br><?php echo substr($orden['OrdenCompra']['fecha_orden_compra'], 0, 10); ?><br><b>Aprobado:</b><br><?php echo substr($orden['OrdenCompra']['fecha_aprobado'], 0, 10); ?><br><b>Entrada Inventario:</b><br><?php echo substr($orden['OrdenCompra']['fecha_entrada_inventario'], 0, 10); ?></td> */?>
        <td><?php echo substr($orden['OrdenCompra']['fecha_orden_compra'], 0, 10); ?></td>
        <td><?php echo substr($orden['OrdenCompra']['fecha_aprobado'], 0, 10); ?></td>
        <td><?php echo substr($orden['OrdenCompra']['fecha_entrada_inventario'], 0, 10); ?></td>
        <td><?php echo $orden['TipoOrden']['nombre_tipo_orden']; ?></td>
        <td><?php echo $html->image($tipo_orden); ?>&nbsp;<?php echo $orden['TipoEstadoOrden']['nombre_estado_orden']; ?></td>
        <td>
        <?php 
        if ($orden['OrdenCompra']['tipo_estado_orden_id']== '1') {
        ?>
            <div class="detalle_compra" title="Continuar Orden"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_compra', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
        <?php
        }else{
        ?>
            <div class="ver_orden" title="Ver Orden de Compra"><?php echo $this->Html->link(__('', true), array('action' => 'ver_orden', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>

        <?php }?>
	<?php 
        if ($orden['OrdenCompra']['tipo_estado_orden_id'] == '4') {
        ?>
            <div class="cambiar_estado" title="Cambiar estado a orden: En Proceso"><?php echo $this->Html->link(__('', true), array('action' => 'cambiar_estado', $orden['OrdenCompra']['id']), array('class' => 'glyphicon glyphicon-repeat', 'escape' => false)); ?></div>
        <?php
        }
        ?>
        </td>
    </tr>
    <?php
        endforeach;
    }else {
        ?>
    <tr>
        <td colspan="8" class="text-center">No existen ordenes de compra en el sistema.</td>
    </tr>
        <?php
    }
    ?>
</table>
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