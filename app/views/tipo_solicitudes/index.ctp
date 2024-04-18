<h2><span class="glyphicon glyphicon-tasks"></span> <?php __('Tipos de Solicitudes PQR');?></h2>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Tipo Solicitud', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="tipoMovimientos index">	
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Tipo Solicitud','nombre_tipo_solicitud');?></th>
            <th><?php echo $this->Paginator->sort('Días Hábiles','dias_respuesta');?></th>
            <th><?php echo $this->Paginator->sort('Sigla','sigla_solicitud');?></th>
            <th><?php echo $this->Paginator->sort('Color','color_solicitud');?></th>
            <th><?php echo $this->Paginator->sort('Estado','estado');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($tipoSolicitudes as $tipoSolicitud):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><?php echo $tipoSolicitud['TipoSolicitude']['nombre_tipo_solicitud']; ?></td>
            <td><?php echo $tipoSolicitud['TipoSolicitude']['dias_respuesta']; ?></td>
            <td><?php echo $tipoSolicitud['TipoSolicitude']['sigla_solicitud']; ?></td>
            <td> <div style="border: #000 solid thin; padding: 1px; background-color: <?php echo $tipoSolicitud['TipoSolicitude']['color_solicitud']; ?>"><?php echo $tipoSolicitud['TipoSolicitude']['color_solicitud']; ?></div></td>
            <td><?php
                    if ($tipoSolicitud['TipoSolicitude']['estado']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?>
            </td>
            <td class="actions">
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $tipoSolicitud['TipoSolicitude']['id']),array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ($tipoSolicitud['TipoSolicitude']['estado']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $tipoSolicitud['TipoSolicitude']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el tipo de solicitud %s?', true), $tipoSolicitud['TipoSolicitude']['nombre_tipo_solicitud']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $tipoSolicitud['TipoSolicitude']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el tipo de solicitud %s?', true), $tipoSolicitud['TipoSolicitude']['nombre_tipo_solicitud']));
                        }
                        ?>
                </div>
            </td>
        </tr>
<?php endforeach; ?>
    </table>
</div>
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
