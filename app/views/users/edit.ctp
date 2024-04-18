<?php echo $this->Html->script(array('users/users_edit')); 
echo $this->Html->script(array('empresas/empresas_aprobadores'));
?>
<?php
echo $this->Form->create('User');
// print_r($this->data['User']['sucursal_id']);
?>
<fieldset>
    <legend><?php __('Editar Usuario'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Nombres: *</td>
            <td><?php echo $this->Form->input('nombres_persona', array('type' => 'text', 'label' => false)); ?></td>
<!--            <td>Apellidos: *</td>-->
            <td></td>                
            <td><?php //echo $this->Form->input('apellidos_persona', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Tipo de Documento: *</td>
            <td><?php echo $this->Form->input('tipo_documento_id', array('type' => 'select', 'options' => $tipoDocumentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>No. Documento: *</td>
            <td><?php echo $this->Form->input('no_identificacion_persona', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>G&eacute;nero: *</td>
            <td><?php echo $this->Form->input('tipo_sexo_id', array('type' => 'select', 'options' => $tipoSexos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>Fecha Nacimiento: *</td>
            <td><?php echo $this->Form->input('fecha_nacimiento', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Departamento: *</td>
            <td><?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <td>Municipio: *</td>
            <td><?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n: *</td>
            <td><?php echo $this->Form->input('direccion_residencia', array('type' => 'text', 'label' => false)); ?></td>
            <td>Tel&eacute;fono: *</td>
            <td><?php echo $this->Form->input('telefono_residencia', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Celular: *</td>
            <td><?php echo $this->Form->input('celular_persona', array('type' => 'text', 'label' => false)); ?></td>
            <td>E-mail: *</td>
            <td><?php echo $this->Form->input('email_persona', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td colspan="4"><h2>Datos Ingreso</h2></td>
        </tr>
        <tr>
            <td>Usuario: *</td>
            <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false)); ?></td>
            <td>Rol: *</td>
            <td><?php echo $this->Form->input('rol_id', array('type' => 'select', 'options' => $roles, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Empresa: *</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Sucursal: *</td>
            <td colspan="3"><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false, 'disabled'=>'disabled')); ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Parametro Precio Modificar Plantillas: *</b></td>
            <td colspan="2"><?php echo $this->Form->input('parametro_precio', array('type' => 'select', 'options' => $parametro_precio, 'label' => false)); //31052018 ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center"><b>¿Puede el usuario cambiar de sucursal cuando esta realizando el pedido? <?php echo $this->Form->input('cambiar_sucursal', array('type' => 'checkbox', 'label' => false,'div'=>false)); ?></b></td>
        </tr>
        <tr>
            <td colspan="4">

            </td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Usuario', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('id', array('type' => 'hidden'));
        echo $this->Form->input('fecha_registro', array('type' => 'hidden', 'value' => date('Y-m-d')));
        echo $this->Form->input('fecha_ultimo_ingreso', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
        echo $this->Form->input('estado', array('type' => 'hidden', 'value' => true));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $this->Form->create('EmpresasAprobadore', array('url' => array('controller' => 'empresas', 'action' => 'aprobadores/0'))); ?>
<table class="table-condensed" align="center" style="width: 90%; ">
    <tr>
        <th>Regional:</th>
        <td colspan="4"><?php echo $this->Form->input('regional_id', array('type' => 'select', 'options' => $regionales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <th>Tipo Pedido:</th>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td><?php echo $this->Form->button('Guardar ', array('type' => 'submit', 'class' => 'btn btn-success btn-xs')); ?></td>
    </tr>
        <?php echo $this->Form->input('empresa_id_2', array('type' => 'hidden', 'value' => null));?>
        <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->data['User']['id']));?>
        <?php echo $this->Form->input('url', array('type' => 'hidden', 'value' => 'edit/'.$this->data['User']['id']));?>
</table>
            <?php echo $this->Form->end(); ?>
<div class="index">
    <table id="permisos_regionales" class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Empresa-Regional-Sucursal</th>
<!--            <th>Permiso para</th>-->
            <th>Tipo de Pedido</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <th><input type="text" id="nombre_regional" onkeyup="search_nombre_regional()" size="60" maxlength="100" placeholder="Regional ..." title="Digite una Empresa, Regional o Sucursal"></th>
            <th><input type="text" id="nombre_usuario" onkeyup="search_nombre_usuario()" size="60" maxlength="100" placeholder="Usuario ..." title="Digite un usuario"></th>
            <th></th>
        </tr>
                        <?php 
                        foreach ($aprobadores as $aprobadore) :
                        ?>        
        <tr>
            <td><b>Emp:</b> <?php echo $aprobadore['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $aprobadore['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $aprobadore['Sucursale']['regional_sucursal'];?></td>
<!--            <td><?php //echo $aprobadore['User']['username']; ?> - <?php // echo $aprobadore['User']['nombres_persona']; ?></td>-->
            <td><?php echo $aprobadore['TipoPedido']['nombre_tipo_pedido']; ?></td>
            <td><?php echo $this->Html->link(__('', true), array('controller'=>'empresas','action' => 'quitar_aprobador', $aprobadore['EmpresasAprobadore']['id']), array('class' => 'glyphicon glyphicon-remove', 'escape' => false)); ?></td>

        </tr>
                            <?php
                        endforeach;
                    ?>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#UserSucursalId').val('<?php echo $this->data['User']['sucursal_id']; ?>');
    });
</script>