<?php ?> 
<div class="cotizacions index">
    <h2><span class="glyphicon glyphicon-usd"></span> Listado de Cotizaciones</h2>
        <?php echo $this->Form->create('Cotizacion'); ?>
    <fieldset>
        <legend><?php __('Filtro'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td>Cliente: </td>
                <td><?php echo $this->Form->input('bd_razon_social', array('type' => 'text', 'size' => '40', 'maxlength' => '120', 'label' => false)); ?></td>
                <td>E-mail Cliente: </td>
                <td><?php echo $this->Form->input('bd_email', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false)); ?></td>
                <td>Teléfono Cliente: </td>
                <td><?php echo $this->Form->input('bd_telefonos', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false)); ?></td>
                <td>Fecha Gestión: </td>
                <td><?php echo $this->Form->input('fecha_cotizacion', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false)); ?></td>
            </tr>
        </table>        
    </fieldset>
    <div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
    </div>
<?php echo $this->Form->end(); ?>
    <div>&nbsp;</div>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('Cliente','bd_cliente_id');?></th>
            <th><?php echo $this->Paginator->sort('Estado','cotizacion_estado_pedido');?></th>
            <th><?php echo $this->Paginator->sort('Usuario','user_id');?></th>
            <th><?php echo $this->Paginator->sort('observaciones');?></th>
            <th><?php echo $this->Paginator->sort('fecha_cotizacion');?></th>
            <th><?php echo $this->Paginator->sort('Contacto','cotizacion_direccion');?></th>
            <th><?php echo $this->Paginator->sort('Estado','cotizacion_estado');?></th>
            <th><?php echo $this->Paginator->sort('Enviada?','cotizacion_envio_email');?></th>
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
        <tr<?php echo $class;?>>
            <td><span style='color:red;'><b>#000<?php echo $cotizacion['Cotizacion']['id']; ?></b></span></td>
            <td><?php echo $cotizacion['BdCliente']['bd_razon_social']; ?></td>
            <td><?php echo $cotizacion['EstadoPedido']['nombre_estado']; ?></td>
            <td><?php echo $cotizacion['User']['nombres_persona']; ?></td>
            <td><?php echo $cotizacion['Cotizacion']['observaciones']; ?></td>
            <td><?php echo $cotizacion['Cotizacion']['fecha_cotizacion']; ?></td>
            <td><?php echo $cotizacion['Cotizacion']['cotizacion_email']; ?><br><?php echo $cotizacion['Cotizacion']['cotizacion_direccion']; ?> <br>Tel:<?php echo $cotizacion['Cotizacion']['cotizacion_telefono']; ?></td>
            <td><?php  if ($cotizacion['Cotizacion']['cotizacion_estado']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        if($cotizacion['EstadoPedido']['id'] != '2'){
                            echo " ";
                            echo $this->Html->link(__('', true), array('action' => 'cotizacion_detalle','controller'=>'listadoLlamadas', $cotizacion['Cotizacion']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false));
                        }
                      //  echo " Inactivo";
                    } ?></td>
            <td style="text-align: center;"><?php  if ($cotizacion['Cotizacion']['cotizacion_envio_email']) {
                        echo '<span class="glyphicon glyphicon-ok" style="color:green;" aria-hidden="true"></span>';
                     //   echo " Activo";
                    } else {
                        echo '<span class="glyphicon glyphicon-remove" style="color:red;" aria-hidden="true"></span>';
                        
                        
                      //  echo " Inactivo";
                    } ?>
            </td>
            <td class="actions">
			<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-zoom-in"></i> Ver', true), array('controller'=>'listadoLlamadas','action' => 'cotizacion_pdf/', $cotizacion['Cotizacion']['id']), array('escape' => false,'target' => '_blank')); ?><br>
			<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-envelope"></i> Enviar', true), array('controller'=>'','action' => '/', $cotizacion['Cotizacion']['id']), array('escape' => false,'target' => '_blank')); ?>
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
