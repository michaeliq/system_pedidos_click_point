<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2><span class="glyphicon glyphicon-th"></span> PLANTILLA DE PRODUCTOS</h2>
<?php echo $this->Form->create('Plantilla'); ?>
<fieldset>
    <table class="table table-condensed" title="Utilice esta opción para buscar una Plantilla por Empresa">
        <tr>
            <td>Empresa:</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Plantilla', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div>&nbsp;</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Plantilla / Empresa', 'nombre_plantilla'); ?></th>
            <th><?php echo $this->Paginator->sort('Tipo Pedido', 'tipo_pedido_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Detalle Plantilla', 'detalle_plantilla'); ?></th>
            <th><?php echo $this->Paginator->sort('Fecha Creación', 'fecha_creacion'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'estado_plantilla'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($plantillas as $plantilla):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $plantilla['Plantilla']['nombre_plantilla']; ?><br><b><?php echo $plantilla['Empresa']['nombre_empresa']; ?></b></td>
            <td><?php echo $plantilla['TipoPedido']['nombre_tipo_pedido']; ?></td>
            <td><?php echo $plantilla['Plantilla']['detalle_plantilla']; ?></td>
            <td><?php echo $plantilla['Plantilla']['fecha_creacion']; ?></td>
            <td>
                    <?php 
                    if ($plantilla['Plantilla']['estado_plantilla']) {
                        echo $html->image('verde.png');
                        // echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        // echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $plantilla['Plantilla']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($plantilla['Plantilla']['estado_plantilla']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $plantilla['Plantilla']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la empresa %s?', true), $plantilla['Plantilla']['nombre_plantilla']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $plantilla['Plantilla']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la empresa %s?', true), $plantilla['Plantilla']['nombre_plantilla']));
                            }
                            ?>
                </div>
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