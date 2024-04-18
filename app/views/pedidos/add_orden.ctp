<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    $(function() {
        $('#tipo_categoria_id').change(function(){
            $.ajax({
                url:'add_orden',
                type:'POST',
                data:{
                    TipoCategoriaId:$('#tipo_categoria_id').val()
                },
                async:false,
                dataType:"json",
                success:onSuccess
            });
            function onSuccess(data){

            }
        });
    });
</script>

<h2>Realizar Orden de Pedido</h2>
<?php /*
  <table class="table table-hover">
  <tr>
  <td>Direcci&oacute;n Pedido:</td>
  <td></td>
  <td>Tel&eacute;fono Pedido:</td>
  <td></td>
  </tr>
  </table>
 * */ ?>

<table class="table table-hover">
    <tr>
        <th>C&oacute;digo</th>
        <th>Categor&iacute;a <?php // echo $this->Form->input('tipo_categoria_id', array('type' => 'select', 'options' => $categorias, 'empty' => '', 'label' => false, 'default' => '1'));  ?></th>
        <th>Descripci&oacute;n</th>
        <th>Cantidad</th>
        <th></th>
    </tr>
    <?php
    foreach ($productos as $producto) :
        ?>
        <tr>
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><?php echo $producto['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
            <td><?php echo $producto['Producto']['nombre_producto'].' '.$producto['Producto']['marca_producto']; ?></td>
            <td><?php echo $this->Form->input('cant_producto', array('type' => 'text', 'size' => '5', 'maxlength' => '4', 'label' => false, 'value' => '0')); ?></td>
            <td><?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-success btn-xs')); ?></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
<div class="text-center">
    <?php echo $this->Form->button(' Visualizar Pedido ', array('type' => 'submit', 'class' => 'btn btn-info')); ?>
    <?php echo $this->Form->button(' Hacer Pedido ', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    <?php echo $this->Form->button(' Cancelar ', array('type' => 'submit', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
</div>