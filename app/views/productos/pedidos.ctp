<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Realizar Pedido</h2>
<table class="table table-hover">
    <tr>
        <th>C&oacute;digo</th>
        <th>Categor&iacute;a</th>
        <th>Descripci&oacute;n</th>
        <th>Cantidad</th>
    </tr>
    <?php
    foreach ($productos as $producto) :
        ?>
        <tr>
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><?php echo $producto['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
            <td><?php echo $producto['Producto']['nombre_producto']; ?></td>
            <td><?php echo $this->Form->input('cant_producto', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>