<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-calendar"></span> ENCUESTAS</h2>
<?php echo $this->Form->create('Encuesta'); ?>
<fieldset>
    <table class="table table-condensed" title="Utilice esta opción para buscar una Encuesta por Empresa">
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
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Encuesta', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Encuesta', 'nombre_encuesta'); ?></th>
            <th><?php echo $this->Paginator->sort('Empresa', 'empresa_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Fecha', 'fecha_creacion'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'estado_encuesta'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($encuestas as $encuesta):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $encuesta['Encuesta']['nombre_encuesta']; ?></td>
            <td><?php echo $encuesta['Empresa']['nombre_empresa']; ?></td>
            <td><?php echo $encuesta['Encuesta']['fecha_creacion']; ?></td>
            <td>
                    <?php
                    if ($encuesta['Encuesta']['estado_encuesta']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div clas="preguntas" title="Preguntas"><?php echo $this->Html->link(__('', true), array('action' => 'preguntas', $encuesta['Encuesta']['id']), array('class' => 'glyphicon glyphicon-question-sign', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $encuesta['Encuesta']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($encuesta['Encuesta']['estado_encuesta']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $encuesta['Encuesta']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el cronograma %s?', true), $encuesta['Encuesta']['nombre_encuesta']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $encuesta['Encuesta']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el cronograma %s?', true), $encuesta['Encuesta']['nombre_encuesta']));
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