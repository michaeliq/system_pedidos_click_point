<?php

echo $this->Html->script(array('productos/productos_add')); ?>
<?php echo $this->Form->create('Producto',array('type' => 'file', 'action' => 'add')); ?>
<fieldset>
    <legend><?php __('Nuevo Producto'); ?></legend>
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
            <td colspan="3"><?php echo $this->Form->input('iva_producto', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '4', 'placeholder'=>'0.16','value'=>'0.00')); ?> Ejemplo: 0.16</td>
        </tr>
        <tr>
            <td>Unidad de medida: *</td>
            <td colspan="3"><?php echo $this->Form->input('medida_producto', array('type' => 'select', 'options' => $unidadMedida, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Imagen del Producto: </td>
            <td colspan="3"><?php echo $this->Form->input('imagen_producto', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Stock minimo:</td>
            <td colspan="3"><?php echo $this->Form->input('stock_minimo', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '10', 'placeholder'=>'30','value'=>'0')); ?></td>
        </tr>
        <tr>
            <td>Proveedor: </td>
            <td colspan="3"><?php echo $this->Form->input('proveedor_producto', array('type' => 'checkbox', 'label' => 'CLICK POINT', 'checked' => true, 'onclick' => 'label_int_ext(this.id)')); ?></td>
        </tr>
        <tr>
            <td>Mensaje / Advertencia:</td>
            <td colspan="3"><?php echo $this->Form->input('mensaje_advertencia', array('type' => 'textarea', 'label' => false, 'cols' => '50', 'rows'=>'3', 'placeholder'=>'Este producto ...')); ?></td>
        </tr>
        <tr>
            <td>Ficha Tecnica: </td>
            <td colspan="3"><?php echo $this->Form->input('archivo_adjunto', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Registro Invima - NSO: </td>
            <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_2', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Hoja de Seguridad: </td>
            <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_3', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Registro Bioseguridad: </td>
            <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_4', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Otros: </td>
            <td colspan="3"><?php echo $this->Form->input('archivo_adjunto_5', array('type' => 'file', 'label' => false)); ?></td>
        </tr>
<?php /*
        <tr>
            <td>Multiplo:</td>
            <td><?php echo $this->Form->input('multiplo', array('type' => 'text', 'label' => false, 'size' => '4', 'maxlength' => '3', 'placeholder'=>'4')); ?></td>
        </tr>
*/ ?>
        <tr>
            <td colspan="3" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Producto', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>