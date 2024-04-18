<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$empresas_sin_aprobador_array = array();
foreach ($empresas_sin_aprobadores as $empresas_sin_aprobador):
    array_push($empresas_sin_aprobador_array, $empresas_sin_aprobador['0']['id']);
endforeach;
?>
<?php echo $this->Html->script(array('empresas/empresas')); ?>
<h2><span class="glyphicon glyphicon-asterisk"></span> EMPRESAS</h2>
<?php echo $this->Form->create('Empresa', array('url' => array('controller' => 'empresas', 'action' => '/index'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Nit:</b></td>
        <td><?php echo $this->Form->input('nit_empresa', array('type' => 'text', 'size' => '20', 'maxlength' => '20', 'label' => false)); ?></td>
        <td><b>Empresa:</b></td>
        <td><?php echo $this->Form->input('nombre_empresa', array('type' => 'text', 'size' => '60', 'maxlength' => '102', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Dirección:</b></td>
        <td><?php echo $this->Form->input('direccion_empresa', array('type' => 'text', 'size' => '60', 'maxlength' => '102', 'label' => false)); ?></td>
        <td><b>Contacto:</b></td>
        <td><?php echo $this->Form->input('nombre_contacto', array('type' => 'text', 'size' => '60', 'maxlength' => '102', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Departamento:</b></td>
        <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td><b>Municipio:</b></td>
        <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Empresa sin aprobador:</b></td>
        <td><?php echo $this->Form->input('empresa_aprobadores', array('type' => 'checkbox', 'label' => '<span class="glyphicon glyphicon-thumbs-up" title="Empresa sin aprobador asignado" style="color:red;"></span> Empresa sin aprobadores', 'checked' => false)); ?></td>
        <td><b>Estado:</b></td>
        <td><?php echo $this->Form->input('estado_empresa', array('type' => 'select', 'options' => $estados, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Contrato:</b></td>
        <td><?php echo $this->Form->input('contrato_empresa', array('type' => 'text', 'size' => '20', 'maxlength' => '20', 'label' => false)); ?></td>
        <td colspan="2"></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<?php echo $this->Form->create('Empresa', array('url' => array('controller' => 'empresas', 'action' => 'delete'))); ?>
<?php if ($this->Session->read('Auth.User.rol_id') == '1') { ?>
<div class="text-center">
        <?php echo $this->Form->button('CAMBIAR DE ESTADO MASIVAMENTE', array('type' => 'submit', 'class' => 'btn btn-info  btn-xs','onclick'=>"confirm('Esta seguro de cambiar el estado de estas empresas?. Recuerde que se cambiará el estado de regionales y sucursales');")); ?>
</div><br>
<?php } ?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Empresa', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th><?php echo $this->Paginator->sort('Nit', 'nit_empresa'); ?></th>
            <th><?php echo $this->Paginator->sort('Empresa', 'nombre_empresa'); ?></th>
            <th><?php echo $this->Paginator->sort('Dirección', 'direccion_empresa'); ?></th>
            <th><?php echo $this->Paginator->sort('Teléfono', 'telefono_empresa'); ?></th>
            <!--<th><?php // echo $this->Paginator->sort('Contacto', 'nombre_contacto');      ?></th>
            <th><?php // echo $this->Paginator->sort('Tel. Contacto', 'telefono_contacto');      ?></th>-->
            <th><?php echo $this->Paginator->sort('E-mail', 'email_empresa'); ?></th>
            <!--<th><?php // echo $this->Paginator->sort('Asesor Encargado', 'user_id'); ?></th>-->
            <th><?php echo $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($empresas as $empresa):
    $aprobador = '';
    $title = "Empresa con aprobadores asignados";
    if(array_search($empresa['Empresa']['id'],$empresas_sin_aprobador_array)){
        $aprobador = "color:red;";
        $title = "Empresa sin aprobador asignado";
    }
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $this->Form->input($empresa['Empresa']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $empresa['Empresa']['id'])); ?></td>
            <td><?php echo $empresa['Empresa']['nit_empresa']; ?></td>
            <td><?php echo $empresa['Empresa']['nombre_empresa']; ?><?php if(!empty($empresa['Empresa']['contrato_empresa'])){ ?> <br><b><?php echo $empresa['Empresa']['contrato_empresa']; ?></b><?php } ?></td>
            <td><?php echo $empresa['Departamento']['nombre_departamento']; ?> - <?php echo $empresa['Municipio']['nombre_municipio']; ?><br><?php echo $empresa['Empresa']['direccion_empresa']; ?></td>
            <td><?php echo $empresa['Empresa']['telefono_empresa']; ?></td>
            <!--<td><?php // echo $empresa['Empresa']['nombre_contacto'];      ?></td>
            <td><?php // echo $empresa['Empresa']['telefono_contacto'];      ?></td>-->
            <td><?php echo $empresa['Empresa']['email_empresa']; ?></td>
            <!--<td title="<?php // echo $empresa['User']['email_persona']; ?>"><?php //echo $empresa['User']['nombres_persona']; ?></td> -->
            <td>
                    <?php
                    if ($empresa['Empresa']['estado_empresa']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td>
                <div class="edit" title="Aprobadores" <?php echo $aprobador;?>><?php echo $this->Html->link(__('', true), array('action' => 'aprobadores', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-thumbs-up', 'escape' => false, 'title'=>$title,'style'=>$aprobador)); ?></div>
                <div class="edit" title="Regionales" <?php echo $aprobador;?>><?php echo $this->Html->link(__('', true), array('action' => 'index', $empresa['Empresa']['id'], 'controller' => 'regionales'), array('class' => 'glyphicon glyphicon-globe', 'escape' => false)); ?></div>
                <div class="edit" title="Sucursales"><?php echo $this->Html->link(__('', true), array('controller' => 'sucursales', 'action' => 'index', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-bookmark', 'escape' => false)); ?></div>
                <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                    <?php if ($empresa['Empresa']['id'] > 1) { ?>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($empresa['Empresa']['estado_empresa']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar la empresa %s?', true), $empresa['Empresa']['nombre_empresa']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $empresa['Empresa']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la empresa %s?', true), $empresa['Empresa']['nombre_empresa']));
                            }
                            ?>
                </div>
                    <?php } ?>
            </td>
        </tr>    
        <?php endforeach; ?>
    </table>
</div>
<?php echo $this->Form->end(); ?>
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