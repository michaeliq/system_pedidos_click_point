<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo $this->Html->script(array('roles/roles_edit')); ?>
<fieldset>
    <legend><?php __('Ver Rol'); ?></legend>
    <table class="table-striped" align="center">
        <tr>
            <td><b>Rol:</b> </td>
            <td><?php echo $role['Role']['rol_nombre']; ?></td>
        </tr>
        <tr>
            <td><b>Estado Rol:</b> </td>
            <td>
                <?php
                if ($role['Role']['rol_estado']) {
                    echo "Activo";
                } else {
                    echo "Inactivo";
                }
                ?>
            </td>
        </tr>
    </table>
    <div>&nbsp;</div>
    <table class="table-striped table-bordered table-hover table-condensed"align="center">
        <tr>
            <th>Menu</th>
            <th>Acción</th>
            <th>Permitir</th>
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
                <td>
                    <?php
                    if ($checked) {
                        echo '<i class="glyphicon glyphicon-ok"></i>';
                    } else {
                        echo '<i class="glyphicon glyphicon-remove"></i>';
                    }
                    ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_view', 'class' => 'btn btn-warning')); ?>
            </td>
        </tr>
    </table>
</fieldset>
