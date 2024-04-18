<?php ?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME PLANTILLAS</h2>
<?php echo $this->Form->create('Plantilla', array('url' => array('controller' => 'informes', 'action' => 'info_plantillas'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombre plantilla: </td>
            <td colspan="3"><?php echo $this->Form->input('nombre_plantilla', array('type' => 'text', 'label' => false, 'size' => '30', 'maxlength' => '60','placeholder'=>'Nombre especifico de la plantilla.')); ?></td>
        </tr>
        <tr>
            <td>Empresa:</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Plantilla</th>
            <th>Tipo Pedido</th>
            <th>Detalle Plantilla</th>
            <th>Estado</th>
            <th>Detalle</th>
        </tr> <?php
$i = 0;
foreach ($plantillas as $plantilla):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    if($id == $plantilla['Plantilla']['id']){
        $class = 'danger';
    }else{
        $class = '';
    }
    $base = null;
    if($plantilla['Plantilla']['plantilla_base']){
        $base = '<i class="glyphicon glyphicon-star" style="color: #009900;"></i>';
    }
    ?>
        <tr class="<?php echo $class; ?>" <?php echo $class; ?>  >
            <td><?php echo $base; echo $plantilla['Plantilla']['nombre_plantilla']; ?></td>
            <td><?php echo $plantilla['TipoPedido']['nombre_tipo_pedido']; ?></td>
            <td><?php echo $plantilla['Plantilla']['detalle_plantilla']; ?></td>
            <td>
                    <?php 
                    if ($plantilla['Plantilla']['estado_plantilla']) {
                        echo $html->image('verde.png');
                        // echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        // echo " Inactivo";
                    }
                    ?>
            </td>
            <td><?php echo $this->Html->link(__('', true), array('action' => 'info_plantillas',base64_encode($plantilla['Plantilla']['id'])), array('class' => 'glyphicon glyphicon-th-list', 'escape' => false)); ?></td>
        </tr>    
        <?php endforeach; ?>
    </table>
</div>
<?php if(count($plantilla_detalles)>0){?>
<div style="text-align: center;"><b>Cantidad de Productos <?php echo count($plantilla_detalles); ?> - <?php echo $plantilla_detalles[0]['Plantilla']['nombre_plantilla']; ?></b></div>
<table class="table-striped table-bordered table-hover table-condensed"align="center">
    <tr>
        <th>C&oacute;digo Producto</th>
        <th>Producto</th>
        <th>Proveedor</th>
        <th>Categor&iacute;a</th>
		<?php if($this->Session->read('Auth.User.parametro_precio') =='0'){ //31052018 ?>
        <th>Precio CISE</th>
        <th>Precio LISTA 2</th>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='1'){ ?>
        <th colspan="2">Precio CISE</th>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='2'){ ?>
        <th colspan="2">Precio LISTA 2</th>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='3'){ ?>
        <th colspan="2">Precio Base</th>
		<?php } ?>
        <th>IVA</th>
        <th>Medida</th>
    </tr>
        <?php
        foreach ($plantilla_detalles as $plantilla_detalle) {
        ?>
    <tr>
        <td><?php echo $plantilla_detalle['Producto']['codigo_producto']; ?></td>
        <td><?php echo $plantilla_detalle['Producto']['nombre_producto']; ?></td>
        <td>
                <?php 
                    if ($plantilla_detalle['Producto']['proveedor_producto']) {
                        echo " CISE";
                    } else {
                        echo " Otro";
                    }
                ?>
        </td>
        <td><?php echo $plantilla_detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
		<?php if($this->Session->read('Auth.User.parametro_precio') =='0'){ //31052018 ?>
        <td style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto'], 2, ',', '.'); ?></td>
        <td style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto_2'], 2, ',', '.'); ?></td>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='1'){ ?>
        <td colspan="2"style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto'], 2, ',', '.'); ?></td>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='2'){ ?>
        <td colspan="2" style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto_2'], 2, ',', '.'); ?></td>
		<?php } ?>
        <?php if($this->Session->read('Auth.User.parametro_precio') =='3'){ ?>
        <td colspan="2" style="text-align: right;">$ <?php echo number_format($plantilla_detalle['PlantillasDetalle']['precio_producto'], 2, ',', '.'); ?></td>
		<?php } ?>
        <td><?php echo ($plantilla_detalle['PlantillasDetalle']['iva_producto']*100); ?> %</td>
        <td><?php echo $plantilla_detalle['PlantillasDetalle']['medida_producto']; ?></td>
    </tr>
            <?php
        }
        ?>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
</table>
<?php } ?>

