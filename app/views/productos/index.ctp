<?php ?>
<script>
    function label_int_ext(parameter) {
        if ($('#' + parameter).is(":checked")) {
            $("label[for='" + parameter + "']").text("CLEANEST L&C");
        } else {
            $("label[for='" + parameter + "']").text("Otro");
        }
    }
</script>


<h2><span class="glyphicon glyphicon-leaf"></span> PRODUCTOS</h2>
<?php echo $this->Form->create('Producto'); ?>
<table class="table table-condensed">
    <tr>
        <td>C&oacute;digo:</td>
        <td><?php echo $this->Form->input('codigo_producto', array('type' => 'text', 'size' => '20', 'maxlength' => '20', 'label' => false)); ?></td>
        <td>Producto:</td>
        <td><?php echo $this->Form->input('nombre_producto', array('type' => 'text', 'size' => '60', 'maxlength' => '120', 'label' => false)); ?></td>
        <td>Categor&iacute;a:</td>
        <td><?php echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $tipoCategoria, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td>Estado:</td>
        <td><?php echo $this->Form->input('estado', array('type' => 'select', 'options' => $estado, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>C&oacute;digo Empresa:</td>
        <td><?php echo $this->Form->input('empresa1_codigo', array('type' => 'text', 'size' => '20', 'maxlength' => '20', 'label' => false)); ?></td>
        <td>Marca Producto:</td>
        <td><?php echo $this->Form->input('marca_producto', array('type' => 'text', 'size' => '30', 'maxlength' => '45', 'label' => false)); ?></td>
        <td><div class="input checkbox">Proveedor:</div></td>
        <td><?php // echo $this->Form->input('proveedor_producto', array('type' => 'checkbox', 'label' => 'CLEANEST L&C', 'checked' => true, 'onclick' => 'label_int_ext(this.id)')); ?></td>
        <td colspan="4">&nbsp;</td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<div class="row">
    <div class="add col-md-2"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Producto', true), array('action' => 'add'), array('escape' => false)); ?></div>
    <div class="add col-md-2"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Cargue de Productos', true), array('action' => 'add_many_products'), array('escape' => false)); ?></div>
</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Imagen', 'imagen_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('Código', 'codigo_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('Producto', 'nombre_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('Presentacion', 'presentacion_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('Categoría', 'tipo_categoria_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Precios', 'precio_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('IVA', 'iva_producto'); ?></th>
            <th><?php echo $this->Paginator->sort('Unidad Medida', 'medida_producto'); ?></th>
<!--            <th><?php // echo $this->Paginator->sort('Stock Minimo', 'stock_minimo'); ?></th>-->
<!--            <th><?php // echo $this->Paginator->sort('Proveedor', 'proveedor_producto'); ?></th>-->
            <th><?php echo $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
// print_r($productos);
foreach ($productos as $producto):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $html->image('productos/'.$producto['Producto']['codigo_producto'].'.jpg'.'?'.rand(5, 1500), array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $producto['Producto']['nombre_producto'])) ?></td>
            <td><?php echo $producto['Producto']['codigo_producto']; 
                    echo '<br><b>'.$producto['Producto']['empresa1_codigo'].'</b>'; 
                    echo '<br><b>'.$producto['Producto']['empresa2_codigo'].'</b>'; 
                    echo '<br><b>'.$producto['Producto']['empresa3_codigo'].'</b>'; 
                    echo '<br><b>'.$producto['Producto']['empresa4_codigo'].'</b>'; 
                ?>
            </td>
            <td><?php echo $producto['Producto']['nombre_producto']; ?><br><?php if(!empty($producto['Producto']['marca_producto'])) echo '<b>Marca: </b>'.$producto['Producto']['marca_producto']; ?></td>
            <td><?php echo $producto['Producto']['presentacion_producto']; ?></td>
            <td><?php echo $producto['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
            <td><b>N. Nacional: </b>$ <?php echo number_format($producto['Producto']['precio_producto'], 2, ',', '.'); 
                if(!empty($producto['Producto']['precio_producto_bs']))
                     echo '<br><b>Bog/Sab:</b> $'.number_format($producto['Producto']['precio_producto_bs'], 2, ',', '.');
                
            ?></td>
            <td><?php echo ($producto['Producto']['iva_producto']*100); ?> %</td>
            <td><?php echo $producto['Producto']['medida_producto']; ?></td>
<!--            <td><?php // echo $producto['Producto']['stock_minimo']; ?></td>-->
<!--            <td>
                <?php 
                   /* if ($producto['Producto']['proveedor_producto']) {
                        echo " CENTRO ASEO";
                    } else {
                        echo " Otro";
                    }*/
                ?>
            </td>-->
            <td>
                    <?php
                    if ($producto['Producto']['estado']) {
                        echo $html->image('verde.png');
                        // echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        // echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Copiar"><?php echo $this->Html->link(__('', true), array('action' => 'copiar', $producto['Producto']['id']), array('class' => 'glyphicon glyphicon-hand-right', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $producto['Producto']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ($producto['Producto']['estado']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $producto['Producto']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el producto %s?', true), $producto['Producto']['nombre_producto']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $producto['Producto']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el producto %s?', true), $producto['Producto']['nombre_producto']));
                        }
                        ?>
                </div>
                <?php 
                    if ($producto['Producto']['archivo_adjunto']) { 
                        echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }
		    if ($producto['Producto']['archivo_adjunto_2']) { 
                        echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_2'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }
		if ($producto['Producto']['archivo_adjunto_3']) { 
                        echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_3'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }					
		if ($producto['Producto']['archivo_adjunto_4']) { 
                        echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_4'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }
		if ($producto['Producto']['archivo_adjunto_5']) { 
                        echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_5'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                    }	
                ?>
            </td>
        </tr>    
        <?php endforeach; ?>
    </table> 
</div><a 
    <p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?>	</p>

    <div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
        | 	<?php echo $this->Paginator->numbers(); ?>
        |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>