<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-globe"></span> REGIONALES <?php echo $empresa; ?></h2>
<?php echo $this->Form->create('Regionale'); ?>
<fieldset>
    <table class="table table-condensed" title="Utilice esta opción para buscar una Regional">
        <tr>
            <td><b>Regional:</b></td>
            <td><?php echo $this->Form->input('nombre_regional', array('type' => 'text','label' => false, 'size' => '50', 'maxlength' => '105')); ?></td>
            <td><b>Estado:</b></td>
            <td><?php echo $this->Form->input('estado_regional', array('type' => 'select', 'options' => $estados, 'empty' => 'Todos', 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Regional', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Regional', 'nombre_regional'); ?></th>
            <th><?php echo $this->Paginator->sort('Fecha Creación', 'fecha_inicio'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'estado_regional'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($regionals as $regional):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $regional['Regionale']['nombre_regional']; ?></td>
            <td><?php echo $regional['Regionale']['fecha_creacion']; ?></td>
            <td>
                    <?php
                    if ($regional['Regionale']['estado_regional']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $regional['Regionale']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($regional['Regionale']['estado_regional']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $regional['Regionale']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la regional %s?', true), $regional['Regionale']['nombre_regional']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $regional['Regionale']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la regional %s?', true), $regional['Regionale']['nombre_regional']));
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