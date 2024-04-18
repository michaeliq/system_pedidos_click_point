<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-earphone"></span> CALL CENTER</h2>
<div class="text-center">
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = '../listadoLlamadas/gestion_llamadas';"> <!-- -->
        <span class="glyphicon glyphicon-list-alt"></span> Gestión de Llamadas
    </button>
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = '../listadoLlamadas/index';"> <!-- -->
        <span class="glyphicon glyphicon-earphone"></span> Listado de Llamadas
    </button>
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = '../cotizacions/index';"> <!-- -->
        <span class="glyphicon glyphicon-usd"></span> Listado de Cotizaciones
    </button><br><br>
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = '../listadoLlamadas/index';"> <!-- -->
        <span class="glyphicon glyphicon-calendar"></span> Agendas de Llamadas
    </button>
    <?php if ($this->Session->read('Auth.User.rol_id') == '1' && $this->Session->read('Auth.User.id') == '1') { ?>
    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href = '../bd_clientes/index';"> <!-- -->
        <span class="glyphicon glyphicon-hdd"></span> Base de Datos Clientes
    </button>
    <?php } ?>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Cotizar cuando cliente envíe información (<?php echo count($cotizacions); ?>)</a></li>
    <li><a data-toggle="tab" href="#menu1">Agendas de llamadas - visitas (<?php echo count($agendas); ?>)</a></li>
</ul>
<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <h3>Cotizar cuando cliente envíe información</h3>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>No.</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Fecha de Llamada</th>
                <th class="actions"><?php __('Acciones');?></th>
            </tr>
	<?php
	$i = 0;        
	foreach ($cotizacions as $cotizacion):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
            <tr<?php echo $class;?> style="">
                <td><?php echo $i; ?></td>
                <td><?php echo $cotizacion['BdCliente']['bd_razon_social']; ?></td>
                <td><?php echo $cotizacion['BdCliente']['bd_telefonos']; ?><br><?php echo $cotizacion['BdCliente']['bd_email']; ?></td> 
                <td><?php echo $cotizacion['ListadoLlamadasDetalle']['fecha_inicio']; ?></td> 
                <td class="actions"> 
                    <?php  echo $this->Html->link(__('<i class="glyphicon glyphicon-ok"></i> Completar', true), array('action' => 'completar_click_cotizacion', base64_encode($cotizacion['ListadoLlamadasDetalle']['id'])), array('escape' => false,'target' => '')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div id="menu1" class="tab-pane fade">
        <h3>Agendas de llamadas - visitas</h3>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>No.</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Fecha Ejecución</th>
                <th class="actions"><?php __('Acciones');?></th>
            </tr>
	<?php
	$i = 0;        
	foreach ($agendas as $agenda):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
            <tr<?php echo $class;?> style="">
                <td><?php echo $i; ?></td>
                <td><?php echo $agenda['BdCliente']['bd_razon_social']; ?></td>
                <td><?php echo $agenda['BdCliente']['bd_telefonos']; ?><br><?php echo $agenda['BdCliente']['bd_email']; ?></td> 
                <td><?php echo $agenda['ListadoLlamada']['fecha_registro']; ?></td>
                <td>Llamada</td>
<!--                <td class="actions"> 
                    <?php // echo $this->Html->link(__('<i class="glyphicon glyphicon-ok"></i> Completar', true), array('action' => 'completar_click_agendar', base64_encode($agenda['ListadoLlamada']['id'])), array('escape' => false,'target' => '')); ?>
                </td>-->
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>