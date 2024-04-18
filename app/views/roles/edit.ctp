<?php echo $this->Html->script(array('roles/roles_edit')); ?>
<?php echo $this->Form->create('Role'); ?>
<fieldset>
    <legend><?php __('Editar Rol'); ?></legend>
    <table class="table-striped" align="center">
        <tr>
            <td>Nombre Rol: *</td>
            <td><?php echo $this->Form->input('rol_nombre', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Rol Activo: </td>
            <td><?php echo $this->Form->input('rol_estado', array('type' => 'checkbox', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Pertenece a la empresa: </td>
            <td><?php echo $this->Form->input('rol_interno_externo', array('type' => 'checkbox', 'label' => 'Interno', 'onclick' => 'label_int_ext(this.id)')); ?></td>
        </tr>
    </table>
    <div>&nbsp;</div>
    <table class="table-striped table-bordered table-hover table-condensed"align="center">
        <tr>
            <th>Menu</th>
            <th>Acción</th>
            <th>Permitir
                <input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        </tr>
        <?php
        foreach ($options as $option) {

            $checked = false;
            foreach ($menu_users as $menu_user):
                if ($menu_user['MenuUser']['menus_actions_id'] == $option['MenuAction']['id']) {
                    $checked = $menu_user['MenuUser']['allow_deny'];
                }
            endforeach;
            ?>
            <tr>
                <td><?php echo $option['Menu']['menu_descripcion']; ?></td>
                <td><?php echo $option['MenuAction']['menus_actions_descripcion']; ?></td>
                <td><?php echo $this->Form->input($option['MenuAction']['id'], array('type' => 'checkbox', 'label' => false, 'checked' => $checked, 'value' => $option['MenuAction']['id'], 'class' => 'ck')); ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center" >
                <?php echo $this->Form->input('rol_id'); ?>
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_edit', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Editar Rol', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>