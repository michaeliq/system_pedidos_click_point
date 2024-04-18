<?php ?>
<?php ?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME INVENTARIOS DETALLE PRODUCTO</h2>
<div class="index">
    <?php /* echo $this->Form->create('MovimientosProducto', array('url' => array('controller' => 'informes', 'action' => 'inv_general'))); ?>
    <fieldset>
        <legend><?php __('Filtro del Informe'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Productos:</td>
                <td colspan="3"><?php echo $this->Form->input('producto_id', array('type' => 'select', 'options' => $productos, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            </tr>
        </table>        
    </fieldset>
    <div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    </div>
    <div>&nbsp;</div>
<?php echo $this->Form->end(); */ ?>
    <div style="text-align: right;">
        <a name="arriba"></a>
        <a href="#abajo">Ir a la parte de abajo</a>
    </div>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>No.</th>
            <th>Producto</th>
            <th>Movimiento</th>
            <th>Tercero</th>
            <th>Tipo Movimiento</th>
            <th>Cantidad</th>
            <th>Fecha Movimiento</th>
            <th>Bodega</th>
        </tr>
<?php
    $i = 0;
    $existencia  = 0;
    foreach ($movimientos as $movimiento):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        if($movimiento['tipo_movimiento_id'] == 'E'){
            $existencia = $existencia + $movimiento['cantidad'];    
            $tipo_movimiento = 'arrow-down';
            $color = '#f6931f;';
            $label_movimiento = "No. Movimiento: ";
            $link = "<a href='../../movimientosEntradas/view/".$movimiento['movimiento']."' target='_blank'>#000".$movimiento['movimiento']."</a>";
        }else{
            $existencia = $existencia - $movimiento['cantidad'];    
            $tipo_movimiento = 'arrow-up';
            $color = '#0a0;';
            $label_movimiento = "No. Orden: ";
            $link = "<a href='../../pedidos/pedido_pdf/".$movimiento['movimiento']."' target='_blank'>#000".$movimiento['movimiento']."</a>";
        }
        
?>
        <tr <?php echo $class; ?>  title="<?php echo $movimiento['tipo_movimiento']; ?>" >
            <td><?php echo $i; ?></td>
            <td><?php echo $movimiento['producto_completo']; ?> </td>
            <td><?php echo $label_movimiento.'<b>'.$link.'</b>'; ?></td>
            <td><?php echo $movimiento['empresa_proveedor']; ?></td>
            <td><?php echo $movimiento['tipo_movimiento_id']; ?> <span style="color: <?php echo $color; ?> " class="glyphicon glyphicon-<?php echo $tipo_movimiento; ?>"></span></td>
            <td class="text-center"><?php echo $movimiento['cantidad']; ?></td>
            <td><?php echo $movimiento['fecha_registro']; ?></td>
            <td>Bodega</td>
        </tr>    
<?php endforeach; ?>
        <tr>
            <td colspan="5" class="text-right"><a name="abajo"></a><b>EXISTENCIA EN BODEGA:</b></td>
            <td class="text-center"><b><?php echo $existencia; ?></b></td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    <div style="text-align: right;">
        <a href="#arriba">Ir a la parte de arriba</a>
        
    </div>
</div>