<?php

// echo $this->Session->read('Auth.User.sucursal_id');
    echo $this->Html->script(array('pedidos/orden_pedido')); ?>
<h2><span class="glyphicon glyphicon-shopping-cart"></span> ORDENES DE PEDIDO - REALIZAR ORDEN</h2>
<?php echo $this->Form->create('Pedido'); ?>
<table class="table table-striped table-bordered table-condensed" align="center">
    <?php     if (count($pedidos) > 0) { ?>
    <tr>
        <td colspan="4">
            <!--            <h2>ORDENES EN PROCESO</h2>-->
            <div class="text-center"><b>Haga click en el icono <div class="glyphicon glyphicon-arrow-right"></div> para continuar.</b></div>
                <?php // print_r($pedidos);?>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th>No. Orden</th>
                    <th>Fecha Orden</th>
                    <th>Direcci&oacute;n Envio</th>
                    <th>Tipo Pedido</th>
                    <th>Estado Orden</th>
                    <th>Acciones</th>
                </tr>
    <?php

        foreach ($pedidos as $pedido):
            ?>
                <tr>
                    <td><span style='color:red;'>#000<?php echo $pedido['Pedido']['id']; ?></span></td>
                    <td><?php echo $pedido['Pedido']['pedido_fecha'] . ' ' . $pedido['Pedido']['pedido_hora']; ?> </td>
                    <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php  echo $pedido['Municipio2']['nombre_municipio']; ?> - <?php echo $pedido['Sucursale']['nombre_sucursal']; ?><br><?php echo $pedido['Pedido']['pedido_direccion']; ?> </td>
                    <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
                    <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
                    <td><div title="Continuar con el Pedido #000<?php echo $pedido['Pedido']['id']; ?>"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div></td>
                </tr>
            <?php
        endforeach;
  
        ?>
            </table>
        </td>
    </tr>
            <?php
    }else{
    ?>
    <tr>
        <td colspan="4">Por favor verifique que los siguientes datos sean correctos para el pedido:</td>
    </tr>
    <tr>
        <td>Empresa:</td>
        <td colspan="3"><?php echo $sucursales['0']['Empresa']['nombre_empresa'];  ?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td>
        <?php 
        if($this->Session->read('Auth.User.cambiar_sucursal')){
            echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options' => $regional, 'empty' => 'Seleccione una Opción', 'label' => false));
        }else{
            echo $sucursales['0']['Sucursale']['regional_sucursal'];    
        }
        ?>
        </td>
        <td>Sucursal:</td>
        <td><?php 
        
        if($this->Session->read('Auth.User.cambiar_sucursal')){
            echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales1, 'empty' => 'Seleccione una Opción', 'label' => false));
        }else{
            echo $sucursales['0']['Sucursale']['nombre_sucursal'];    
            echo $this->Form->input('sucursal_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.sucursal_id')));
        }
        ?></td>
    </tr>
    <tr>
        <td>Departamento:</td>
        <td><?php echo $sucursales['0']['Departamento']['nombre_departamento']; ?></td>
        <td>Municipio:</td>
        <td><?php echo $sucursales['0']['Municipio']['nombre_municipio']; ?></td>
    </tr>
    <tr>
        <td>Dirección Envio: *</td>
        <td><?php echo $this->Form->input('pedido_direccion', array('type' => 'text', 'label' => false, 'value' => $sucursales['0']['Sucursale']['direccion_sucursal'])); ?></td>
        <td>Tel. Contacto: *</td>
        <td><?php echo $this->Form->input('pedido_telefono', array('type' => 'text', 'label' => false, 'value' => $sucursales['0']['Sucursale']['telefono_sucursal'])); ?></td>
    </tr>
    <tr>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <?php if($this->Session->read('Auth.User.rol_id') == '1'){?>
        <td>Tipo de Movimiento Salida: </td>
        <td><?php echo $this->Form->input('tipo_movimiento_id', array('type' => 'select', 'options' => $tipoMovimientos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <?php }else{ ?>
        <td>&nbsp;</td>
        <td>&nbsp; <?php echo $this->Form->input('tipo_movimiento_id', array('type' => 'hidden', 'value' => $tipoMovimientos)); ?></td>
        <?php } ?>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <!--    <tr>
        <td>Observaciones</td>
        <td colspan="3"><?php // echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80')); ?></td>
    </tr>-->
    <tr>
        <td colspan="4" class="text-center" >
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
            <?php echo $this->Form->button('Realizar Orden', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<div>&nbsp;</div>
<h3><span class="glyphicon glyphicon-usd"></span> PRESUPUESTO SUCURSAL</h3>
<table class="table  table-striped table-bordered table-condensed" align="center" style="font-size: 12px;">
    <tr>
        <th>Tipo de Pedido</th>
        <th>Presupuesto Asignado</th>
        <th>Presupuesto Utilizado</th>
        <th>Disponible</th>
    </tr>
    <?php 
        $style = null;
        foreach ($presupuestos as $presupuesto) :
            if(($presupuesto['SucursalesPresupuestosPedido']['presupuesto_asignado'] - $presupuesto['SucursalesPresupuestosPedido']['presupuesto_utilizado']) < 200000){
                $style = 'color: #EE5757;';
            }
    ?>
    <tr>
        <td><?php echo $presupuesto['TipoPedido']['nombre_tipo_pedido']; ?></td>
        <td>$ <?php echo number_format($presupuesto['SucursalesPresupuestosPedido']['presupuesto_asignado'],0,",","."); ?></td>
        <td>$ <?php echo number_format($presupuesto['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",","."); ?></td>
        <td style="<?php echo $style; ?>"><b>$ <?php echo number_format($presupuesto['SucursalesPresupuestosPedido']['presupuesto_asignado'] - $presupuesto['SucursalesPresupuestosPedido']['presupuesto_utilizado'],0,",","."); ?></b></td>
    </tr>
    <?php         
        endforeach; 
    ?>
</table>
<?php
echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.empresa_id')));
// echo $this->Form->input('sucursal_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.sucursal_id')));
echo $this->Form->input('departamento_id', array('type' => 'hidden', 'value' => $sucursales['0']['Empresa']['departamento_id']));
echo $this->Form->input('municipio_id', array('type' => 'hidden', 'value' => $sucursales['0']['Empresa']['municipio_id']));
// echo $this->Form->input('pedido_fecha', array('type' => 'hidden', 'value' => date('Y-m-d')));
// echo $this->Form->input('pedido_hora', array('type' => 'hidden', 'value' => date('h:i:s')));
echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
?>
<?php echo $this->Form->end(); ?>