<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    .list_ordenes, .orden_pedido,.search_orden, .aprobar_orden, .despacho, .cambiar_estado, .entregado{
        display: none;
    }
    .panel-body {
        text-align: center;
    }
    .btn-toolbar {
        display: inline-block;
    }
    .btn-toolbar .btn {
        margin-bottom: 5px;
    }
</style>
<h2><span class="glyphicon glyphicon-inbox"></span> ORDENES DE PEDIDO</h2>
<div style="  width: 100%;
     text-align: center;">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg search_orden" onclick="window.location.href = 'search_orden';">
                <span class="glyphicon glyphicon-filter"></span> Consultar Orden
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg list_ordenes" onclick="window.location.href = 'list_ordenes';">
                <span class="glyphicon glyphicon-list-alt"></span> Ordenes Pendientes
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg orden_pedido" onclick="window.location.href = 'orden_pedido';"> <!---->
                <span class="glyphicon glyphicon-shopping-cart"></span> Realizar Orden
            </button>
        </div>

        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg aprobar_orden" onclick="window.location.href = 'aprobar_orden';">
                <span class="glyphicon glyphicon-ok"></span> Aprobar Orden
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg despacho" onclick="window.location.href = 'despacho';">
                <span class="glyphicon glyphicon-plane"></span> Despachos
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg entregado" onclick="window.location.href = 'entregado';">
                <span class="glyphicon glyphicon-thumbs-up"></span> Entregas
            </button>  
        </div>
    </div>
    <br>
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <?php if($this->Session->read('Auth.User.empresa_id')!='104'){ // BBVA ?>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg despacho" onclick="window.location.href = '../masivos/despachos';"> <!-- -->
                <span class="glyphicon glyphicon-plane"></span> Despachos Masivos
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg orden_pedido" onclick="window.location.href = '../masivos';"> <!-- -->
                <span class="glyphicon glyphicon-upload"></span> Pedidos Masivos
            </button>
        </div>
        <?php } ?>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg cambiar_estado" onclick="window.location.href = 'cambiar_estado';">
                <span class="glyphicon glyphicon-random"></span> Cambiar Estado a Pedido
            </button>
        </div>
    </div>
</div>