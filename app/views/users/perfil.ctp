<?php

echo $this->Html->script(array('users/perfil')); ?>
<h2>Perfil <?php echo $user['User']['nombres_persona']; ?>&nbsp;<?php // echo $user['User']['apellidos_persona']; ?> (<?php echo $user['Role']['rol_nombre']; ?>)</h2>
<table class="table" >
    <tr>
        <td>Empresa:</td>
        <td colspan="3"><b><?php echo $user['Empresa']['nombre_empresa']; ?></b></td>
    </tr>
    <tr>
        <td>Genero:</td>
        <td><?php echo $user['TipoSexo']['nombre_tipo_sexo']; ?></td>
        <td>Fecha de Nacimiento:</td>
        <td><?php echo $user['User']['fecha_nacimiento']; ?></td>
    </tr>
    <tr>
        <td>Tipo de Identificaci&oacute;n:</td>
        <td><?php echo $user['TipoDocumento']['nombre_tipo_documento']; ?></td>
        <td>No. Identificación:</td>
        <td><?php echo $user['User']['no_identificacion_persona']; ?></td>
    </tr>
    <tr>
        <td>Departamento:</td>
        <td><?php echo $user['Departamento']['nombre_departamento']; ?></td>
        <td>Municipio:</td>
        <td><?php echo $user['Municipio']['nombre_municipio']; ?></td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Residencia:</td>
        <td><?php echo $user['User']['direccion_residencia']; ?></td>
        <td>Tel&eacute;fono Residencia:</td>
        <td><?php echo $user['User']['telefono_residencia']; ?></td>
    </tr>
    <tr>
        <td>Celular:</td>
        <td><?php echo $user['User']['celular_persona']; ?></td>
        <td>E-mail:</td>
        <td><?php echo $user['User']['email_persona']; ?></td>
    </tr>
    <tr>
        <td>Fecha de Registro:</td>
        <td><?php echo $user['User']['fecha_registro']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <!--
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>    
    <tr>
        <td colspan="4" class="center">
            <?php // echo $this->Html->link('[ Editar ]', array('controller' => 'users', 'action' => 'edit', 'full_base' => true)); ?>
            <?php //echo $this->Html->link('[  ]', array('controller' => 'users', 'action' => 'changepass', 'full_base' => true)); ?>
        </td>
    </tr> -->
</table>
<h2><i class="glyphicon glyphicon-warning-sign" style="color: red;"></i> Pedidos Pr&oacute;ximos a Cancelarse</h2>
<ul>
    <?php echo $pedidos_vencer; ?>
</ul><br>
<h2><i class="glyphicon glyphicon-calendar"></i> Cronograma de Pedidos</h2>
<ul>
    <?php 
    if(count($cronogramas)> 0){
        foreach ($cronogramas as $cronograma):
            echo '<li>El cronograma <b>'.$cronograma[0]['nombre_cronograma'].'</b> del tipo de pedido <b>'.$cronograma[0]['nombre_tipo_pedido'].'</b> se encuentra activo entre las fechas (<b>'.$cronograma[0]['fecha_inicio'].' - '.$cronograma[0]['fecha_fin'].'</b>).</li>';
        endforeach;
    }else{
            echo '<li>No hay cronogramas activos en el sistema.</li>';
    }
?>
</ul><br>
<?php if($this->Session->read('Auth.User.rol_id')=='1'){ ?>
<h2><i class="glyphicon glyphicon-bookmark"></i> Sucursales Inactivas</h2>
<ul>
     <?php 
    if(count($sucursales)> 0){
        foreach ($sucursales as $sucursal):
            echo '<li>La sucursal <b>'.$sucursal['Sucursale']['nombre_sucursal'].'</b> de la empresa <b>'.$sucursal['Empresa']['nombre_empresa'].'</b> se encuentra inactiva. Creada el ('.$sucursal['Sucursale']['fecha_creacion'].'</b>).';
            if (!$sucursal['Sucursale']['estado_sucursal']) {
                   echo $this->Html->link(__('', true), array('controller'=>'sucursales','action' => 'delete', $sucursal['Sucursale']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo la sucursal %s?', true), $sucursal['Sucursale']['nombre_sucursal']));
            }
            echo "</li>";
        endforeach;
    }else{
            echo '<li>No hay sucursales inactivas que se hayan creado en los últimos 30 días.</li>';
    }
?>
</ul><br>
<?php } ?>
<div class="text-center"><?php echo $this->Form->button('Cambiar Contraseña', array('type' => 'button', 'class' => 'btn btn-success','onclick'=>'cambiar_contrasena();')); ?></div>



