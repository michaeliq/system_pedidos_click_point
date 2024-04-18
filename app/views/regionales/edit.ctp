<?php

echo $this->Form->create('Regionale'); ?>
<script>

    $(document).ready(function () {
        $(".check_todos_apc").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_apc:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_apc:checkbox:checked").removeAttr("checked");
            }
        });
        $(".check_todos_drrhh").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_drrhh:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_drrhh:checkbox:checked").removeAttr("checked");
            }
        });
        $(".check_todos_io").click(function (event) {
            if ($(this).is(":checked")) {
                $(".ck_io:checkbox:not(:checked)").attr("checked", "checked");
            } else {
                $(".ck_io:checkbox:checked").removeAttr("checked");
            }
        });

    });
</script>
<fieldset>
    <legend><?php __('Editar Regional'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td><b>Regional: *</b></td>
            <td><?php echo $this->Form->input('nombre_regional', array('type' => 'text', 'label' => false, 'maxlength' => '200','size'=>'100')); ?></td>
        </tr>
        <?php /*<tr>
            <td colspan="2">
                <h2><span class="glyphicon glyphicon-thumbs-up"></span> PERMISOS SOBRE PEDIDOS SUCURSALES - REGIONALES</h2>
                <table class="table table-striped table-bordered table-condensed" align="center">
                    <tr>
                        <th>Usuario</th>
                        <th>Nombres Usuario</th>
                        <th>Rol</th>
                        <th class="text-center">Aseo<br>Papelería<br>Cafetería<br> <input name="Todos" type="checkbox" value="1" class="check_todos_apc"/></th>
                        <th class="text-center">Dotaciones<br>RR HH<br> <input name="Todos" type="checkbox" value="1" class="check_todos_drrhh"/></th>
                        <th class="text-center">Insumos<br>Operaciones<br> <input name="Todos" type="checkbox" value="1" class="check_todos_io"/></th>
                    </tr>
                    <?php
                    foreach ($users as $value) {
                    $checkedApc = false;
                    $checkedDrrhh = false;
                    $checkedIo = false;
                    // print_r($empresasAprobadores);
                    foreach ($empresasAprobadores as $aprobado) {
                        if($aprobado['EmpresasAprobadore']['user_id'] ==$value['User']['id']){
                            if($aprobado['EmpresasAprobadore']['tipo_pedido_id'] == 1)
                                $checkedApc = true;
                            if($aprobado['EmpresasAprobadore']['tipo_pedido_id'] == 2)
                                $checkedIo = true;
                            if($aprobado['EmpresasAprobadore']['tipo_pedido_id'] == 3)
                                $checkedDrrhh = true;
                        }
                    }

                    ?>
                    <tr>
                        <td><?php echo $value['User']['id'].'-'.$value['User']['username']; ?></td>    
                        <td><?php echo $value['User']['nombres_persona']; ?></td>
                        <td><?php echo $value['Role']['rol_nombre']; ?></td>
                        <td class="text-center"><?php echo $this->Form->input('apc_'.$value['User']['id'], array('type' => 'checkbox', 'label' => false, 'checked' => $checkedApc, 'value' => $value['User']['id'], 'class' => 'ck_apc')); ?></td>
                        <td class="text-center"><?php echo $this->Form->input('drrhh_'.$value['User']['id'], array('type' => 'checkbox', 'label' => false, 'checked' => $checkedDrrhh, 'value' => $value['User']['id'], 'class' => 'ck_drrhh')); ?></td>
                        <td class="text-center"><?php echo $this->Form->input('io_'.$value['User']['id'], array('type' => 'checkbox', 'label' => false, 'checked' => $checkedIo, 'value' => $value['User']['id'], 'class' => 'ck_io')); ?></td>
                    </tr>
                    <?php } ?>
                </table>

            </td>
        </tr> */?>
        <tr>
            <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Guardar Regional', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <?php
        echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $this->Session->read('Regional.empresa_id')));
        echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>