<?php

echo $this->Html->script(array('empresas/empresas_add')); 
echo $this->Html->script(array('empresas/empresas_aprobadores')); ?>
<style>

    .tab-content{
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;   
        border-bottom: 1px solid #ddd;   
    }

</style>
<div class="container">
    <h2><span class="glyphicon glyphicon-thumbs-up"></span> PERMISOS SOBRE PEDIDOS EMPRESAS - SUCURSALES - REGIONALES</h2>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#permisosEmpresa"><b>Permisos por Empresa</b></a></li>
<!--        <li><a data-toggle="tab" href="#permisosRegional"><b>Permisos por Regional</b></a></li>-->
    </ul>
    <div class="tab-content">
        <div id="permisosEmpresa" class="tab-pane fade in active">

            <?php echo $this->Form->create('EmpresasAprobadore', array('url' => array('controller' => 'empresas', 'action' => 'aprobadores/'.$empresa_id))); ?>
            <table class="table-condensed" align="center" style="width: 90%; ">
                <tr>
                    <td colspan="7">
                        <div class="alert alert-info">
                            Con esta opción podrá asignar permisos para todas las sucursales de la empresa <b><?php echo $empresa['0']['Empresa']['nombre_empresa']; ?></b>.
                        </div> 
                    </td>
                </tr>
                <tr>
                    <th>Empresa:</th>
                    <td><b><?php echo $empresa['0']['Empresa']['nombre_empresa']; ?></b>  <?php echo $this->Form->input('empresa_id_2', array('type' => 'hidden', 'value' => $empresa_id));?> </td>
                    <th>Usuario:</th>
                    <td><?php echo $this->Form->input('user_id', array('type' => 'select', 'options' => $users, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                    <?php /*<th>Tipo Pedido:</th>
                    <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                     * 
                     */?>
                    <td><?php echo $this->Form->button('Guardar ', array('type' => 'submit', 'class' => 'btn btn-success btn-xs')); ?></td>
                </tr>
                <?php //echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_aprobadores', 'class' => 'btn btn-warning btn-xs')); ?>

            <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));?>
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
        <div id="permisosRegional" class="tab-pane fade">
            <?php echo $this->Form->create('EmpresasAprobadore', array('url' => array('controller' => 'empresas', 'action' => 'aprobadores/'.$empresa_id))); ?>
            <table class="table-condensed" align="center" style="width: 90%; ">
                <tr>
                    <td colspan="5">
                        <div class="alert alert-warning">
                            Con esta opción podrá asignar permisos a <b>regionales especificas</b>. Una regional tiene varias sucursales.
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Regional:</th>
                    <td colspan="4"><?php echo $this->Form->input('regional_id', array('type' => 'select', 'options' => $regionales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <th>Usuario:</th>
                    <td><?php echo $this->Form->input('user_id', array('type' => 'select', 'options' => $users, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                    <th>Tipo Pedido:</th>
                    <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
                    <td><?php echo $this->Form->button('Guardar ', array('type' => 'submit', 'class' => 'btn btn-success btn-xs')); ?></td>
                </tr>
                <?php echo $this->Form->input('empresa_id_2', array('type' => 'hidden', 'value' => null));?>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));?>
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div>&nbsp;</div>
<div align="center"> <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_aprobadores', 'class' => 'btn btn-warning btn-xs')); ?></div>
<div>&nbsp;</div>
<div class="index">

    <table id="permisos_regionales" class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Empresa-Regional-Sucursal</th>
            <th>Permiso para</th>
<!--            <th>Tipo de Pedido</th>-->
            <th>Acciones</th>
        </tr>
        <tr>
            <th><input type="text" id="nombre_regional" onkeyup="search_nombre_regional()" size="60" maxlength="100" placeholder="Regional ..." title="Digite una Empresa, Regional o Sucursal"></th>
            <th><input type="text" id="nombre_usuario" onkeyup="search_nombre_usuario()" size="60" maxlength="100" placeholder="Usuario ..." title="Digite un usuario"></th>
            <th></th>
            <th></th>
        </tr>
        <?php 
        foreach ($aprobadores as $aprobadore) :
        ?>        
        <tr>
            <td><b>Emp:</b> <?php echo $aprobadore['Empresa']['nombre_empresa'];?><br><b>Suc:</b> <?php echo $aprobadore['Sucursale']['nombre_sucursal'];?><br><b>Reg:</b> <?php echo $aprobadore['Sucursale']['regional_sucursal'];?></td>
            <td><?php echo $aprobadore['User']['username']; ?> - <?php echo $aprobadore['User']['nombres_persona']; ?></td>
<!--            <td><?php // echo $aprobadore['TipoPedido']['nombre_tipo_pedido']; ?></td>-->
            <td><?php echo $this->Html->link(__('', true), array('action' => 'quitar_aprobador', $aprobadore['EmpresasAprobadore']['id']), array('class' => 'glyphicon glyphicon-remove', 'escape' => false)); ?></td>

        </tr>
            <?php
        endforeach;
    ?>
    </table>

</div>