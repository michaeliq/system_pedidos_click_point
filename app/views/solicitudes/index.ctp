<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<style>
    .registrar_solicitud, .listar_solicitudes{
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
<h2><span class="glyphicon glyphicon-comment"></span> PQR - Solicitudes</h2>
<div style="  width: 100%; text-align: center;">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg registrar_solicitud" onclick="window.location.href = 'registrar_solicitud';">
                <span class="glyphicon glyphicon-tags"></span> Realizar Solicitud PQR
            </button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-default btn-lg listar_solicitudes" onclick="window.location.href = 'listar_solicitudes';">
                <span class="glyphicon glyphicon-filter"></span> Gestionar Solicitud PQR
            </button>
        </div>
    </div>
</div>