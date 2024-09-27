<?php

    echo $this->Session->read('Auth.User.sucursal_id');
    echo $this->Html->script(array('pedidos/orden_pedido.js?var='.date('dymhmis')));
    echo $this->Html->script(array('pedidos/fechas_entrega')); 
    
    $fecha_entrega_1 = null;
    $fecha_entrega_2 = null;
    if($this->Session->read('Auth.User.empresa_id')=='104'){
        $fecha_entrega_1 = date('Y-m-d');
        $fecha_entrega_2 = date('Y-m-d', strtotime($fecha_entrega_1. ' + 5 days'));
        
        $dayOfWeek = date('N', strtotime($fecha_entrega_2));
        if($dayOfWeek == '5')
            $fecha_entrega_2 = date('Y-m-d', strtotime(date('Y-m-d'). ' + 6 days'));
        if($dayOfWeek == '6')
            $fecha_entrega_2 = date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
    }
    if($this->Session->read('Auth.User.empresa_id')=='106' || $this->Session->read('Auth.User.empresa_id')=='107'){
        $fecha_entrega_1 = date('Y-m-d');
        $fecha_entrega_2 = date('Y-m-d', strtotime($fecha_entrega_1. ' + 60 days'));
        
        $dayOfWeek = date('N', strtotime($fecha_entrega_2));
        if($dayOfWeek == '5')
            $fecha_entrega_2 = date('Y-m-d', strtotime(date('Y-m-d'). ' + 61 days'));
        if($dayOfWeek == '6')
            $fecha_entrega_2 = date('Y-m-d', strtotime(date('Y-m-d'). ' + 62 days'));
    }
    
$meses_pedidos = array('0' => 'INSTALACION');
$meses = array('1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
$mes_actual = date('n');
/* echo date('Y-m-d H:i:s');
echo "<br>";
echo date('n');
echo "<br>";
echo date('n', strtotime('+28 days')); */
$mes_siguiente = date('n', strtotime('+1 month'));
//$mes_siguiente = date('n', strtotime('+28 days'));
$mes_siguiente_2 = date('n', strtotime('+2 month'));
$meses_pedidos[$mes_actual] = $meses[$mes_actual];
$meses_pedidos[$mes_siguiente] = $meses[$mes_siguiente];
$meses_pedidos[$mes_siguiente_2] = $meses[$mes_siguiente_2];

$clasificacion = array('Tarifa integral'=>'Tarifa integral','Facturacion sobre pedido'=>'Facturacion sobre pedido');
    ?>
<h2><span class="glyphicon glyphicon-shopping-cart"></span> ORDENES DE PEDIDO - REALIZAR ORDEN</h2>
<?php echo $this->Form->create('Pedido'); ?>
<table class="table table-striped table-bordered table-condensed" align="center">
    <?php if (count($pedidos) > 0) { ?>
    <tr>
        <td colspan="4">
            <!--            <h2>ORDENES EN PROCESO</h2>-->
            <div class="text-center"><b>Haga click en el icono <div class="glyphicon glyphicon-arrow-right"></div> para continuar.</b></div>
                <?php // print_r($pedidos);?>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th>No. Orden</th>
                    <th>Datos Empresa</th>
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
                    <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal'];?></td>
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
    }//else{
    ?>
    <tr>
        <td colspan="4">Por favor verifique que los siguientes datos sean correctos para el pedido:</td>
    </tr>
     <?php 
        if($this->Session->read('Auth.User.cambiar_sucursal')){
        ?>
    <tr>
        <td>Empresa:</td>
        <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false));?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td colspan="3"><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', /*'options' => $regional,*/ 'empty' => 'Seleccione una Opción', 'label' => false));?></td>
    </tr>
    <tr>
        <td>Sucursal:</td>
        <td colspan="3"><?php echo $this->Form->input('sucursal_id', array('type' => 'select',/* 'options' => $sucursales1,*/ 'empty' => 'Seleccione una Opción', 'label' => false, 'style' => 'max-width:50%;'));?></td>
    </tr>
    <tr>
    <td>Consecutivo:</td>
    <td colspan="3"><?php echo $this->Form->input("consecutivo_id",array("type" => "select", "options" => $consecutivos, "empty" => "Selecciona una opción", "label" => false, "required"=> true)) ?></td>
    </tr>
