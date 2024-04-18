<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="text-center">
    <ul class="nav nav-pills menu_principal">
        <?php
        if ($this->Session->read('Auth.User.id')) {
            echo " <li id='inicio'> " . $this->Html->link(__('Inicio', true), array('action' => '/perfil', 'controller' => 'users')) . "</li>";
            echo " <li class='administracion'> " . $this->Html->link(__('Administración', true), array('action' => '/index', 'controller' => 'administracion')) . "</li>";
            echo " <li class='pedidos'> " . $this->Html->link(__('Ordenes de Pedido', true), array('action' => '/index', 'controller' => 'pedidos')) . "</li>";
            echo " <li class='ordencompras'> " . $this->Html->link(__('Ordenes de Compra', true), array('action' => '/index', 'controller' => 'ordenCompras')) . "</li>";
            echo " <li class='movimientosentradas'> " . $this->Html->link(__('Inventarios', true), array('action' => '/index', 'controller' => 'movimientosEntradas')) . "</li>";
            echo " <li class='informes'> " . $this->Html->link(__('Informes', true), array('action' => '/index', 'controller' => 'informes')) . "</li>";
            echo " <li class='solicitudes'> " . $this->Html->link(__('PQR', true), array('action' => '/index', 'controller' => 'solicitudes')) . "</li>";
            ?>
            <?php
            echo " <li> " . $this->Html->link(__('Salir', true), array('controller' => 'users', 'action' => 'logout'), array('escape' => false)) . "</li>";
        }
        ?>
    </ul>
</div>
