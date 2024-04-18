<h2><span class="glyphicon glyphicon-book"></span> INFORME % AVANCE PEDIDOS</h2>

<?php echo $this->Form->create('Empresa', array('url' => array('controller' => 'informes', 'action' => 'info_avance_pedidos'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa: *</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Seleccione'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Año: *</td>
            <td><?php echo $this->Form->input('vendedor_id', array('type' => 'select', 'options' => $anio, 'empty' => array('0'=>'Seleccione'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Mes: *</td>
            <td><?php echo $this->Form->input('parametro_precio', array('type' => 'select', 'options' => $mes, 'empty' => array('0'=>'Seleccione'), 'label' => false)); ?></td>
        </tr>
    </table>
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<?php if(count($data)>0){ ?>
<table class="table-striped table-bordered table-condensed" align="center">
    <tr>
        <th>Año</th>
        <th>Mes</th>
        <th>Empresa</th>
        <th>Total Pedidos Mes</th>
        <th>Tipo Pedido</th>
        <th>Sucursales Activas</th>
        <th>Sucursales Inactivas</th>
        <th>Porcentaje</th>
        <th>Sucursales Sin Pedidos</th>
    </tr>
    <?php foreach ($data as $value) { ?>
    <tr>
        <td><?php echo $value[0]['anio'];?></td>
        <td><?php echo $value[0]['mes'];?></td>
        <td><?php echo $value[0]['nombre_empresa'];?></td>
        <td><?php echo $value[0]['total_pedidos'];?></td>
        <td><?php echo $value[0]['nombre_tipo_pedido'];?></td>
        <td><?php echo $value[0]['sucursales_activas'];?></td>
        <td><?php echo $value[0]['sucursales_inactivas'];?></td>
        <td><?php echo $value[0]['porcentaje'];?> %</td>
        <td><?php echo $value[0]['sucursales_sin_pedidos'];?></td>
    </tr>

    <?php } ?>
</table>
<?php } ?>