<?php echo $this->Html->script(array('pedidos/list_ordenes'));  ?>
<h2><span class="glyphicon glyphicon-random"></span> CAMBIAR ESTADO A PEDIDO</h2>
<?php echo $this->Form->create('Pedido'); ?>

<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 70%;">
    <tr>
        <td colspan="6"  align='center'><h3><b>1. Digite el N&uacute;mero de Orden al que le quiere cambiar de estado:</b></h3><br><span style="color: #0a0"><b>Sin son varias ordenes, escribalas separadas por comas así (154,158,180,193)</b></span></td>
    </tr>
    <tr align='center'>
        <td colspan="5"><?php echo $this->Form->input('id', array('type' => 'textarea', 'placeholder'=>'No. Orden', 'rows'=>'3','cols'=>'80', 'label' => false)); ?></td>
        <td><?php echo $this->Form->button('Consultar', array('type' => 'submit', 'class' => 'btn btn-info')); ?></td>
    </tr>
    <?php if (count($data_pedidos)>0){ ?>
    <tr align='center'>
        <td colspan="6"><h3>2. El siguiente recuadro contiene la informaci&oacute;n actual de las ordenes:</h3></td>
    </tr>
    <tr align='center'>
        <th><input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        <th>No. Orden</th>
        <th>Empresa - Sucursal</th>
        <th>Fecha Pedido</th>
        <th>Estado</th>
        <th>Tipo Pedido</th>
    </tr>
    <?php
            // Mostrar mensaje de la cantidad de días habilitados para cambiar pedidos
            $mensaje = false;
    
            foreach ($data_pedidos as $pedido) {

                $fecha_pedido = new DateTime($pedido['Pedido']['pedido_fecha']);
                $fecha_actual = new DateTime(date('Y-m-d'));
                $diff = $fecha_pedido->diff($fecha_actual);
                $dias_pedido = $diff->days;
                if($dias_pedido > 90){
                    $mensaje = true;
                }else{
                    $mensaje = false;
                }

    ?>
    <tr align='center'>
        <td><?php 
			if($dias_pedido <= 690 && $pedido['EstadoPedido']['id'] < 6){ 
				echo $this->Form->input($pedido['Pedido']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $pedido['Pedido']['id'], 'class' => 'ck')); 
			} ?>
		</td>
        <td><span style='color:red;'><b>#000<?php echo $pedido['Pedido']['id']; ?></b></span></td>
        <td><?php echo $pedido['Empresa']['nombre_empresa']; ?> <br> <?php echo $pedido['Sucursale']['nombre_sucursal']; ?></td>
        <td><b><?php echo $pedido['Pedido']['pedido_fecha']; ?></b> <?php if($dias_pedido > 90){ echo '<br><span style="color: #ef2929;"><b>'.$dias_pedido.' días.</b></span>'; } ?> </td>
        <td><b><?php echo $pedido['EstadoPedido']['nombre_estado']; ?></b></td>
        <td><b><?php echo $pedido['TipoPedido']['nombre_tipo_pedido']; ?></b></td>
    </tr>
    <?php
            }
            
    ?>
    <?php /*
    <tr align='center'>
        <td><span style='color:red;'>#000<?php echo $pedido_id; ?></span></td>
        <td><?php echo $empresa; ?> <br> <?php echo $sucursal; ?></td>
        <td><b><?php echo $estado; ?></b></td>
    </tr>
    <tr align='center'>
        <td colspan="4"><b>Tipo de Pedido:</b> <?php echo $tipo_pedido; ?></td>
    </tr> 
     * */?>
    <?php if($mensaje){ ?>
    <tr align='center'>
        <td colspan="6" style="color: #ef2929;"><b>Solo se pueden cambiar ordenes con máximo 90 días de creadas. <br>Las ordenes que no se pueden modificar se han marcado con la cantidad de días transcurridos.</td>
    </tr>
    <?php } ?>
    <tr align='center'>
        <td colspan="6"><h3>3. Seleccione el nuevo estado de la orden:</h3></td>
    </tr>
    <tr align='center'>
        <td colspan="2"><b>Nuevo Estado:</b></td>
        <td colspan="2"><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td colspan="2"><?php echo $this->Form->button('Cambiar Estado', array('type' => 'submit', 'class' => 'btn btn-success')); ?></td>
    </tr>
    <?php } ?>
</table>
<?php echo $this->Form->end(); ?>