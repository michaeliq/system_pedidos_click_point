<?php ?>
<script>
    var arr_tipos = [];

    function fn_aprobar_orden() {
        window.location = 'pedidos/aprobar_orden';
    }

    function consultar_cronogramas() {
        $('#MasivoTipoPedidoId').attr({
            disabled: true
        });

        $.ajax({
            url: 'users/cronogramas/',
            type: 'POST',
            data: {
                PedidoEmpresaId: $('#MasivoEmpresaId option:selected').val(),
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });

        function onSuccess(data) {
            if (data == null) {
                alert('No hay respuesta');
            } else {
                document.getElementById('MasivoTipoPedidoId').innerHTML = '';
                $('#MasivoTipoPedidoId').attr({
                    disabled: false
                });

                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opción';
                        op_default.value = '0';
                        document.getElementById('MasivoTipoPedidoId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].TipoPedido.nombre_tipo_pedido;
                    opcion.value = data[i].TipoPedido.id;
                    document.getElementById('MasivoTipoPedidoId').add(opcion, null);

                    arr_tipos.push(data[i]);

                }
            }
        }
    }


    $(function() {
        $('#MasivoFechaEntrega1').datepicker({
            changeMonth: false,
            changeYear: false,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            yearRange: '2019',
            minDate: 0,
            maxDate: +365
        });

        $('#MasivoFechaEntrega2').datepicker({
            changeMonth: false,
            changeYear: false,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            yearRange: '2019',
            minDate: 0,
            maxDate: +365
        });
    });
</script>
<?php
$meses_pedidos = array('0' => 'INSTALACION');
$meses = array('1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
$mes_actual = date('n');
$mes_siguiente = date('n', strtotime('+1 month'));
$mes_siguiente_2 = date('n', strtotime('+2 month'));
$meses_pedidos[$mes_actual] = $meses[$mes_actual];
$meses_pedidos[$mes_siguiente] = $meses[$mes_siguiente];
$meses_pedidos[$mes_siguiente_2] = $meses[$mes_siguiente_2];

$clasificacion = array('Tarifa integral' => 'Tarifa integral', 'Facturacion sobre pedido' => 'Facturacion sobre pedido');
?>
<h2>Cargador Masivo de Pedidos</h2>
<?php echo $this->Form->create('Masivo', array('type' => 'file')); ?>
<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 50%;">
    <tr>
        <td colspan="2" class="text-center"><b>Seleccione un archivo para cargar la información de los pedidos:</b><br>Tenga en cuenta lo siguiente:</td>
    </tr>
    <tr>
        <td colspan="2" align='left'>
            <b>1.</b> El archivo a cargar debe ser de tipo CSV.<br>
            <b>2.</b> El separador de los datos debe ser punto y coma (;), si se tiene un separador diferente, los datos no se cargaran.<br>
            <b>3.</b> El archivo se validará antes de cargar las ordenes de pedido, si el archivo no esta correcto, no se procesará.<br>
            <b>4.</b> Luego de cargar el archivo, se procederá a mostrar los errores encontrados en el mismo, para que sean validados por el usuario y se realice la carga del archivo nuevamente.<br>
            <b>5.</b> Se debe seleccionar una empresa y tipo de pedido al que este relacionado la orden de pedido.

        </td>
    </tr>
    <tr>
        <td colspan="2" align='center'><b>Empresa:</b> <?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresa, 'empty' => 'Seleccione una Opción', 'label' => false, 'required' => true, 'onchange' => 'consultar_cronogramas()')); ?></td>
    </tr>
    <tr>
        <td colspan="2" align='center'><b>Tipo de Pedido:</b> <?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false, 'required' => true)); ?></td>
    </tr>
    <tr>
        <td align='center'><b>Fecha de Entrega: </b> <br>
            <?php echo $this->Form->input('fecha_entrega_1', array('type' => 'text', 'label' => false, 'maxlength' => '10', 'placeholder' => 'Desde ... ', 'required' => true)); ?> <br>
            <?php echo $this->Form->input('fecha_entrega_2', array('type' => 'text', 'label' => false, 'maxlength' => '10', 'placeholder' => 'Hasta ... ', 'required' => true)); ?>
        </td>
        <td align='center'><b>Mes Pedido: </b><br>
            <?php echo $this->Form->input('mes_pedido', array('type' => 'select', 'options' => $meses_pedidos, 'label' => false, 'empty' => 'Seleccione una Opción', 'required' => true)); //2021-07-28 
            ?><br>
            <b>Clasificación:</b><br>
            <?php echo $this->Form->input('clasificacion_pedido', array('type' => 'select', 'options' => $clasificacion, 'label' => false, 'empty' => 'Seleccione una Opción', 'required' => true)); //2021-07-28 
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align='center'><?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file', 'label' => false, 'div' => false)); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <?php if ($pendiente_aprobacion > 0) {
                echo $this->Form->button('Aprobar Ordenes!', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'fn_aprobar_orden()'));
            } else {
                echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-success'));
            } ?>
            <?php echo $this->Form->input('fecha_hora_carga', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s'))); ?>
        </td>
    </tr>
</table>
<?php if (count($errores) > 0) {
    $i = 0;
    echo "Se generaron " . count($errores) . " errores";
    foreach ($errores as $errore) :
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        echo "<div " . $class . ">" . $i . " - " . $errore['0']['error_generado'] . "</div>";

    endforeach;
}
?>
<?php if (count($pedidos_creados) > 0) { ?>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <td colspan="4" align='center'>
                <h2>DETALLE DE LOS PEDIDOS CREADOS</h2>
            </td>
        </tr>

        <tr>
            <td><b>No.</b></td>
            <td><b>No. Pedido</b></td>
            <td><b>Sucursal</b></td>
            <td><b>Cantidad de Productos</b></td>
        </tr>
        <?php
        $j = 0;
        $total =  0;
        foreach ($pedidos_creados as $pedidos_creado) :
            if ($j++ % 2 == 0) {
                $class = ' class="altrow"';
            }
            $total = $total + $pedidos_creado['0']['sum'];
        ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $j; ?></td>
                <td>000<?php echo $pedidos_creado['0']['pedido_id']; ?></td>
                <td><?php echo $pedidos_creado['0']['nombre_sucursal']; ?></td>
                <td><?php echo $pedidos_creado['0']['sum']; ?></td>
                </tr>
            <?php
        endforeach;
            ?>
            <tr>
                <td colspan="3">TOTAL PRODUCTOS</td>
                <td><?php echo $total; ?></td>
            </tr>
        <?php } ?>
    </table>