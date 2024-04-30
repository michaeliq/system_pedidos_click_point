<?php

if($this->data['Producto']['iva_producto']=='0'){
    $iva_producto = '0.00';
}else{
    $iva_producto = $this->data['Producto']['iva_producto'];
}
    
echo $this->Html->script(array('productos/productos_edit.js?var='.date('y-m-dhis'))); ?>

<fieldset>
    <legend><?php __('Editar Producto'); ?></legend>
    <table class="table">
        <tr>
            <td>
                <?php echo $this->Form->create('Producto',array('type' => 'file', 'action' => 'edit')); ?>
                <table class="table table-striped table-bordered table-condensed" align="center">
                    <tr>
                        <td>C&oacute;digo: *</td>
                        <td colspan="3"><?php echo $this->Form->input('codigo_producto', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Centro Aseo')); ?></td>
                    </tr>
                    <tr>
                        <td>Producto: *</td>
                        <td colspan="3"><?php echo $this->Form->input('nombre_producto', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120', 'placeholder'=>'Nombre Producto')); ?></td>
                    </tr>
                    <tr>
                        <td>Marca Producto: *</td>
                        <td colspan="3"><?php echo $this->Form->input('marca_producto', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '45', 'placeholder'=>'Marca Producto')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 1:</td>
                        <td><?php echo $this->Form->input('empresa1_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa1_nombre', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa1_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 2:</td>
                        <td><?php echo $this->Form->input('empresa2_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa2_nombre', array('type' => 'text', 'label' => false, 'size' => '50','maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa2_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 3:</td>
                        <td><?php echo $this->Form->input('empresa3_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa3_nombre', array('type' => 'text', 'label' => false, 'size' => '50','maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa3_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 4:</td>
                        <td><?php echo $this->Form->input('empresa4_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa4_nombre', array('type' => 'text', 'label' => false, 'size' => '50','maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa4_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 5:</td>
                        <td><?php echo $this->Form->input('empresa5_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa5_nombre', array('type' => 'text', 'label' => false, 'size' => '50','maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa5_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>C&oacute;digo Empresa 6:</td>
                        <td><?php echo $this->Form->input('empresa6_codigo', array('type' => 'text', 'label' => false, 'maxlength' => '20', 'placeholder'=>'Código Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa6_nombre', array('type' => 'text', 'label' => false, 'size' => '50','maxlength' => '120','placeholder'=>'Nombre Homologo Producto')); ?></td>
                        <td><?php echo $this->Form->input('empresa6_precio', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10','placeholder'=>'Precio Homologo')); ?></td>
                    </tr>
                    <tr>
                        <td>Categoria: *</td>
                        <td colspan="3"><?php echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $tipoCategoria, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Esecificación Técnica: *</td>
                        <td colspan="3"><?php echo $this->Form->input('especificacion_tecnica', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite la especificación técnica del producto')); ?></td>
                    </tr>
                    <tr>
                        <td>Presentación: *</td>
                        <td colspan="3"><?php echo $this->Form->input('presentacion_producto', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite la presentación del producto.')); ?></td>
                    </tr>
                    <tr>
                        <td>Precio centro aseo a nivel nacional: *</td>
                        <td colspan="3"><?php echo $this->Form->input('precio_producto', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10')); ?></td>
                    </tr>
                    <tr>
                        <td>Precio centro aseo a Bogotá-Sabana: *</td>
                        <td colspan="3"><?php echo $this->Form->input('precio_producto_bs', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '10')); ?></td>
                    </tr>
                    <tr>
                        <td>IVA: *</td>
                        <td colspan="3"><?php echo $this->Form->input('iva_producto', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '4', 'placeholder'=>'0.16','value'=>$iva_producto)); ?></td>
                    </tr>
                    <tr>
                        <td>Unidad de medida: *</td>
                        <td colspan="3"><?php echo $this->Form->input('medida_producto', array('type' => 'select', 'options' => $unidadMedida, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Imagen del Producto: </td>
                        <td colspan="3"><?php echo $this->Form->input('imagen_producto_2', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Stock minimo:</td>
                        <td colspan="3"><?php echo $this->Form->input('stock_minimo', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '10', 'placeholder'=>'30','value'=>'0')); ?></td>
                    </tr>
                    <tr>
                        <td>Proveedor: </td>
                        <td colspan="3"><?php echo $this->Form->input('proveedor_producto', array('type' => 'checkbox', 'label' => 'CLICK POINT', 'onclick' => 'label_int_ext(this.id)')); ?></td>
                    </tr>
                    <tr>
                        <td>Mensaje / Advertencia:</td>
                        <td colspan="3"><?php echo $this->Form->input('mensaje_advertencia', array('type' => 'textarea', 'label' => false, 'cols' => '50', 'rows'=>'3', 'placeholder'=>'Este producto ...')); ?><br>
                            <b>Última actualizacion: <?php echo $this->data['Producto']['fecha_actualizacion']; ?></b></td>
                    </tr>
                    <tr>
                        <td>Ficha Tecnica: </td>
                        <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_2_1', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Registro Invima - NSO: </td>
                        <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_2_2', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Hoja de Seguridad: </td>
                        <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_3_2', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Registro Bioseguridad: </td>
                        <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_4_2', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Otros: </td>
                        <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_5_2', array('type' => 'file', 'label' => false)); ?></td>
                    </tr>
<?php /*
                    <tr>
                        <td>Multiplo:</td>
                        <td><?php echo $this->Form->input('multiplo', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '3', 'placeholder'=>'4')); ?></td>
                    </tr>
*/ ?>
                    <tr>
                        <td colspan="3" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Producto', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                        </td>
                    </tr>
                <?php
                    echo $this->Form->input('id', array('type' => 'hidden'));
                    echo $this->Form->input('imagen_producto', array('type' => 'hidden'));
                    echo $this->Form->input('archivo_adjunto', array('type' => 'hidden'));
                    echo $this->Form->input('archivo_adjunto_2', array('type' => 'hidden'));
                    echo $this->Form->input('archivo_adjunto_3', array('type' => 'hidden'));
                    echo $this->Form->input('archivo_adjunto_4', array('type' => 'hidden'));
                    echo $this->Form->input('archivo_adjunto_5', array('type' => 'hidden'));
                ?>
                </table>
                <?php echo $this->Form->end(); ?>
            </td>
<?php /*
            <td  valign="top">
                <h4>Resumen de últimos 10 movimientos de entrada</h4>
                <?php echo $this->Form->create('Producto',array('id' => 'actualizar_plantillas', 'action' => 'edit_porcentaje')); ?>
                <table class="table table-condensed table-striped table-hover table-bordered" >
                    <tr>
                        <th>Mov.Ent</th>
                        <th>Producto</th>
                        <th class="text-center">Precio Compra</th>
                        <th class="text-center">Fecha</th>
                    </tr>
                        <?php
                        $i = 0;
                        $promedio = 0;
                        $promedio_final = 0;
                        $relacion = 0;
                        $ultimo_precio = null;
                        $arrow = null;
                            if (count($movimientosEntradas) > 0) {
                                foreach ($movimientosEntradas as $movimientosEntrada) :
                                    $relacion = $ultimo_precio - $movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'];
                                    $promedio = $promedio + $movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'];
                                    if($i > 0){
                                        if($relacion<0){
                                            // echo "Subio ";
                                            $arrow = '<span class="glyphicon glyphicon-chevron-up" style="color: #e32;"></span>';
                                        }else{
                                            if($relacion == 0){
                                                // echo "Igual";    
                                                $arrow = '<span class="glyphicon glyphicon-chevron-right" style="color: #0a0;"></span>';
                                            }else{
                                                // echo "Bajo";    
                                                $arrow = '<span class="glyphicon glyphicon-chevron-down" style="color: #FECA40;"></span>';
                                            }
                                        }
                                        

                                    }
                                    ?>
                    <tr>
                        <td><b>#000<?php echo $movimientosEntrada['MovimientosEntradasDetalle']['movimientos_entrada_id']; ?></b></td>
                        <td><?php echo $movimientosEntrada['Producto']['producto_completo']; ?></td>
                        <td class="text-right"><?php echo $arrow;?> $<?php echo number_format($movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'],2,",","."); ?></td>
                        <td class="text-right"><?php echo $movimientosEntrada['MovimientosEntradasDetalle']['fecha_registro_entrada']; ?></td>
                    </tr>
                            <?php
                                    $i++;
                                    $ultimo_precio = $movimientosEntrada['MovimientosEntradasDetalle']['precio_producto'];
                                endforeach;
                                $promedio_final = $promedio/$i;
                            }
                        ?>
                    <tr>
                        <td colspan="3" class="text-center"><b>Promedio precio:</b></td>
                        <td  class="text-right"><b>$<?php echo number_format($promedio_final,2,",","."); ?></b></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3"><div id="nuevo_precio"></div></td>
                        <td><b>Porcentaje:</b><br><?php echo $this->Form->input('porcentaje', array('type' => 'text', 'label' => false, 'size' => '10', 'maxlength' => '5', 'placeholder'=>'Ejemplo: 10')); ?>
                            </td>
                        <td><?php echo $this->Form->button('Actualizar Plantillas', array('id'=>'button_actualizar','type' => 'submit', 'class' => 'btn btn-primary')); ?></td>
                    </tr>
                    <?php echo $this->Form->input('id', array('type' => 'hidden'));?> 
                    <?php echo $this->Form->input('precio_producto', array('type' => 'hidden'));?> 
                </table>
                <?php echo $this->Form->end(); ?>
            </td>
*/ ?>
        </tr>
    </table>
</fieldset>
