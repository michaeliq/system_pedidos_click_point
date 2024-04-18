<?php

echo $this->Html->script(array('users/users_index')); ?>
<h2><span class="glyphicon glyphicon-user"></span> USUARIOS</h2>
<?php echo $this->Form->create('User', array('action' => "index/")); ?>
<table class="table table-condensed" title="Utilice esta opción para buscar un Usuario">
    <tr>
        <td><b>Usuario:</b></td>
        <td><?php echo $this->Form->input('username_f', array('type' => 'text', 'label' => false)); ?></td>
        <td><b>Nombres:</b></td>
        <td><?php echo $this->Form->input('nombres_persona_f', array('type' => 'text', 'label' => false)); ?></td>
        <td><b>No. Identificaci&oacute;n:</td>
        <td><?php echo $this->Form->input('no_identificacion_persona_f', array('type' => 'text', 'label' => false)); ?></td>
  <!--        <td>Apellidos:</td>
          <td><?php // echo $this->Form->input('apellidos_persona_f', array('type' => 'text', 'label' => false)); ?></td>-->
    </tr>
    <tr>
        <td><b>Rol:</b></td>
        <td><?php echo $this->Form->input('rol_id_f', array('type' => 'select', 'options' => $roles, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>    
        <td><b>Estado:</b></td>
        <td><?php echo $this->Form->input('estado_f', array('type' => 'select', 'options' => $estados, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>    
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Empresa:</b></td>
        <td colspan="5"><?php echo $this->Form->input('empresa_id_f', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td><b>Sucursal:</b></td>
        <td colspan="5"><?php echo $this->Form->input('sucursal_id_f', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>        
    </tr>
    <tr>
        <td><b>¿Puede cambiar sucursal?:</b></td>
        <td><?php echo $this->Form->input('cambiar_sucursal', array('type' => 'checkbox', 'label' => false,'div'=>false)); ?></td>
        <td><b>Precios de Plantillas:</b></td>
        <td><?php echo $this->Form->input('parametro_precio', array('type' => 'select', 'options' => $parametro_precio, 'label' => false)); //31052018 ?></td>
    </tr>
</table>
<div class="text-center"><?php echo $this->Form->button('<i class="icon-search icon-white"></i> Filtrar', array('type' => 'submit', 'class' => 'btn btn-success')); ?></div>
<?php echo $this->Form->end(); ?>
<?php
if($filtro == true){
    $empresa = 'de '.$users['0']['Empresa']['nombre_empresa'];
    $id_empresa = $users['0']['Empresa']['id'];
}else{
    $empresa = null;
    $id_empresa = null;
}

?>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Usuario', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="estados_digitadores" title="Cambia el estado a todos los usuarios que tengan el rol de digitadores"><?php 
echo $this->Html->link(__('<i class="glyphicon glyphicon-dashboard"></i> Cambiar Estado a Digitadores '.$empresa.'', true), array('action' => 'estados_digitadores/'.$id_empresa, 'controller' => 'users'), array('class' => '', 'escape' => false), sprintf(__('Esta seguro de cambiar el estado a los digitadores ?', true), null)); ?></div>
<div>&nbsp;</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('No.', 'id'); ?></th>
            <th><?php echo $this->Paginator->sort('Usuario', 'username'); ?></th>
            <th><?php echo $this->Paginator->sort('Empresa', 'empresa_id'); ?></th>
<!--            <th><?php // echo $this->Paginator->sort('No. Identificacion', 'no_identificacion_persona'); ?></th>-->
            <th><?php echo $this->Paginator->sort('Nombres', 'nombres_persona'); ?></th>
            <th><?php echo $this->Paginator->sort('Celular', 'celular_persona'); ?></th>
            <th><?php echo $this->Paginator->sort('E-mail', 'email_persona'); ?></th>
            <th><?php echo $this->Paginator->sort('Rol', 'rol_id'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr> <?php
$i = 0;
foreach ($users as $user):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $i; ?></td>
            <td><?php echo $user['User']['username']; ?></td>
            <td title="Sucursal: <?php echo $user['Sucursale']['nombre_sucursal']; ?>"><?php echo $user['Empresa']['nombre_empresa']; ?></td>
<!--            <td><?php // echo $user['TipoDocumento']['sigla_tipo_documento'] . "-" . $user['User']['no_identificacion_persona']; ?></td>-->
            <td><?php echo $user['User']['nombres_persona']; ?><?php //echo $user['User']['apellidos_persona']; ?></td>
            <td><?php echo $user['User']['celular_persona']; ?></td>
            <td><?php echo $user['User']['email_persona']; ?></td>
            <td>
                    <?php
                    if ($user['User']['id'] == 1) {
                        echo "Super ";
                    } echo $user['Role']['rol_nombre'];
                    ?>
            </td>
            <td class="text-center">
                    <?php
                    if ($user['User']['estado']) {
                        echo $html->image('verde.png', array('alt' => 'Activo', 'title' => 'Activo'));
                        // echo " Activo";
                    } else {
                        echo $html->image('rojo.png', array('alt' => 'Inactivo', 'title' => 'Inactivo'));
                        // echo " Inactivo";
                    }
                    ?>
            </td>
            <td  <?php
                if ($user['User']['id'] == 1) {
                    echo "title='Recuerde que este es el SuperUsuario. Por seguridad se deshabilita las acciones. Si desea realizar alguna acción, conctacte con soporte técnico. '";
                }
                    ?> >
                        <?php if ($user['User']['id'] > 1) { ?>
                <div class="reestablecer_contrasena" title="Reestablecer"><?php echo $this->Html->link(__('', true), array('action' => 'reestablecer_contrasena', $user['User']['id']), array('class' => 'glyphicon glyphicon-refresh', 'escape' => false)); ?></div>
                <div class="view" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'view', $user['User']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $user['User']['id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($user['User']['estado']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $user['User']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el usuario %s?', true), $user['User']['username']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $user['User']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el usuario %s?', true), $user['User']['username']));
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
