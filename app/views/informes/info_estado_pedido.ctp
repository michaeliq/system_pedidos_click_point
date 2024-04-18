<?php

ini_set('max_execution_time','300');
ini_set('memory_limit', '912M'); 


// Crear archivo plano csv
    $file_name = 'informes/InformeEstadoPedidos_'.date('Y_m_d').'.csv';
    $file = fopen($file_name, 'w');

echo $this->Html->script(array('informes/informes_general')); 
?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME ESTADOS DE PEDIDOS</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_estado_pedido'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa:</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Regional:</td>
            <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options'=>'', 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Sucursal:</td>
            <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => '', 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>     
            <td>Fecha Inicio: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?><!--<span style="font-size: 10px; color: red;"> Fecha Aprobado</span>--></td>
            <td>Fecha Corte: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?><!--<span style="font-size: 10px; color: red;"> Fecha Aprobado</span>--></td>
        </tr>
        <tr>
            <td>Estado Orden: </td>                
            <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<table class="table table-hover">
    <tr>
        <th>No. Orden</th>
        <th>Sucursal - Regional</th>
        <th>Ciudad Destino</th>
        <th>Fecha Despacho</th>
        <th>Fecha Probable Entrega</th>
        <th>Fecha Entrega</th>
        <th>Guia Despacho</th>
        <th>Estado</th>
    </tr>
    <?php
    $data_csv = utf8_decode("No. Orden;Sucursal-Regional;CiudadDestino;FechaDespacho;FechaEntregaDesde;FechaEntregaHasta;FechaEntrega;GuiaDespacho;Estado\n");
    fwrite($file, $data_csv);   
    foreach ($pedidos as $pedido):
    ?>
    <tr>
        <td>#000<?php echo $pedido['Pedido']['id']; ?></td>
        <td><?php echo $pedido['Sucursale']['regional_sucursal'].' - '.$pedido['Sucursale']['nombre_sucursal']; ?></td>
        <td><?php echo $pedido['Municipio2']['nombre_municipio']; ?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_despacho'],0,10);; ?></td>
        <td><b>Desde: </b><?php echo $pedido['Pedido']['fecha_entrega_1']; ?><br><b>Hasta:  </b><?php echo $pedido['Pedido']['fecha_entrega_2']; ?></td>
        <td><?php echo substr($pedido['Pedido']['fecha_entregado'],0,10); ?></td>
        <td><?php echo $pedido['Pedido']['guia_despacho']; ?></td>
        <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?></td>
    </tr>
    <?php
        $data_csv = utf8_decode('#000'. $pedido['Pedido']['id']). ';' .
            utf8_decode($pedido['Sucursale']['regional_sucursal'].' - '.$pedido['Sucursale']['nombre_sucursal']). ';' .
            utf8_decode($pedido['Municipio2']['nombre_municipio']) . ';' .
            utf8_decode(substr($pedido['Pedido']['fecha_despacho'],0,10)) . ';' .
            utf8_decode($pedido['Pedido']['fecha_entrega_1']) . ';' .
            utf8_decode($pedido['Pedido']['fecha_entrega_2']) . ';' .
            utf8_decode(substr($pedido['Pedido']['fecha_entregado'],0,10)) . ';' .
            utf8_decode($pedido['Pedido']['guia_despacho']) . ';' .
            utf8_decode($pedido['EstadoPedido']['nombre_estado']);
        fwrite($file, $data_csv);
        fwrite($file, chr(13) . chr(10));
    endforeach;
    fclose($file);

    ?>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>