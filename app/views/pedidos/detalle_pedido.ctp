<?php

if ($this->Session->read('Pedido.clasificacion_pedido') == 'Facturacion sobre pedido') {
    echo $this->Html->script(array('pedidos/pedidos_detalle_2_1.js?var=' . date('dymhmis')));
} else {
    echo $this->Html->script(array('pedidos/pedidos_detalle_2.js?var=' . date('dymhmis')));
}


echo $this->Html->script(array('plantillas/plantillas_search.js?var=' . date('dymhmis')));
/*
echo $this->Session->read('Pedido.pedido_id'); echo "<br>";    
echo $this->Session->read('User.sucursal_id'); echo "<br>";  
echo $this->Session->read('Pedido.tipo_pedido_id'); 
print_r($productos_sucursal);*/
// print_r($pedidos);
// print_r($detalles);

//05032018
$categorias = null;
$presupuesto_disponible = 'red';
foreach ($tipo_categorias as $key => $tipo_categoria) {
    $categorias = $tipo_categoria . ', ' . $categorias;
}
?>
<style>
    .presupuestoAlerta {
        color: red;
    }
</style>
<h2>ORDEN DE PEDIDO <span style='color:red;'>#000<?php echo $this->Session->read('Pedido.pedido_id'); ?></span></h2>
<div><b>Empresa:</b> <?php echo $presupuestos_info['0']['Empresa']['nombre_empresa']; ?> - <b>Contrato:</b> <?php echo $presupuestos_info['0']['Empresa']['contrato_empresa']; ?></div>
<div><b>Sucursal:</b> <?php echo $presupuestos_info['0']['Sucursale']['nombre_sucursal']; ?> (OI: <?php echo $presupuestos_info['0']['Sucursale']['oi_sucursal']; ?>) - <b>Auxiliares:</b> <?php echo $presupuestos_info['0']['Sucursale']['numero_auxiliares']; ?></div>
<div><b>Regional:</b> <?php echo $presupuestos_info['0']['Sucursale']['v_regional_sucursal']; ?></div>
<div><b>Tipo de Pedido:</b> <?php echo $pedidos['0']['TipoPedido']['nombre_tipo_pedido']; ?> - <?php echo $this->Session->read('Pedido.clasificacion_pedido'); ?></div>
<div><b>Plantilla:</b> <?php echo $plantilla['0']['Plantilla']['nombre_plantilla']; ?>(<?php echo $plantilla['0']['Plantilla']['id']; ?>) <?php if (!$plantilla['0']['Plantilla']['estado_plantilla']) echo "<span style='color: red'><b><h4>La plantilla se encuentra inactiva</h4></b></span>"; ?></div>
<div><b>Presupuesto Asignado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'], 0, ",", ".");  ?></div>
<?php if ($parametro_presupuesto_iva) { // BBVA 
?>
    <div><b>Presupuesto Utilizado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'] + $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_iva'], 2, ",", ".");  ?> (CON IVA)</div>
<?php } else { ?>
    <div><b>Presupuesto Utilizado: </b>$ <?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'], 0, ",", ".");  ?> (Sin IVA)</div>
<?php } ?>

<div><b>Categoria: </b> <?php echo $categorias; //05032018 
                        ?></div>
<?php if (!empty($this->Session->read('Pedido.fecha_entrega_1'))) { ?>
    <div><b>Fechas de Entrega:</b> Desde el <b><?php echo $this->Session->read('Pedido.fecha_entrega_1'); ?></b> hasta el <b><?php echo $this->Session->read('Pedido.fecha_entrega_2');  ?></b></div>
<?php } ?>

<?php if ($parametro_presupuesto_iva) { // BBVA 
?>
    <div style="color: <?php echo $presupuesto_disponible; ?> "><b>Presupuesto Disponible: </b>$ <span id="tag_presupuesto_diponible"><?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'] - ($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'] + $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_iva']), 2, ",", ".");  ?></span></div>
