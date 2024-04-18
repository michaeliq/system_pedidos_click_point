<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<style>
    .orden_compra, .listar_ordenes,.aprobar_ordenes{
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
<h2><span class="glyphicon glyphicon-tags"></span> ORDENES DE COMPRA</h2>
<div style="  width: 100%; text-align: center;">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg orden_compra" onclick="window.location.href = 'orden_compra';">
                <span class="glyphicon glyphicon-tags"></span> Realizar Orden de Compra
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg listar_ordenes" onclick="window.location.href = 'listar_ordenes';">
                <span class="glyphicon glyphicon-filter"></span> Consultar Orden de Compra
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg aprobar_ordenes" onclick="window.location.href = 'aprobar_ordenes';">
                <span class="glyphicon glyphicon-check"></span> Aprobar Orden de Compra
            </button>
        </div>
    </div>
</div>