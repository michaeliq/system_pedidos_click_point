<?php

echo $this->Html->script(array('empresas/empresas_edit')); ?>
<?php echo $this->Form->create('Empresa'); ?>
<fieldset>
    <legend><?php __('Editar Empresa'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Contrato: </b></td>
            <td> <?php echo $this->Form->input('contrato_empresa', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
            <td>Sector:</td>
            <td><?php echo $this->Form->input('sector_id', array('type' => 'select', 'options' => $sectores, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>CECO: </td>
            <td><?php echo $this->Form->input('ceco_empresa', array('type' => 'text', 'label' => false, 'maxlength' => '20','maxlength' => '20')); ?></td>
            <td># Auxiliares:</td>
            <td><?php echo $this->Form->input('auxiliares_empresa', array('type' => 'text', 'label' => false, 'size' => '20', 'maxlength' => '4')); ?></td>
        </tr>
        <tr>
            <td>Nit. Empresa: *</td>
            <td><?php echo $this->Form->input('nit_empresa', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
            <td>Empresa: *</td>
            <td><?php echo $this->Form->input('nombre_empresa', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
        </tr>
        <tr>
            <td>Departamento: *</td>
            <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>Municipio: *</td>
            <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n Empresa: *</td>
            <td><?php echo $this->Form->input('direccion_empresa', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
            <td>Tel. Empresa: *</td>
            <td><?php echo $this->Form->input('telefono_empresa', array('type' => 'text', 'label' => false, 'maxlength' => '20')); ?></td>
        </tr>
        <tr>
            <td>E-mail Empresa: *</td>
            <td><?php echo $this->Form->input('email_empresa', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '120')); ?></td>
<!--            <td>Asesor Encargado: *</td>
            <td><?php // echo $this->Form->input('user_id', array('type' => 'select', 'options' => $users, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>-->
            <td>Vendedor Encargado: *</td>
            <td><?php echo $this->Form->input('vendedor_id', array('type' => 'select', 'options' => $vendedores, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td colspan="2">Encabezado para PDF:</td>
            <td colspan="2"><?php echo $this->Form->input('membrete_pdf', array('type' => 'text', 'label' => false, 'size' => '75', 'maxlength' => '200')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><b>Parametro de precio a usar en pedidos: * <br>Relacionado a las plantillas</b></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_precio', array('type' => 'select', 'options' => $parametro_precio, 'label' => false)); //31052018 ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><b>Parametro tiempo m&aacute;ximo en pedidos: *</b> <br><span style="font-size: x-small;">Pedidos en Pendiente Aprobaci&oacute;n</span></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_tiempo_pedido', array('type' => 'text', 'label' => false, 'maxlength' => '2','placeholder'=>'Tiempo en D�as')); ?> D&iacute;as</td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><b>Parametro IVA en Pesupuesto: *</b> <br><span style="font-size: x-small;">Calculo de IVA en el presupuesto</span></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_presupuesto_iva', array('type' => 'checkbox')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><b>Parametro Encuesta en pedidos: *</b> <br><span style="font-size: x-small;">Diligenciar encuesta antes de pedido</span></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_encuesta', array('type' => 'checkbox')); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><b>Parametro Cronograma: *</b> <br><span style="font-size: x-small;">Debe tener cronograma para crear pedidos</span></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_cronograma', array('type' => 'checkbox')); ?></td>
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
            <td>Contaseña para reestablecer: *</td>
            <td><?php echo $this->Form->input('restore_pass', array('type' => 'text', 'label' => false, 'size' => '50', 'maxlength' => '10')); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Empresa', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>