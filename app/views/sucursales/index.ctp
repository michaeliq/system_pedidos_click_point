<?php

echo $this->Html->script(array('sucursales/sucursales_index')); ?>
<h2>SUCURSALES DE <?php echo strtoupper($empresa['0']['Empresa']['nombre_empresa']); ?></h2>
<?php echo $this->Form->create('Sucursale', array('url' => array('controller' => 'sucursales', 'action' => 'index/'.$id_empresa))); ?>
<table class="table table-condensed">
    <tr>
        <td><b>Sucursal:</b></td>
        <td><?php echo $this->Form->input('nombre_sucursal', array('type' => 'text', 'size' => '30', 'maxlength' => '60', 'label' => false)); ?></td>
        <td><b>OI:</b></td>
        <td><?php echo $this->Form->input('oi_sucursal', array('type' => 'text', 'size' => '10', 'maxlength' => '12', 'label' => false)); ?></td>
        <td><b>CECO:</b></td>
        <td><?php echo $this->Form->input('ceco_sucursal', array('type' => 'text', 'size' => '10', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Regional:</b></td>
        <td colspan="3"><?php echo $this->Form->input('regional_id', array('type' => 'select', 'options' => $regionales, 'empty' => 'Todas', 'label' => false)); ?></td><!-- 31052018 --> 
        <td><b>Plantillas:</b></td>
        <td><?php echo $this->Form->input('plantilla_id', array('type' => 'select', 'options' => $plantillas, 'empty' => 'Todas', 'label' => false)); ?></td>
    </tr>
</tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva sucursal', true), array('action' => 'add', $id_empresa), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('OI', 'oi_sucursal'); ?></th>
            <th><?php echo $this->Paginator->sort('CECO', 'ceco_sucursal'); ?></th>
            <th><?php echo $this->Paginator->sort('Regional', 'regional_sucursal'); ?></th>
            <th><?php echo $this->Paginator->sort('Municipio', 'municipio_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Sucursal', 'nombre_sucursal'); ?></th>
            <th><?php echo $this->Paginator->sort('Dirección', 'direccion_sucursal'); ?></th>
            <!--<th><?php // echo $this->Paginator->sort('Teléfono', 'telefono_sucursal'); ?></th>
            <th><?php /*echo $this->Paginator->sort('Contacto', 'nombre_contacto'); ?></th>
            <th><?php echo $this->Paginator->sort('Tel. Contacto', 'telefono_contacto'); ?></th>
            <th><?php echo $this->Paginator->sort('E-mail', 'email_contacto'); */?></th>-->
            <th><?php echo $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($sucursals as $sucursal):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $sucursal['Sucursale']['oi_sucursal']; ?></td>
            <td><?php echo $sucursal['Sucursale']['ceco_sucursal']; ?></td>
            <td><?php echo $sucursal['Sucursale']['regional_sucursal']; ?></td>
            <td><?php echo $sucursal['Municipio']['nombre_municipio']; ?></td>
            <td><?php echo $sucursal['Sucursale']['nombre_sucursal']; ?></td>
            <td style="width: 30%"><?php echo $sucursal['Sucursale']['direccion_sucursal']; ?></td>
            <!--<td><?php // echo $sucursal['Sucursale']['telefono_sucursal']; ?></td>
            <td><?php /* echo $sucursal['Sucursale']['nombre_contacto']; ?></td>
                <td><?php echo $sucursal['Sucursale']['telefono_contacto']; ?></td>
                <td><?php echo $sucursal['Sucursale']['email_contacto']; */?></td>-->
            <td>
                    <?php
                    if ($sucursal['Sucursale']['estado_sucursal']) {
                        echo $html->image('verde.png');
                        // echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        // echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="presupuesto" title="Presupuesto"><?php //echo $this->Html->link(__('', true), array('action' => 'presupuesto', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-usd', 'escape' => false)); ?></div>
                <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                    <?php if ($sucursal['Sucursale']['id'] > 1) { ?>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($sucursal['Sucursale']['estado_sucursal']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la sucursal %s?', true), $sucursal['Sucursale']['nombre_sucursal']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la sucursal %s?', true), $sucursal['Sucursale']['nombre_sucursal']));
                            }
                            ?>
                </div>
                    <?php } ?>
            </td>
        </tr>    
        <?php endforeach; ?>
    </table>
</div>
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
<div>&nbsp;</div>
<div class="text-center"><?php echo $this->Form->button('Regresar a Empresas', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?></div>