<div class="listadoLlamadas index">
    <h2><?php __('Listado de Llamadas para hoy ('.date('Y-m-d').')');?></h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('Cliente','bd_cliente_id');?></th>
            <!--<th>Teléfonos</th>-->
            <th>Ubicación</th>
            <th>Actividad</th>
            <th><?php echo $this->Paginator->sort('Estado','estado_llamada');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
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
	?>
        <tr<?php echo $class;?> style="">
            <td><?php echo $listadoLlamada['ListadoLlamada']['id']; ?></td>
            <td><?php echo $listadoLlamada['BdCliente']['bd_razon_social']; ?></td>
            <!-- <td><?php /*echo $listadoLlamada['BdCliente']['bd_telefonos']; ?><br>
                <a href="<?php echo $listadoLlamada['BdCliente']['bd_pagina_web']; ?>" target="_blank"><?php echo $listadoLlamada['BdCliente']['bd_pagina_web'];  */ ?></a></td>-->
            <td><?php echo $listadoLlamada['BdCliente']['bd_nombre_municipio']; ?></td>
            <td><?php echo $listadoLlamada['BdCliente']['bd_descripcion_1']; ?></td>
            <td title="<?php echo $listadoLlamada['ListadoLlamada']['fecha_registro']; ?>"><?php
                    if ($listadoLlamada['ListadoLlamada']['estado_llamada']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        echo $agendada;
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td class="actions">
			<?php 
                         if (!$listadoLlamada['ListadoLlamada']['estado_llamada']) {
                             echo $this->Html->link(__('<i class="glyphicon glyphicon-earphone"></i> Iniciar', true), array('action' => 'iniciar_llamada', base64_encode($listadoLlamada['ListadoLlamada']['id'])), array('escape' => false,'target' => '')); 
                             // echo $this->Html->link(__('Iniciar', true), array('action' => '', base64_encode($listadoLlamada['ListadoLlamada']['id']))); 
                         }else{
                             echo "Terminada";
                         } ?>
			<?php /*echo $this->Html->link(__('View', true), array('action' => 'view', $listadoLlamada['ListadoLlamada']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $listadoLlamada['ListadoLlamada']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $listadoLlamada['ListadoLlamada']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $listadoLlamada['ListadoLlamada']['id'])); */ ?>
            </td>
        </tr>
<?php endforeach; ?>
    </table>
    <p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?>	</p>

    <div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
        | 	<?php echo $this->Paginator->numbers(); ?>
        |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>
