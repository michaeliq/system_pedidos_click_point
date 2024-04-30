<?php

echo $this->Html->script(array('plantillas/plantillas_edit'));
echo $this->Html->script(array('plantillas/plantillas_search'));
?>
<?php echo $this->Form->create('Plantilla'); ?>
<fieldset>
    <legend><?php __('Editar Plantilla'); ?></legend>
    <table class="table-striped table-bordered table-condensed" align="center">
        <tr>
            <td class="text-center"><b>Plantilla: *</b></td>
            <td><?php echo $this->Form->input('id',array('type' => 'hidden')); ?>
                <?php echo $this->Form->input('nombre_plantilla', array('type' => 'text', 'label' => false, 'size' => '60', 'maxlength' => '60')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Tipo Pedido: *</b> </td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'label' => false,'options'=>$tipoPedido,'empty'=>'Seleccione una Opción')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Estado Plantilla: *  </b></td>
            <td><?php echo $this->Form->input('estado_plantilla', array('type' => 'checkbox', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Detalle Plantilla: </b></td>
            <td><?php echo $this->Form->input('detalle_plantilla', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'60','placeholder'=>'Digite sus observaciones generales de la plantilla.')); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Empresa: *</b> </td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Seleccione una Opción'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td class="text-center"><b>Regional: *</b> </td>
            <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $regionales, 'empty' => array('0'=>'Seleccione una Opción'), 'label' => false)); ?></td>
        </tr>
        <!-- <tr>
            <td class="text-center"><b>Plantilla Base: *</b> </td>
            <td><?php // echo $this->Form->input('plantilla_base', array('type' => 'checkbox', 'label' => false,'div'=>false)); ?></td>
        </tr> -->
        <tr>
            <td colspan="2" class="text-center" >

                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Plantilla', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
    <div>&nbsp;</div>
    <table id="productos_plantilla" class="table-striped table-bordered table-hover table-condensed"align="center">
<!--        <tr>
            <td colspan="9" class="text-center" >

                <?php //echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php //echo $this->Form->button('Editar Plantilla', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>-->
        <tr>
            <th>C&oacute;digo Producto</th>
            <th>Producto</th>
            <th>Proveedor</th>
            <th>Categor&iacute;a</th>
             <?php if($this->Session->read('Auth.User.parametro_precio') < '3'){ //31052018 ?>
            <th>Precio Centro Aseo N. Nacional</th>  <!-- 2022-11-17 -->
            <th>Precio Centro Aseo Bog/Sab</th>  <!-- 2022-11-17 -->
            <th>Precio<br>Venta Nac.</th> <!-- 2022-11-17 -->
            <th>Precio<br>Venta Bog/Sab</th> <!-- 2022-11-17 -->
             <?php }else{ ?>
            <th colspan="2">Precio Base</th>
             <?php } ?>
            <th>IVA</th>
            <th>Medida</th>
            <th title="Incluir todos">Incluir <input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        </tr>
        <tr>
            <th><input type="text" id="codigo_producto_sh" onkeyup="search_codigo_producto()" size="10" maxlength="10" placeholder="Código ..." title="Digite un código de producto"></th>
            <th colspan="8"><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Producto ..." title="Digite un nombre de producto"></th>
        </tr>
        <?php
