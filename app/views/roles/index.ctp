<h2><span class="glyphicon glyphicon-lock"></span> Roles de Usuario</h2>
<div class="add">
    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Nuevo Rol', true), array('action' => 'add'), array('escape' => false)); ?>
</div>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('Rol', 'rol_nombre'); ?></th>
            <th><?php echo $this->Paginator->sort('Estado', 'rol_estado'); ?></th>
            <th><?php echo $this->Paginator->sort('Pertenece a CLEANEST L&C', 'rol_interno_externo'); ?></th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($roles as $role):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $role['Role']['rol_nombre']; ?></td>
                <td>
                    <?php
                    if ($role['Role']['rol_estado']) {
                        echo $html->image('verde.png');
                        //echo " Activo";
                    } else {
                        echo $html->image('rojo.png');
                        // echo " Inactivo";
                    }
                    ?></td>
                <td><?php echo $role['Role']['rol_interno_externo'] == true ? 'Si' : 'No'; ?></td>
                <td class="actions">
                    <?php
                    if ($role['Role']['rol_id'] > 1) {
                        ?>
                        <div class="view" title="Ver">
                            <?php echo $this->Html->link(__('', true), array('action' => 'view', $role['Role']['rol_id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?>
                        </div>
                        <div class="edit" title="Editar">
                            <?php echo $this->Html->link(__('', true), array('action' => 'edit', $role['Role']['rol_id']), array('class' => 'glyphicon glyphicon-edit', 'escape' => false)); ?>
                        </div>
                        <div class="delete" title="Cambiar Estado">
                            <?php
                            if ($role['Role']['rol_estado']) {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $role['Role']['rol_id']), array('class' => 'glyphicon glyphicon-ok', 'escape' => false), sprintf(__('Esta seguro de inactivar el rol %s? Se inactivaran todos los usuarios asociados a este rol.', true), $role['Role']['rol_nombre']));
                            } else {
                                echo $this->Html->link(__('', true), array('action' => 'delete', $role['Role']['rol_id']), array('class' => 'glyphicon glyphicon-remove', 'escape' => false), sprintf(__('Esta seguro de activar de nuevo el rol %s? Recuerde que debe activar los usuarios asociados a este rol.', true), $role['Role']['rol_nombre']));
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
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
