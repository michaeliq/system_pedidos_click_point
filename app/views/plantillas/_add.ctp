<?php

echo $this->Html->script(array('plantillas/plantillas_add')); 
echo $this->Html->script(array('plantillas/plantillas_search'));
?>
<script>
$(document).ready(function () {
    $(".check_base").click(function (event) {
        if ($(this).is(":checked")) {
             $('#PlantillaPlantillaId').prop('disabled', 'disabled');
			 $('#productos_plantilla').show();
			 $("#PlantillaPlantillaId").val('');
        } else {
			$('#PlantillaPlantillaId').prop('disabled', false);
        }
    });
	$("#PlantillaPlantillaId").change(function(){
		$('#productos_plantilla').hide();
	}); 

});
</script>
<?php echo $this->Form->create('Plantilla'); ?>
<fieldset>
    <legend><?php __('Nueva Plantilla'); ?></legend>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td class="text-center"><b>Plantilla: *</b> <?php echo $this->Form->input('nombre_plantilla', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '60','placeholder'=>'Nombre especifico de la plantilla.')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Tipo Pedido: *</b> <?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'label' => false,'options'=>$tipoPedido,'empty'=>'Seleccione una Opción')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Detalle Plantilla:</b> <?php echo $this->Form->input('detalle_plantilla', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'60','placeholder'=>'Digite sus observaciones generales de la plantilla.')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Empresa: *</b> <?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array(''=>'Seleccione una Opción'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Regional: *</b> <?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $regionales, 'empty' => array(''=>'Seleccione una Opción'), 'label' => false)); ?></td>
        </tr>		
        <tr>
            <td class="text-center"><b>Es plantilla Base: *</b> <?php echo $this->Form->input('plantilla_base', array('type' => 'checkbox', 'label' => false,'div'=>false, 'class'=>'check_base')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Crear plantilla a partir de: </b> <?php echo $this->Form->input('plantilla_id', array('type' => 'select', 'options' => $plantillas_base, 'empty' => array(''=>'Seleccione una Opción'), 'label' => false)); ?>
            </td>
        </tr>
    </table>
    <div>&nbsp;</div>
	<div class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Plantilla', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    </div>
	<div>&nbsp;</div>
    <table id="productos_plantilla" class="table-striped table-bordered table-hover table-condensed"align="center">
        <tr>
            <th>C&oacute;digo Producto</th>
            <th>Producto</th>
            <th>Proveedor</th>
            <th>Categor&iacute;a</th>
            <th>Precio Centro Aseo N. Nacional</th>  <!-- 2022-11-17 -->
            <th>Precio Centro Aseo Bog/Sab</th>  <!-- 2022-11-17 -->
            <th>Precio<br>Venta Nac.</th> <!-- 2022-11-17 -->
            <th>Precio<br>Venta Bog/Sab</th> <!-- 2022-11-17 -->
            <th>IVA</th>
            <th>Medida</th>
            <th title="Incluir todos">Incluir <input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        </tr>
        <tr>
            <th><input type="text" id="codigo_producto_sh" onkeyup="search_codigo_producto()" size="10" maxlength="10" placeholder="Código ..." title="Digite un código de producto"></th>
            <th colspan="8"><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Producto ..." title="Digite un nombre de producto"></th>
        </tr>
        <?php
        foreach ($productos as $producto) {
            ?>
        <tr class="class_<?php echo $producto['Producto']['tipo_categoria_id']; ?>">
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><?php echo $producto['Producto']['nombre_producto'].' '.$producto['Producto']['marca_producto']; ?></td>
            <td>
                <?php 
                    if ($producto['Producto']['proveedor_producto']) {
                        echo " CENTRO ASEO";
                    } else {
                        echo " Otro";
                    }
                ?>
            </td>
            <td><?php echo $producto['TipoCategoria']['tipo_categoria_descripcion']; // $this->Form->input('tipo_categoria_id_'.$producto['Producto']['id'], array('type' => 'select', 'options' => $tipoCategoria, 'empty' => 'Seleccione una Opción', 'label' => false,'default'=>$producto['Producto']['tipo_categoria_id'])); ?></td>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='0'){ //31052018?>
            <td><?php echo '$ '. number_format($producto['Producto']['precio_producto'],0,",",".");  echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['precio_producto'])); // echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto'])); ?></td>
            <td><?php echo '$ '.number_format($producto['Producto']['precio_producto_bs'],0,",","."); // echo $this->Form->input('precio_producto_bs_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto_bs'])); ?></td>
            <td><?php echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto'])); //31052018 ?></td>
            <td><?php echo $this->Form->input('precio_producto_bs_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto_bs'])); //31052018 ?></td>
            <?php } ?>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='1'){ ?>
            <td><?php echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto'])); ?></td>
            <td class="text-right">$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['precio_producto']));  //31052018 ?></td>
            <td class="text-right">$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); echo $this->Form->input('precio_producto_bs_2_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['precio_producto_bs']));  //31052018 ?></td>
            <?php } ?>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='2'){ ?>
            <td>$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['precio_producto']));  //31052018 ?></td>
            <td><?php echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto'])); ?></td>
            <td><?php echo $this->Form->input('precio_producto_bs_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$producto['Producto']['precio_producto_bs'])); ?></td>
            <?php } ?>
            <td><?php echo $producto['Producto']['iva_producto']; // $this->Form->input('iva_producto_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '4', 'placeholder'=>'0.16','value'=>$producto['Producto']['iva_producto'])); ?></td>
            <td><?php echo $producto['Producto']['medida_producto']; // $this->Form->input('medida_producto_'.$producto['Producto']['id'], array('type' => 'select', 'options' => $unidadMedida, 'empty' => 'Seleccione una Opción', 'label' => false,'default'=>$producto['Producto']['medida_producto'])); ?></td>
            <td><?php echo $this->Form->input($producto['Producto']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $producto['Producto']['id'], 'class' => 'ck')); ?></td>

        </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Plantilla', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
        <?php
        echo $this->Form->input('estado_plantilla', array('type' => 'hidden', 'value' => true));
        ?>
</fieldset>
<?php echo $this->Form->end(); ?>