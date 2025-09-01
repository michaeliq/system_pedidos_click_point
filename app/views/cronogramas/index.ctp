<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-calendar"></span> CRONOGRAMAS</h2>
<?php echo $this->Form->create('Cronograma'); ?>
<fieldset>
    <table class="table table-condensed" title="Utilice esta opción para buscar una Cronograma por Empresa">
        <tr>
            <td>Empresa:</td>
            <td><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>

            <td>Tipo Pedido: </td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Todos', 'label' => false)); ?></td>

            <td>Estado: </td>
            <td><?php echo $this->Form->input('estado_cronograma', array('type' => 'select', 'options' => $estados, 'empty' => 'Todos', 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Cronograma', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Cronograma Masivo', true), array('action' => 'add_masive_schedule'), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Cronograma', 'nombre_cronograma'); ?></th>
            <th><?php echo $this->Paginator->sort('Empresa', 'empresa_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Tipo Pedido', 'tipo_pedido_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Inicio', 'fecha_inicio'); ?></th>
            <th><?php echo $this->Paginator->sort('Fin', 'fecha_fin'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'estado_cronograma'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($cronogramas as $cronograma):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
  //  print_r($tipo_pedido); 
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $cronograma['Cronograma']['nombre_cronograma']; ?></td>
            <td><?php echo $cronograma['Empresa']['nombre_empresa']; ?></td>
            <td><?php foreach ($tipo_pedido as $key => $value) {
                        if(strpbrk( $cronograma['Cronograma']['tipo_pedido_id_2'],$key ))
                            echo $value;
                            echo "<br>";
                        }            
                        // echo $cronograma['TipoPedido']['nombre_tipo_pedido']; ?>
            </td>
            <td><?php echo $cronograma['Cronograma']['fecha_inicio']; ?></td>
            <td><?php echo $cronograma['Cronograma']['fecha_fin']; ?></td>
            <td>
                    <?php
                    if ($cronograma['Cronograma']['estado_cronograma']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $cronograma['Cronograma']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($cronograma['Cronograma']['estado_cronograma']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $cronograma['Cronograma']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el cronograma %s?', true), $cronograma['Cronograma']['nombre_cronograma']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $cronograma['Cronograma']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el cronograma %s?', true), $cronograma['Cronograma']['nombre_cronograma']));
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