<?php }else{ ?>
    <tr>
        <td>Empresa:</td>
        <td colspan="3"><?php echo $sucursales['0']['Empresa']['nombre_empresa']; 
        echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.empresa_id')));?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td><?php echo $sucursales['0']['Sucursale']['regional_sucursal']; ?></td>
        <td>Sucursal:</td>
        <td><?php echo $sucursales['0']['Sucursale']['nombre_sucursal']; echo $this->Form->input('sucursal_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.sucursal_id')));?></td>
    </tr>
        <?php } ?>
    <tr>
        <td>Departamento:</td>
        <td id="data-departamento"><?php echo $sucursales['0']['Departamento']['nombre_departamento']; ?></td>
        <td>Municipio:</td>
        <td id="data-municipio"><?php echo $sucursales['0']['Municipio']['nombre_municipio']; ?></td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Envio: *</td>
        <td><?php echo $this->Form->input('pedido_direccion', array('type' => 'text', 'size'=>'60','label' => false, 'value' => $sucursales['0']['Sucursale']['direccion_sucursal'])); ?></td>
        <td>Tel. Contacto: *</td>
        <td><?php echo $this->Form->input('pedido_telefono', array('type' => 'text', 'label' => false, 'value' => $sucursales['0']['Sucursale']['telefono_sucursal'])); ?></td>
    </tr>
    <tr>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <?php if($this->Session->read('Auth.User.rol_id') == '1' || $this->Session->read('Auth.User.rol_id') == '8' || $this->Session->read('Auth.User.rol_id') == '12'){?>
        <td>Tipo de Movimiento Salida: </td>
        <td><?php echo $this->Form->input('tipo_movimiento_id', array('type' => 'select', 'options' => $tipoMovimientos, 'empty' => 'Seleccione una Opción','default'=>'7', 'label' => false)); ?></td>
        <?php }else{ ?>
        <td>&nbsp;</td>
        <td>&nbsp; <?php echo $this->Form->input('tipo_movimiento_id', array('type' => 'hidden', 'value' => $tipoMovimientos)); ?></td>
        <?php } ?>
    </tr>
<!--    <tr>
        <td>Categor&iacute;a del Pedido:</td>
        <td colspan="3"><?php // echo $this->Form->input('tipo_categoria_id2', array('type' => 'select', 'multiple'=>'multiple','options' => $tipoCategoria, 'label' => false, 'title'=>'Para seleccionar varios, presione la tecla Control y con el puntero del mouse, seleccione los elementos.')); //05032018 ?></td>
    </tr>-->
    <tr>
        <td><b>Fecha de Entrega: </b></td>
        <td><?php echo $this->Form->input('fecha_entrega_1', array('type' => 'text', 'onkeydown'=>'return false', 'value'=>$fecha_entrega_1, 'label' => false,'maxlength'=>'10','placeholder'=>'Desde ... ','required'=>true)); ?> <br> 
            <?php echo $this->Form->input('fecha_entrega_2', array('type' => 'text', 'onkeydown'=>'return false', 'value'=>$fecha_entrega_2, 'label' => false,'maxlength'=>'10','placeholder'=>'Hasta ... ','required'=>true)); ?></td>
        <?php if($this->Session->read('Auth.User.rol_id') == '1' || $this->Session->read('Auth.User.rol_id') == '2' || $this->Session->read('Auth.User.rol_id') == '8' || $this->Session->read('Auth.User.rol_id') == '12'){?>
        <td><b>Mes Pedido: </b><br><br>
            <b>Clasificación:</b></td>
        <td><?php echo $this->Form->input('mes_pedido', array('type' => 'select', 'options' => $meses_pedidos, 'label' => false, 'empty'=>'Seleccione una Opción','required'=>true)); //2021-07-28 ?><br>
            <?php echo $this->Form->input('clasificacion_pedido', array('type' => 'select', 'options' => $clasificacion, 'label' => false, 'empty'=>'Seleccione una Opción','required'=>true)); //2021-07-28 ?></td>
        <?php }else{ ?>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <?php } ?>
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
    //}
    ?>
</table>
<div>&nbsp;</div>
     <?php 
        if(!$this->Session->read('Auth.User.cambiar_sucursal')){
        ?>
<h3><span class="glyphicon glyphicon-usd"></span> PRESUPUESTO SUCURSAL</h3>
<table class="table  table-striped table-bordered table-condensed" align="center" style="font-size: 12px;">
    <tr>
        <th>Tipo de Pedido</th>
        <th>Presupuesto Asignado</th>
        <th>Presupuesto Utilizado</th>
        <th>Disponible</th>
    </tr>
    <?php 
// print_r($presupuestos);
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
        }
// echo $this->Form->input('sucursal_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.sucursal_id')));
echo $this->Form->input('departamento_id', array('type' => 'hidden', 'value' => $sucursales['0']['Empresa']['departamento_id']));
echo $this->Form->input('municipio_id', array('type' => 'hidden', 'value' => $sucursales['0']['Empresa']['municipio_id']));
// echo $this->Form->input('pedido_fecha', array('type' => 'hidden', 'value' => date('Y-m-d')));
// echo $this->Form->input('pedido_hora', array('type' => 'hidden', 'value' => date('h:i:s')));
echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
?>
<?php echo $this->Form->end(); ?>