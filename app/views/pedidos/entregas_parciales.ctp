<?php ?>
<script>
    function validar_cantidades(cantidad, cantidad_parcial, id) {
        if (cantidad_parcial > cantidad) {
            alert("La cantidad faltante (" + cantidad_parcial + ") no puede ser mayor a la cantidad del pedido (" + cantidad + ").");
            $('#' + id).val('')
        }

        if (!/^([0-9])*$/.test(cantidad_parcial))
            alert("El valor " + cantidad_parcial + " no es un número");
    }

    function search_nombre_producto() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("nombre_producto_sh");
        filter = input.value.toUpperCase();
        table = document.getElementById("productos");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<h2>ENTREGAS PARCIALES DE ORDENES DE PEDIDO <span style='color:red;'>#000<?php echo $id; ?></span></h2>
<table id="productos" class="table table-hover">
    <tr>
        <th>Descripci&oacute;n</th>
        <th>Categor&iacute;a <?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $categorias, 'empty' => '', 'label' => false, 'default' => '1'));                                  
                                ?></th>
        <!--  <th>Precio</th>
        <th>IVA</th>
        <th>Valor Producto</th>
        <th>Total</th>   -->
        <th>Cant.</th>
        <th>Cantidad<br>Faltante</th>
    </tr>
    <tr>
        <th colspan="4"><input type="text" id="nombre_producto_sh" onkeyup="search_nombre_producto()" size="60" maxlength="100" placeholder="Buscar producto ..." title="Digite un nombre de producto"></th>
    </tr>
    <?php
    // $total_final = 0;
    foreach ($detalles as $detalle) :
        // $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];
    ?>
        <tr>
            <td><?php echo $detalle['Producto']['producto_completo']; ?></td>
            <td><?php echo $detalle['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
            <?php /*
        <td>$ <?php echo number_format($detalle['PedidosDetalle']['precio_producto'], 2, ',', '.'); ?> </td>
        <td><?php echo ($detalle['PedidosDetalle']['iva_producto']*100); ?> %</td>
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])), 2, ',', '.'); ?></td>
        
        <td>$ <?php echo number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.'); ?></td> */ ?>
            <td><?php echo number_format($detalle['PedidosDetalle']['cantidad_pedido'], 0, ",", "."); ?></td>
            <td align="center">
                <?php
                if ($detalle['PedidosDetalle']['cantidad_pedido_parcial'] > 0) {
                    echo '<span style="color:red;"><b>' . $detalle['PedidosDetalle']['cantidad_pedido_parcial'] . '</b></span>';
                    echo " ";
                    echo $this->Html->link(__(' ', true), array('action' => 'entregas_parciales_refresh', $detalle['PedidosDetalle']['id']), array('class' => 'glyphicon glyphicon-refresh', 'escape' => false));
                } else {
                ?>
                    <?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'entregas_parciales/' . base64_encode($id)))); ?>
                    <table>
                        <tr>
                            <td><?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $detalle['PedidosDetalle']['id'])); ?>
                                <?php echo $this->Form->input('cantidad_pedido_parcial_' . $detalle['PedidosDetalle']['id'], array('type' => 'text', 'label' => false, 'placeholder' => 'Cantidad', 'size' => '5', 'maxlength' => '4', 'onchange' => 'validar_cantidades(' . $detalle['PedidosDetalle']['cantidad_pedido'] . ',this.value, this.id)')); ?></td>
                            <td><?php echo $this->Form->button('Actualizar', array('type' => 'submit', 'class' => 'btn btn-success  btn-xs', 'onclick' => '')); ?> </td>
                        </tr>
                    </table>
                    <?php echo $this->Form->end(); ?>
                <?php } ?>
            </td>
        </tr>
    <?php
    endforeach;
    ?>
    <!--    <tr>
        <td colspan="6" class="text-center"><b>&nbsp; Total >> </b></td>
        <td><b>$ <?php //echo number_format($total_final, 2, ',', '.');
                    ?></b></td>
        <td></td>
    </tr>-->
</table>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'pedidos', 'action' => 'entregas_parciales'))); ?>
<?php echo $this->Form->input('id_pedido', array('type' => 'hidden', 'value' => $id)); ?>

<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Crear Orden por entregas parciales', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
</div>
<?php echo $this->Form->end(); ?>