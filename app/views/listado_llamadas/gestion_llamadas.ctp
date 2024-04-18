<div class="listadoLlamadas index">
    <h2><?php __('Gestión de Llamadas');?></h2>
    <?php echo $this->Form->create('ListadoLlamada'); ?>
    <fieldset>
        <legend><?php __('Filtro'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Cliente: </td>
                <td><?php echo $this->Form->input('bd_razon_social', array('type' => 'text', 'size' => '40', 'maxlength' => '120', 'label' => false)); ?></td>
                <td>E-mail Cliente: </td>
                <td><?php echo $this->Form->input('bd_email', array('type' => 'text', 'size' => '20', 'maxlength' => '100', 'label' => false)); ?></td>
                <td>Teléfono Cliente: </td>
                <td><?php echo $this->Form->input('bd_telefonos', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false)); ?></td>
                <td>Fecha Gestión: </td>
                <td><?php echo $this->Form->input('fecha_inicio', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false)); ?></td>
            </tr>
        </table>        
    </fieldset>
    <div class="text-center">
    <?php echo $this->Form->button('Nueva Llamada a Cliente', array('type' => 'button', 'class' => 'btn btn-primary', 'onclick' => " window.open('add');")); ?>
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    </div>
<?php echo $this->Form->end(); ?>
    <div>&nbsp;</div>
    <div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nueva Llamada a Cliente', true), array('action' => 'add'), array('escape' => false)); ?></div>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Id</th>
            <th>Fecha Llamada</th>
            <th>Duración<br>(hh:mm:ss)</th>
            <th>Cliente</th>
            <th>Datos de Contacto</th>
            <th>Observaciones</th>

<!--            <th>Actividad</th>-->
<!--            <th>Estado</th>-->
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
        // print_r($listadoLlamadas);
	$i = 0;        
	foreach ($listadoLlamadas as $listadoLlamada):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
                $agendada = null;
                if($listadoLlamada['ListadoLlamada']['agendada']){
                    $agendada = ' <i class="glyphicon glyphicon-time" style="font-size: 17px;"></i>';
                }
                $llamada_iniciada = '';
                if($listadoLlamada['ListadoLlamadasDetalle']['fecha_inicio'] == $listadoLlamada['ListadoLlamadasDetalle']['fecha_fin']){
                   $llamada_iniciada = 'background-color: #ffffcc';
                }
	?>
        <tr<?php echo $class;?> style="<?php echo $llamada_iniciada; ?>">
            <td><?php echo $listadoLlamada['ListadoLlamada']['id']; ?></td>
            <td><b>Inicio</b> <?php echo $listadoLlamada['ListadoLlamadasDetalle']['fecha_inicio']; ?><br>
                <b>Fin</b> <?php echo $listadoLlamada['ListadoLlamadasDetalle']['fecha_fin']; ?></td>
            <td><?php echo $listadoLlamada['ListadoLlamadasDetalle']['duracion']; ?></td>
            <td><?php echo $listadoLlamada['BdCliente']['bd_razon_social']; ?><br><b>Contacto:</b> <?php echo $listadoLlamada['BdCliente']['bd_nombre_contacto']; ?></td>
            <td><?php echo $listadoLlamada['BdCliente']['bd_direccion']; ?><br>
                <?php echo $listadoLlamada['BdCliente']['bd_telefonos']; ?><br>
                <?php echo $listadoLlamada['BdCliente']['bd_email']; ?><br>
                <a href="<?php echo $listadoLlamada['BdCliente']['bd_pagina_web']; ?>" target="_blank"><?php echo $listadoLlamada['BdCliente']['bd_pagina_web']; ?></a></td>
            <td><?php echo $listadoLlamada['BdCliente']['bd_observaciones']; ?><br>

            <td class="actions">
			<?php 
                        $object = json_decode($listadoLlamada['ListadoLlamadasDetalle']['detalle_encuesta'],true); 
                            $detalle_encuesta = array('1' => 'No contestó',
                            '2' => 'No está interesado',
                            '3' => 'Llamar luego (Agendar)',
                            '4' => 'Realizar visita',
                            '5' => 'Cotizar inmediatamente',
                            '6' => 'Cotizar cuando cliente envíe información',
                            '7' => 'No volver a llamar',
                            '8' => 'Empresa inexistente',
                            '9' => 'Datos Erroneos');                       
                            if(!empty($object['detalle_encuesta'])){
                                echo $detalle_encuesta[$object['detalle_encuesta']]; 
                            }
                            if(!empty($listadoLlamada['Cotizacion']['id'])){    
                                if(!empty($listadoLlamada['Cotizacion']['cotizacion_estado'])){    
                                    // echo $this->Html->link(__('', true), array('action' => 'cotizacion_detalle','controller'=>'listadoLlamadas', $listadoLlamada['Cotizacion']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false));
                                    echo "Cotización Terminada";
                                    echo "<br>";
                                    echo $this->Html->link(__('<i class="glyphicon glyphicon-zoom-in"></i> Ver', true), array('controller'=>'listadoLlamadas','action' => 'cotizacion_pdf/', $listadoLlamada['Cotizacion']['id']), array('escape' => false,'target' => '_blank'));
                                }else{
                                    echo "Cotización Pendiente";
                                    echo "<br>";
                                    echo $this->Html->link(__('', true), array('action' => 'cotizacion_detalle','controller'=>'listadoLlamadas', $listadoLlamada['Cotizacion']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false));
                                }
                                    
                                
                             }
                             if($listadoLlamada['ListadoLlamadasDetalle']['fecha_inicio'] == $listadoLlamada['ListadoLlamadasDetalle']['fecha_fin']){
                                 echo $this->Html->link(__('<i class="glyphicon glyphicon-earphone"></i> Retomar Llamada', true), array('controller'=>'listadoLlamadas','action' => 'iniciar_llamada/', base64_encode($listadoLlamada['ListadoLlamada']['id'])), array('escape' => false,'target' => '_blank'));
                             }
                         ?>
            </td>
        </tr>
<?php endforeach; ?>
    </table>

</div>

