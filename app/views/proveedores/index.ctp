<h2><?php __('Proveedores');?></h2>

<?php echo $this->Form->create('Proveedore', array('url' => array('controller' => 'proveedores', 'action' => '/index'))); ?>
<table class="table table-condensed ">
    <tr>
        <td><b>Provedor:</b></td>
        <td><?php echo $this->Form->input('nombre_proveedor', array('type' => 'text', 'size' => '20', 'maxlength' => '120', 'label' => false)); ?></td>
        <td><b>Nit Provedor:</b></td>
        <td><?php echo $this->Form->input('nit_proveedor', array('type' => 'text', 'size' => '20', 'maxlength' => '20', 'label' => false)); ?></td>
        <td><b>Persona Contacto:</b></td>
        <td><?php echo $this->Form->input('persona_contacto', array('type' => 'text', 'size' => '60', 'maxlength' => '120', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<div class="add"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Proveedor', true), array('action' => 'add'), array('escape' => false)); ?></div>
<div class="proveedores index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Proveedor','nombre_proveedor');?></th>
            <th><?php echo $this->Paginator->sort('Régimen','tipo_regimene_id');?></th>
            <th><?php echo $this->Paginator->sort('Contacto','persona_contacto');?></th>
            <th><?php echo $this->Paginator->sort('Estado','estado');?></th>
            <th class="actions"><?php __('Acciones');?></th>
        </tr>
	<?php
	$i = 0;
	foreach ($proveedores as $proveedore):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
        <tr<?php echo $class;?>>
            <td><?php echo $proveedore['Proveedore']['nombre_proveedor']; ?><br>Nit: <?php echo $proveedore['Proveedore']['nit_proveedor']; ?></td>
            <td><?php echo $proveedore['TipoRegimene']['tipo_regimen_nombre']; ?><br> Forma de Pago: <?php echo $proveedore['TipoFormasPago']['nombre_forma_pago']; ?></td>
            <td><?php echo $proveedore['Proveedore']['persona_contacto']; ?><br>Dir. <?php echo $proveedore['Proveedore']['direccion_proveedor']; ?><br>Tel. <?php echo $proveedore['Proveedore']['telefono_proveedor']; ?><br>E-mail. <?php echo $proveedore['Proveedore']['email_proveedor']; ?></td>
            <td><?php
                    if ($proveedore['Proveedore']['estado']) {
                        echo $html->image('verde.png');
                     //   echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                      //  echo " Inactivo";
                    }
                    ?></td>
            <td class="actions">
                <div class="view" title="Ver"><?php // echo $this->Html->link(__('', true), array('action' => 'view', $proveedore['Proveedore']['id']),array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>
                <div class="edit" title="Editar"><?php echo $this->Html->link(__('', true), array('action' => 'edit', $proveedore['Proveedore']['id']),array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?></div>
                <div class="delete" title="Cambiar Estado">
                        <?php
                        if ($proveedore['Proveedore']['estado']) {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $proveedore['Proveedore']['id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el proveedor %s?', true), $proveedore['Proveedore']['nombre_proveedor']));
                        } else {
                            echo $this->Html->link(__('', true), array('action' => 'delete', $proveedore['Proveedore']['id']), array('class' => 'glyphicon glyphicon-ban-circle', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el proveedor %s?', true), $proveedore['Proveedore']['nombre_proveedor']));
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
