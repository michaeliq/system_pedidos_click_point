<?php echo $this->Html->script(array('pedidos/list_ordenes')); ?>
<h2><span class="glyphicon glyphicon-list-alt"></span> APROBAR ORDENES PENDIENTES</h2>
<?php echo $this->Form->create('Pedido'); ?>

<table class="table table-condensed ">
    <tr>
        <td>Empresas:</td>
        <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select',/*'options' => $regional,*/ 'empty' => 'Todos', 'label' => false)); ?></td>
        <td>Sucursales:</td>
        <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', /*'options' => $sucursales,*/ 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>No. Orden:</td>
        <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td>Fecha Orden:</td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td></td>
        <td></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('Pedido', array('url' => array('controller' => 'pedidos', 'action' => 'aprobar_masivo'))); ?>
<?php
if (count($pedidos) > 0) {
?>
    <div class="text-center">
        <?php echo $this->Form->button('APROBAR ORDENES SELECCIONADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
    </div><br>
<?php
}
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th><input name="Todos" type="checkbox" value="1" class="check_todos" /></th>
        <th>No. Orden</th>
        <th>No. Consecutivo</th>
        <th>Datos Empresa</th>
        <th>Fecha Orden</th>
        <th>Direcci&oacute;n Envio</th>
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
    // print_r($pedidos);
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido) :
    ?>
            <tr>
                <td><?php echo $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => 'ck')); ?></td>
                <td><span style='color:red;'>#000<?php echo $pedido['Pedido']['id']; ?></span></td>
                <td><span style='color:red; font-weight: bold;'><?php echo $pedido['Pedido']['consecutivo']; ?></span></td>
                <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa']; ?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal']; ?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal']; ?></td>
                <td><?php echo $pedido['Pedido']['pedido_fecha'] . ' ' . $pedido['Pedido']['pedido_hora']; ?> </td>
                <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php echo $pedido['Municipio2']['nombre_municipio']; ?><br><?php echo $pedido['Sucursale']['direccion_sucursal']; ?> </td>
                <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
                <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
                <td>
                    <div class="ver_pedido" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                    <div title="Imprimir Reporte Shalom"><?php echo $this->Html->link(__('', true), array('controller' => 'pedidos', 'action' => 'pedido_pdf_shalom', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank', 'escape' => false)); ?></div>
                    <?php
                    if ($pedido['Pedido']['pedido_estado_pedido'] != '4') {
                    ?>
                        <div class="aprobar_pedido" title="Aprobar"><?php echo $this->Html->link(__('', true), array('action' => 'aprobar_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false)); ?></div>
                    <?php
                    }
                    ?>
                    <div class="observaciones" title="Cambiar Observaciones"><?php echo $this->Html->link(__('', true), array('action' => 'observaciones', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-list-alt', 'escape' => false)); ?></div>
                </td>
            </tr>
        <?php
        endforeach;
    } else {
        ?>
        <tr>
            <td colspan="8" class="text-center">No existen ordenes de pedido en el sistema.</td>
        </tr>
    <?php
    }
    ?>
</table>
<?php
if (count($pedidos) > 0) {
?>
    <div class="text-center">
        <?php echo $this->Form->button('APROBAR ORDENES SELECCIONADAS', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs')); ?>
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
    ?> </p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | <?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
</div>