<?php } else { ?>
    <div id="id_presupuesto_disponible" style="color: <?php echo $presupuesto_disponible; ?> "><b>Presupuesto Disponible: </b>$ <span id="tag_presupuesto_diponible"><?php echo number_format($presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado'] - $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado'], 0, ",", ".");  ?></span></div>
<?php } ?>
<div><b>Acceso rápido administrador:</b>
    <?php if ($this->Session->read('Auth.User.rol_id') == '1') {
        echo  $this->Html->link(__('Empresa', true), array('action' => '../empresas/edit/',  $this->Session->read('Pedido.empresa_id')), array('target' => '_blank', 'class' => 'glyphicon glyphicon-asterisk', 'escape' => true));
        echo "&nbsp;&nbsp;";
        echo  $this->Html->link(__('Sucursal', true), array('action' => '../sucursales/edit/', $this->Session->read('Pedido.sucursal_id')), array('target' => '_blank', 'class' => 'glyphicon glyphicon-asterisk', 'escape' => false));
        echo "&nbsp;&nbsp;";
        echo  $this->Html->link(__('Plantilla', true), array('action' => '../plantillas/edit/', $plantilla['0']['Plantilla']['id']), array('target' => '_blank', 'class' => 'glyphicon glyphicon-asterisk', 'escape' => false));
    }
    ?>
