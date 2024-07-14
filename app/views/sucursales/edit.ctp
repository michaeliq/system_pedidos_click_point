<?php

echo $this->Html->script(array('sucursales/sucursales_edit')); ?>
<script>
    /*$(function () {
        var availableTags = [
    <?php // echo $regional; ?>
        ];
        $("#SucursaleRegionalSucursal").autocomplete({
            source: availableTags
        });
    });*/
</script> 
<?php echo $this->Form->create('Sucursale'); ?>
<fieldset>
    <legend><?php __('Editar Sucursal para ' . strtoupper($empresa['0']['Empresa']['nombre_empresa'])); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Sucursal: *</td>
            <td><?php echo $this->Form->input('nombre_sucursal', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
            <td>Empresa: </td>
            <td><?php echo $this->Form->input('id_empresa', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false, 'disabled' => true)); ?></td>
        </tr>
        <tr>
            <td>Departamento: *</td>
            <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>Municipio: *</td>
            <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n Sucursal: *</td>
            <td><?php echo $this->Form->input('direccion_sucursal', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
            <td>Tel. Sucursal: *</td>
            <td><?php echo $this->Form->input('telefono_sucursal', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
        </tr>
        <tr>
            <td>Localidad: </td>
            <td><?php echo $this->Form->input('localidad_ruta_id', array('type' => 'select', 'options' => $localidades, 'default' => $localidad , 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>E-mail Sucursal: *</td>
            <td><?php echo $this->Form->input('email_sucursal', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
            <td>Regional: </td>
            <td><?php echo $this->Form->input('regional_id', array('type' => 'select', 'options' => $regionales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?>
                <?php echo $this->Form->input('regional_sucursal', array('type' => 'hidden','value'=>$this->data['Regionale']['nombre_regional'])); ?></td>        
        </tr>
        <tr>
            <td>OI: </td>
            <td><?php echo $this->Form->input('oi_sucursal', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '25')); ?></td>
            <td>CECO: </td>
            <td><?php echo $this->Form->input('ceco_sucursal', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '25')); ?></td>
        </tr>
        <tr>
            <td>Plantilla: *</td>
            <td><?php echo $this->Form->input('plantilla_id2', array('type' => 'select', 'multiple'=>'multiple', 'options' => $plantillas, 'selected' => $selected, 'label' => false)); ?></td>
            <td>Auxiliares: </td>
            <td><?php echo $this->Form->input('numero_auxiliares', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '4')); ?></td>  
        </tr>
        <tr>
            <td colspan="4"><h2>Datos Contacto</h2></td>
        </tr>
        <tr>
            <td>Nombre Contacto: *</td>
            <td><?php echo $this->Form->input('nombre_contacto', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '200')); ?></td>
            <td>Tel. Contacto: *</td>
            <td><?php echo $this->Form->input('telefono_contacto', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
        </tr>
        <tr>
            <td>E-mail Contacto: *</td>
            <td><?php echo $this->Form->input('email_contacto', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">
                <table class="table table-hover" align="center">
                    <tr>
                        <th colspan="4" class="text-center">ASGINAR PRESUPUESTOS A SUCURSAL</th>
                    </tr>
                    <tr>
                        <th class="text-center">Tipo de Pedido</th>
                        <th class="text-center">Presupuesto Asignado</th>
                        <th class="text-center">Presupuesto Utilizado</th>
                        <th class="text-center">Fecha Asignación</th>
                    </tr>
                    <?php   foreach ($tipo_pedidos as $tipo_pedido) : 
                                $asignado = 0;
                                $utilizado = 0;
                                $fecha = null;
                                $presupuesto_id = null;
                                foreach ($presupuestos as $presupuesto) :
                                    if($presupuesto['SucursalesPresupuestosPedido']['tipo_pedido_id'] == $tipo_pedido['TipoPedido']['id']){
                                        $presupuesto_id = $presupuesto['SucursalesPresupuestosPedido']['id'];
                                        $asignado = $presupuesto['SucursalesPresupuestosPedido']['presupuesto_asignado'];
                                        $utilizado = $presupuesto['SucursalesPresupuestosPedido']['presupuesto_utilizado'];
                                        $fecha = $presupuesto['SucursalesPresupuestosPedido']['fecha_presupuesto_pedido'];
                                    }
                                endforeach;    
                    ?>
                    <tr>
                        <td class="text-center"><b><?php echo $tipo_pedido['TipoPedido']['nombre_tipo_pedido']; ?></b></td>
                        <td class="text-center" ><?php echo $this->Form->input('presupuesto_asignado_'.$tipo_pedido['TipoPedido']['id'], array('type' => 'text', 'label' => false, 'maxlength' => '12','value'=>$asignado));  ?></td>
                        <td class="text-center">$ <?php echo number_format($utilizado,0,",",".");; ?></td>
                        <td class="text-center"><?php echo $fecha; ?></td>
                    </tr>
                    <?php
                            echo $this->Form->input('presupuesto_id_'.$tipo_pedido['TipoPedido']['id'], array('type' => 'hidden', 'value' => $presupuesto_id));
                            echo $this->Form->input('tipo_pedido_id_'.$tipo_pedido['TipoPedido']['id'], array('type' => 'hidden', 'value' => $tipo_pedido['TipoPedido']['id']));
                            endforeach;
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Sucursal', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>

        <?php
        echo $this->Form->input('id_empresa', array('type' => 'hidden'));
        echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
    </table>

</fieldset>
<?php echo $this->Form->end(); ?>