<?php

/* 
  proveedor_id integer NOT NULL,
  tipo_estado_orden_id integer NOT NULL,
  fecha_orden_compra timestamp without time zone,
  empresa_id integer,
  tipo_formas_pago_id integer,
  rte_fuente integer,
  rte_ica integer,
  tipo_orden_id integer,
  observaciones text,
  user_id_elaboro integer,
  user_id_aprobo integer,
  fecha_elaboracion timestamp without time zone,
  user_id integer
  */
          ?>
<div class="movimientosEntradas form">
<?php echo $this->Form->create('OrdenCompra');?>
    <fieldset>
        <legend><span class="glyphicon glyphicon-tags"></span>  <?php __('NUEVA ORDEN DE COMPRA'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center" style="width: 70%;">
            <tr>
                <td><b>Proveedor: *</b></td>
                <td><?php echo $this->Form->input('proveedor_id', array('type' => 'select', 'options' => $proveedores, 'label' => false, 'empty'=>'Seleccione una Opción')); ?></td>
            </tr>   

            <tr>
                <td><b>Forma de Pago:</b></td>
                <td><?php echo $this->Form->input('tipo_formas_pago_id', array('type' => 'select', 'options' => $tipoFormasPagos, 'label' => false, 'empty'=>'Seleccione una Opción')); ?>
            </tr>   
            <tr>
                <td><b>% ReteFuente:</b></td>
                <td><?php echo $this->Form->input('rte_fuente', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20','placeholder'=>'Ej: 0.16 => (16%)','title'=>'Utilice el punto (0.00) como separador decimal. No utilizar simbolo de porcentaje.')); ?></td>
            </tr>   

            <tr>
                <td><b>% ReteICA:</b></td>
                <td><?php echo $this->Form->input('rte_ica', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '20','placeholder'=>'Ej: 0.08 => (8%)','title'=>'Utilice el punto (0.00) como separador decimal.')); ?></td><td></td>
            </tr>
            <tr>
                <td><b>Tipo de Orden: *</b></td>
                <td><?php echo $this->Form->input('tipo_orden_id', array('type' => 'select', 'options' => $tipoOrden, 'label' => false, 'empty'=>'Seleccione una Opción')); ?> </td>            
            </tr>   
            <tr>
                <td><b>Observaciones:</b></td>
                <td><?php echo $this->Form->input('observaciones', array('type' => 'textarea', 'label' => false,'rows'=>'3','cols'=>'80','placeholder'=>'Digite sus observaciones generales de la orden.')); ?></td>
            </tr>

            <tr>
                <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning','onclick'=>'history.back();')); ?>
                <?php echo $this->Form->button('Registrar', array('type' => 'submit', 'class' => 'btn btn-success','id'=>'registrarOrden')); ?>
                </td>
            </tr>
        </table>
    </fieldset>
<?php echo $this->Form->end();?>
</div>
