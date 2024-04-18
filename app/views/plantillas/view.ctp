<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2>AUDITORIA DE PLANTILLA</h2>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>No. Plantilla</th>
        <th>Fecha Cambio</th>
        <th>Usuario</th>
        <th>ID, Codigo, Precio CA, Precio Venta, IVA, Medida</th>
    </tr>
    <?php
    if(count($auditorias) > 0){
        foreach ($auditorias as $auditoria):
    ?>
    <tr>
        <td><span style='color:black; font-weight: bold;'><?php echo $auditoria['PlantillasAudit']['plantilla_id']; ?></span></td>
        <td><?php echo $auditoria['PlantillasAudit']['fecha_cambio_estado']; ?></td>
        <td><?php echo $auditoria['User']['username']; ?> - <?php echo $auditoria['User']['nombres_persona']; ?></td>
        <td><?php echo $auditoria['PlantillasAudit']['observaciones']; ?></td>
    </tr>
    <?php
        endforeach;
    }else{
    ?>
    <tr>
        <td colspan="5" class="text-center">No hay registros de auditoria para esta plantilla.</td>
    </tr>
    <?php
    }
    ?>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
</div>