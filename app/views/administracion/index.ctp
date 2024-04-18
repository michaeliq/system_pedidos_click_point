<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    .empresas,
    .productos,
    .usuarios,
    .roles,
    .estados_digitadores,
    .plantillas,
    .cronogramas,
    .proveedores,
    .bodegas,
    .tipos_movimiento,
    .cronogramas_inventarios,
    .regionale,
    .vendedore {
        display: none;
    }
</style>
<h2><span class="glyphicon glyphicon-wrench"></span> ADMINISTRACI&Oacute;N</h2>
<div></div>
<table class="table">
    <tr>
        <td>
            <div class="">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-asterisk"></i> Empresas y Sucursales', true), array('action' => '/index', 'controller' => 'empresas'), array('escape' => false)); ?>
            </div>
        </td>
        <td>
            <div >
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-user"></i> Usuarios del Sistema', true), array('action' => '/index', 'controller' => 'users'), array('escape' => false)); ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-leaf"></i> Productos', true), array('action' => '/index', 'controller' => 'productos'), array('escape' => false)); ?></div>
        </td>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-lock"></i> Roles de Usuario', true), array('action' => '/index', 'controller' => 'roles'), array('escape' => false)); ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Plantillas de productos', true), array('action' => '/index', 'controller' => 'plantillas'), array('escape' => false)); ?></div>
        </td>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-calendar"></i> Cronogramas de pedidos', true), array('action' => '/index', 'controller' => 'cronogramas'), array('escape' => false)); ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-phone"></i> Vendedores', true), array('action' => '/index', 'controller' => 'vendedores'), array('escape' => false)); ?></div>
        </td>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-tasks"></i> Tipo Solicitudes PQR', true), array('action' => '/index', 'controller' => 'tipoSolicitudes'), array('escape' => false)); ?></div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
<div>&nbsp;</div>
<h2><span class="glyphicon glyphicon-stats"></span> INVENTARIOS</h2>
<div>&nbsp;</div>
<table class="table">
    <tr>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-tree-deciduous"></i> Proveedores', true), array('action' => '/index', 'controller' => 'proveedores'), array('escape' => false)); ?></div>
        </td>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-cloud-download"></i> Bodegas', true), array('action' => '/index', 'controller' => 'bodegas'), array('escape' => false)); ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-transfer"></i> Tipos de Movimiento', true), array('action' => '/index', 'controller' => 'tipoMovimientos'), array('escape' => false)); ?></div>
        </td>
        <td>
            <div class=""><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-calendar"></i> Cronogramas de Inventarios', true), array('action' => '/index', 'controller' => 'cronogramasInventarios'), array('escape' => false)); ?></div>
        </td>
    </tr>
</table>