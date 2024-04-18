<?php echo $this->Html->script(array('roles/roles_add')); ?>
<?php echo $this->Form->create('Role'); ?>
<fieldset>
    <legend><?php __('Nuevo Rol'); ?></legend>
    <table class="table-striped" align="center">
        <tr>
            <td>Nombre Rol: *</td>
            <td><?php echo $this->Form->input('rol_nombre', array('type' => 'text', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Rol Activo: </td>
            <td><?php echo $this->Form->input('rol_estado', array('type' => 'checkbox', 'label' => false, 'checked' => true)); ?></td>
        </tr>
        <tr>
            <td>Pertenece a la empresa: </td>
            <td><?php echo $this->Form->input('rol_interno_externo', array('type' => 'checkbox', 'label' => 'Interno', 'checked' => true, 'onclick' => 'label_int_ext(this.id)')); ?></td>
        </tr>
    </table>
    <div>&nbsp;</div>
    <table class="table-striped table-bordered table-hover table-condensed"align="center">
        <tr>
            <th>Menu</th>
            <th>Acción</th>
            <th>Permitir <input name="Todos" type="checkbox" value="1" class="check_todos"/></th>
        </tr>
        <?php
        foreach ($options as $option) {
            ?>
            <tr>
                <td><?php echo $option['Menu']['menu_descripcion']; ?></td>
                <td><?php echo $option['MenuAction']['menus_actions_descripcion']; ?></td>
                <td><?php echo $this->Form->input($option['MenuAction']['id'], array('type' => 'checkbox', 'label' => false, 'value' => $option['MenuAction']['id'], 'class' => 'ck')); ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Rol', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>