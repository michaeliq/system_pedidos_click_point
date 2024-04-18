<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-phone"></span> VENDEDORES</h2>
<?php echo $this->Form->create('Vendedore'); ?>
<fieldset>
    <table class="table table-condensed" title="Utilice esta opción para buscar una Vendedor">
        <tr>
            <td><b>Vendedor:</b></td>
            <td><?php echo $this->Form->input('nombre_vendedor', array('type' => 'text','label' => false, 'size' => '50', 'maxlength' => '105')); ?></td>
            <td><b>No. Identificación:</b></td>
            <td><?php echo $this->Form->input('no_identificacion', array('type' => 'text','label' => false, 'size' => '50', 'maxlength' => '105')); ?></td>
            <td><b>Estado:</b></td>
            <td><?php echo $this->Form->input('estado_vendedor', array('type' => 'select', 'options' => $estados, 'empty' => 'Todos', 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Vendedor', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('ID', 'id'); ?></th>
            <th><?php echo $this->Paginator->sort('No. Identificación', 'no_identificacion'); ?></th>
            <th><?php echo $this->Paginator->sort('Vendedor', 'nombre_vendedor'); ?></th>            
            <th><?php echo $this->Paginator->sort('Direción', 'direccion_vendedor'); ?></th>            
            <th><?php echo $this->Paginator->sort('Celular', 'telefono_vendedor'); ?></th>            
            <th><?php echo $this->Paginator->sort('E-mail', 'correo_vendedor'); ?></th>            
            <th><?php echo $this->Paginator->sort('Estado', 'estado_vendedor'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($vendedores as $vendedor):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
	    <td><?php echo $vendedor['Vendedore']['id']; ?></td>
            <td><?php echo $vendedor['Vendedore']['no_identificacion']; ?></td>
            <td><?php echo $vendedor['Vendedore']['nombre_vendedor']; ?></td>
            <td><?php echo $vendedor['Vendedore']['direccion_vendedor']; ?></td>
            <td><?php echo $vendedor['Vendedore']['telefono_vendedor']; ?></td>
            <td><?php echo $vendedor['Vendedore']['correo_vendedor']; ?></td>
            <td>
                    <?php
                    if ($vendedor['Vendedore']['estado_vendedor']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $vendedor['Vendedore']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($vendedor['Vendedore']['estado_vendedor']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $vendedor['Vendedore']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la regional %s?', true), $vendedor['Vendedore']['nombre_vendedor']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $vendedor['Vendedore']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la regional %s?', true), $vendedor['Vendedore']['nombre_vendedor']));
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