</div>
<?php if (count($detalles) > 0) { ?>
    <div title="Cambiar Observaciones"> <b>Observaciones: </b> <?php echo  $detalles[0]['Pedido']['observaciones']; ?> <?php echo $this->Html->link(__('', true), array('action' => 'observaciones', $detalles[0]['Pedido']['id']), array('target' => '_blank', 'class' => 'glyphicon glyphicon-list-alt', 'escape' => false)); ?></div>
<?php } ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'detalle_pedido'))); ?>
<table width="100%" id="productos_plantilla" class="table-striped table-bordered table-hover table-condensed" align="center">
    <tr>
        <th style="width: 10%;">C&oacute;digo Producto</th>
        <th style="width: 25%;">Producto</th>
        <th style="width: 10%;">Precio Centro Aseo N. Nacional</th>
        <th style="width: 10%;">Precio Centro Aseo Bog/Sab</th>
        <th style="width: 5%;">IVA</th>
        <th style="width: 5%;">Medida</th>
        <th style="width: 5%;">Cantidad</th>
        <th style="width: 15%;">Vencimiento</th>
        <th style="width: 5%;">Lote</th>
        <th style="width: 10%;">Total</th>
        <!--        <th title="Incluir todos">Incluir <input name="Todos" type="checkbox" value="1" class="check_todos"/></th>-->
    </tr>
    <tr>
        <th><input type="text" id="codigo_producto_sh" onkeyup="search_codigo_producto()" size="10" maxlength="20" placeholder="Código ..." title="Digite un código de producto"></th>
        <th colspan="8"><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Producto ..." title="Digite un nombre de producto"></th>
    </tr>
    <?php
    echo $this->Form->input('presupuesto_asignado', array('type' => 'hidden', 'value' =>  $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_asignado']));
    echo $this->Form->input('presupuesto_utilizado', array('type' => 'hidden', 'value' =>  $presupuestos_info['0']['SucursalesPresupuestosPedido']['presupuesto_utilizado']));
    echo $this->Form->input('presupuesto_pedido', array('type' => 'hidden', 'value' =>  '0'));
    foreach ($productos as $producto) {
        foreach ($productos_sucursal as $producto_sucursal) {
            if ($producto['Producto']['id'] == $producto_sucursal['PlantillasDetalle']['producto_id']) {
                $precio_producto = $producto_sucursal['PlantillasDetalle']['precio_producto_2'];
                $precio_producto_bs = $producto_sucursal['PlantillasDetalle']['precio_producto_bs_2'];
            }
        }

        $precio_usar = '';
        $precio_usar_bs = '';
        if ($municipio_bs['Municipio']['municipio_bogota_sabana']) {
            $precio_final_producto = $precio_producto_bs;
            $precio_usar_bs = 'font-weight: bold;';
        } else {
            $precio_final_producto = $precio_producto;
            $precio_usar = 'font-weight: bold;';
        }

        $total_producto = 0;
        $cantidad_pedido = 0;
        foreach ($detalles as $detalle) {
            if ($detalle['PedidosDetalle']['producto_id'] == $producto['Producto']['id']) {
                $cantidad_pedido = $detalle['PedidosDetalle']['cantidad_pedido'];
                $total_producto = $detalle['PedidosDetalle']['cantidad_pedido'] * $precio_final_producto;
            }
        }

    ?>
        <tr class="class_<?php echo $producto['Producto']['tipo_categoria_id']; ?>">
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><span class="glyphicon glyphicon-tags" title=" <?php echo $producto['Producto']['especificacion_tecnica']; ?>"></span>&nbsp;&nbsp;<span class="glyphicon glyphicon-info-sign" title=" <?php echo $producto['Producto']['presentacion_producto']; ?>"></span>&nbsp;&nbsp;<?php echo $producto['Producto']['nombre_producto'] . ' | ' . $producto['Producto']['marca_producto']; ?></td>
            <td style="<?php echo $precio_usar; ?>"><?php echo '$ ' . number_format($precio_producto, 0, ",", ".");   ?></td>
            <td style="<?php echo $precio_usar_bs; ?>"><?php echo '$ ' . number_format($precio_producto_bs, 0, ",", ".");  ?></td>
            <td><?php echo $producto['Producto']['iva_producto'];  ?></td>
            <td><?php echo $producto['Producto']['medida_producto'];  ?></td>
            <td><?php echo $this->Form->input('cantidad_pedido_' . $producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '5', 'maxlength' => '5', 'value' => $cantidad_pedido, 'onchange' => 'total_producto(' . $producto['Producto']['id'] . ')')); ?></td>
            <td>
                <?php
                
                echo $this->Form->input("fecha_expiracion_" . $producto['Producto']['id'], array('type' => 'text', 'label' => false, 'class' => 'fecha_vencimiento', "placeholder" => "Ingresa una fecha valida"))
                ?>
            </td>
            <td><?php echo $this->Form->input("lote_" . $producto['Producto']['id'], array('type' => 'text', 'label' => false)) ?></td>
            <td>
                <div id="total_producto_<?php echo $producto['Producto']['id'] ?>">$ <?php echo $total_producto; ?></div>
            </td>
            <?php
            echo $this->Form->input('producto_id_' . $producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['id']));
            echo $this->Form->input('precio_producto_' . $producto['Producto']['id'], array('type' => 'hidden', 'value' => $precio_final_producto));
            echo $this->Form->input('iva_producto_' . $producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['iva_producto']));
            ?>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td colspan="6">&nbsp;</td>
        <td colspan="4"><?php echo $this->Form->input('pedido_entrega_parcial', array('type' => 'checkbox', 'label' => '<span class="glyphicon glyphicon-save" title="Guardar Sin Terminar" style="color:#47a447;"></span> <b>Guardar Sin Terminar</b>', 'checked' => false)); ?></td>
    </tr>
    <tr>
        <td colspan="10" class="text-center">
            <?php
            if (count($detalles) > 0) {
                if (count($presupuestos) == 0) {
                    $disabled = 'disabled';
                } else {
                    $disabled = '';
                }
            } else {
                $disabled = 'disabled';
                if ($this->Session->read('Pedido.clasificacion_pedido') == 'Facturacion sobre pedido') {
                    $disabled = '';
                }
            }
            ?>

            <?php echo $this->Form->button('Cancelar Pedido', array('type' => 'button', 'class' => 'btn btn-danger  btn-xs', 'onclick' => 'cancelar_pedido(' . $this->Session->read('Pedido.pedido_id') . ');')); ?>
            <?php echo $this->Form->button('Terminar Pedido', array('type' => 'submit', 'id' => 'id_terminar_pedido', 'class' => 'btn btn-primary btn-xs', 'disabled' => $disabled, /* 'onclick' => 'terminar_pedido(' . $this->Session->read('Pedido.pedido_id') . ');'*/)); ?>
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>