// print_r($plantillas_detalles);
        $i = 0;
        foreach ($productos as $producto) {
                    $tipo_categoria_id = $producto['Producto']['tipo_categoria_id'];
                    $tipo_categoria_nombre = $producto['TipoCategoria']['tipo_categoria_descripcion'];
                    $precio_producto = $producto['Producto']['precio_producto'];
                    $precio_producto_2 = $producto['Producto']['precio_producto'];
                    $precio_producto_bs_2 = $producto['Producto']['precio_producto_bs'];
                    $iva_producto = $producto['Producto']['iva_producto'];
                    $medida_producto = $producto['Producto']['medida_producto'];
                    
            $checked = false;
            foreach ($plantillas_detalles as $plantillas_detalle):
            /*    echo $plantillas_detalle['PlantillasDetalle']['producto_id'];
            echo "-";
            echo $producto['Producto']['id'];
            echo "<br>";*/
                if ($plantillas_detalle['PlantillasDetalle']['producto_id'] == $producto['Producto']['id'] && $checked == false) {
                    $checked = true;
                    $tipo_categoria_id = $plantillas_detalle['PlantillasDetalle']['tipo_categoria_id'];
                    $precio_producto = $plantillas_detalle['PlantillasDetalle']['precio_producto'];
                    $precio_producto_2 = ($plantillas_detalle['PlantillasDetalle']['precio_producto_2']=='0'?$plantillas_detalle['PlantillasDetalle']['precio_producto']:$plantillas_detalle['PlantillasDetalle']['precio_producto_2']);//31052018
                    $precio_producto_bs_2 = ($plantillas_detalle['PlantillasDetalle']['precio_producto_bs_2']=='0'?$plantillas_detalle['PlantillasDetalle']['precio_producto_bs']:$plantillas_detalle['PlantillasDetalle']['precio_producto_bs_2']);//31052018
                    $iva_producto = $plantillas_detalle['PlantillasDetalle']['iva_producto'];
                    $medida_producto = $plantillas_detalle['PlantillasDetalle']['medida_producto'];
                    break;
                }else{
                    $tipo_categoria_id = $producto['Producto']['tipo_categoria_id'];
                    $precio_producto = $producto['Producto']['precio_producto'];
                    $precio_producto_2 = $producto['Producto']['precio_producto']; //31052018
                    $precio_producto_bs_2 = $producto['Producto']['precio_producto_bs']; //31052018
                    $iva_producto = $producto['Producto']['iva_producto'];
                    $medida_producto = $producto['Producto']['medida_producto'];
                }
            endforeach;
            ?>
        <tr class="class_<?php echo $producto['Producto']['tipo_categoria_id']; ?>">
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><?php echo $producto['Producto']['nombre_producto'].' '.$producto['Producto']['marca_producto']; ?></td>
            <td>
                <?php 
                     if ($producto['Producto']['proveedor_producto']) {
                        echo " CLICK POINT ";
                    } else {
                        echo " Otro";
                    }
                ?>
            </td>
            <td><?php echo $tipo_categoria_nombre; echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $producto['Producto']['precio_producto']));  // $this->Form->input('tipo_categoria_id_'.$producto['Producto']['id'], array('type' => 'select', 'options' => $tipoCategoria, 'empty' => 'Seleccione una Opción', 'label' => false,'default'=>$tipo_categoria_id)); ?></td>

            <td><?php echo '$ '. number_format($producto['Producto']['precio_producto'],0,",",".");   ?></td>
            <td><?php echo '$ '.number_format($producto['Producto']['precio_producto_bs'],0,",","."); ?></td>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='0'){ //31052018 ?>
            <td><?php echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$precio_producto_2)); //31052018 ?></td>
            <td><?php echo $this->Form->input('precio_producto_bs_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$precio_producto_bs_2)); //31052018 ?></td>
            <?php } ?>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='1'){ ?>
            <td><?php echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$precio_producto)); ?></td>
            <td class="text-right">$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $precio_producto_2));  //31052018 ?></td>
            <?php } ?>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='2'){ ?>
            <td  class="text-right">$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); 
            echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $precio_producto));  //31052018 
            ?></td>
            <td><?php echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','value'=>$precio_producto_2)); ?></td>
            <?php } ?>
            <?php if($this->Session->read('Auth.User.parametro_precio') =='3'){ ?>
            <td class="text-right" colspan="2">$ <?php echo number_format($producto['Producto']['precio_producto'],0,",","."); 
            echo $this->Form->input('precio_producto_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $precio_producto));  
            echo $this->Form->input('precio_producto_2_'.$producto['Producto']['id'], array('type' => 'hidden', 'value' => $precio_producto_2));  //31052018 ?></td>
            <?php } ?>
            <td><?php echo $iva_producto; // $this->Form->input('iva_producto_'.$producto['Producto']['id'], array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '4', 'placeholder'=>'0.16','value'=>$iva_producto)); ?></td>
            <td><?php echo $medida_producto; // $this->Form->input('medida_producto_'.$producto['Producto']['id'], array('type' => 'select', 'options' => $unidadMedida, 'empty' => 'Seleccione una Opción', 'label' => false,'default'=>$medida_producto)); ?></td>
            <td><?php echo $this->Form->input($producto['Producto']['id'], array('type' => 'checkbox', 'label' => false, 'checked' => $checked, 'value' => $producto['Producto']['id'], 'class' => 'ck')); ?></td>
        </tr>
            <?php
            $i++;
        }
        ?>
        <tr>
            <td colspan="8">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="8" class="text-center" >

                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Plantilla', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php echo $i; ?>