<?php

echo $this->Html->script(array('cronometro')); 
echo $this->Html->css(array('cronometro')); 
    $centesimas = '0';
    $segundos = '0';
    $minutos = '0';
    $horas = '0';
    // print_r($listadoLlamadaDetalle);
if(!empty($listadoLlamadaDetalle['ListadoLlamadasDetalle']['duracion_actual'])){
    $aux = explode(':', $listadoLlamadaDetalle['ListadoLlamadasDetalle']['duracion_actual']);
    // print_r($aux);
    $centesimas = '35';
    $segundos = '59';
    $minutos = $aux['1'];
    $horas = $aux['0'];
}
?>
<script>
    $(document).ready(function () {
        inicio_en(<?php echo $horas; ?>, <?php echo $minutos; ?>, <?php echo $segundos; ?>, <?php echo $centesimas; ?>);

        $('#button_cotizar').prop('disabled', true);
        $('#button_agendar').prop('disabled', true);
        $('#button_terminar').prop('disabled', true);
        <?php if(!empty($cotizacion)){
            if($cotizacion['0']['Cotizacion']['cotizacion_estado_pedido'] == 3){ ?>
        $('#button_terminar').prop('disabled', false);
        <?php }} ?>

        $('#div_fecha_probable').hide();
        $('input[name=detalle_encuesta]').on('change', function () {
            // No esta interesado - No contesta
            if ($('input[name=detalle_encuesta]:checked').val() == '1' || $('input[name=detalle_encuesta]:checked').val() == '2' || $('input[name=detalle_encuesta]:checked').val() >= '6') {
                $('#div_fecha_probable').hide();
                $('#fecha_probable').val('');
                $('#button_cotizar').prop('disabled', true);
                $('#button_agendar').prop('disabled', true);
                $('#button_terminar').prop('disabled', false);
            }
            // Llamar luego - Realizar visita
            if ($('input[name=detalle_encuesta]:checked').val() == '3' || $('input[name=detalle_encuesta]:checked').val() == '4') {
                $('#div_fecha_probable').show();
                $('#fecha_probable').focus();
                $('#button_cotizar').prop('disabled', true);
                $('#button_agendar').prop('disabled', false);
                $('#button_terminar').prop('disabled', true);
            } /*else {
             $('#button_agendar').prop('disabled', true);
             $('#button_terminar').prop('disabled', false);
             $('#div_fecha_probable').hide();
             }*/

            // Cotizar inmediatamente
            if ($('input[name=detalle_encuesta]:checked').val() == '5') {
                $('#div_fecha_probable').hide();
                $('#fecha_probable').val('');
                $('#button_cotizar').prop('disabled', false);
                $('#button_agendar').prop('disabled', true);
                $('#button_terminar').prop('disabled', true);
            }
        });

        $('#fecha_probable').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            yearRange: '2017',
            minDate: 0,
            maxDate: 30
        });

    });
    function proceso_cotizacion(id) {
        $.ajax({
            url: '../actualizar_cliente',
            type: 'POST',
            data: {
                ListadoLlamadaId: id,
                // detalle_encuesta: $('input[name=detalle_encuesta]:checked').val(),
                departamento_id: $('#departamento_id').val(),
                municipio_id: $('#municipio_id').val(),
                cliente_email: $('#cliente_email').val(),
                cliente_direccion: $('#cliente_direccion').val(),
                cliente_telefono: $('#cliente_telefono').val(),
                cliente_id: $('#cliente_id').val(),
                // fecha_probable: $('#fecha_probable').val(),
                // hora_probable: $('#hora_probable').val(),
                nombre_contacto: $('#bd_nombre_contacto').val(),
                observaciones: $('#bd_observaciones').val(),
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                window.location = "../cotizacion/" + id;
            } else {
                location.href = document.URL;
            }
        }
    }

    function proceso_agendar(id) {
        $.ajax({
            url: '../agendar_llamada',
            type: 'POST',
            data: {
                ListadoLlamadaId: id,
                detalle_encuesta: $('input[name=detalle_encuesta]:checked').val(),
                departamento_id: $('#departamento_id').val(),
                municipio_id: $('#municipio_id').val(),
                cliente_email: $('#cliente_email').val(),
                cliente_direccion: $('#cliente_direccion').val(),
                cliente_telefono: $('#cliente_telefono').val(),
                cliente_id: $('#cliente_id').val(),
                fecha_probable: $('#fecha_probable').val(),
                hora_probable: $('#hora_probable').val(),
                nombre_contacto: $('#bd_nombre_contacto').val(),
                observaciones: $('#bd_observaciones').val()
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                window.location = "../index";
            } else {
                location.href = document.URL;
            }
        }
    }

    function proceso_terminar(id) {
        $.ajax({
            url: '../terminar_llamada',
            type: 'POST',
            data: {
                ListadoLlamadaId: id,
                detalle_encuesta: $('input[name=detalle_encuesta]:checked').val(),
                departamento_id: $('#departamento_id').val(),
                municipio_id: $('#municipio_id').val(),
                cliente_email: $('#cliente_email').val(),
                cliente_direccion: $('#cliente_direccion').val(),
                cliente_telefono: $('#cliente_telefono').val(),
                cliente_id: $('#cliente_id').val(),
                nombre_contacto: $('#bd_nombre_contacto').val(),
                observaciones: $('#bd_observaciones').val(),
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                window.location = "../index";
            } else {
                location.href = document.URL;
            }
        }
    }
    /*  Cargar municipios a partir del departamento seleccionado. */
    $(function () {
        $('#departamento_id').change(function () {
            $.ajax({
                url: '../../users/municipios/',
                type: 'POST',
                data: {
                    UserDepartamentoId: $('#departamento_id').val()
                },
                async: false,
                dataType: "json",
                success: onSuccess
            });
            function onSuccess(data) {
                if (data == null) {
                    alert('No hay Municipios para este Departamento');
                } else {
                    document.getElementById('municipio_id').innerHTML = '';
                    $('#municipio_id').attr({
                        disabled: false
                    });
                    for (var i in data) {
                        if (i == 0) {
                            var op_default = document.createElement('option');
                            op_default.text = 'Seleccione una Opcion';
                            op_default.value = '0';
                            document.getElementById('municipio_id').add(op_default, null);
                        }

                        var opcion = document.createElement('option');
                        opcion.text = data[i].Municipio.nombre_municipio;
                        opcion.value = data[i].Municipio.id;
                        document.getElementById('municipio_id').add(opcion, null);

                    }
                    if ($('#departamento_id').val() == -1)
                        $('#municipio_id').attr({
                            disabled: true
                        });
                }
            }
        });
    });

</script>

<fieldset>
    <legend><?php __('Nueva Llamada en curso'); ?></legend>
    <table class="table table-bordered" align="center">
        <tr>
            <td><b>Empresa:</b> <?php echo $listadoLlamada['BdCliente']['bd_razon_social']; ?> - Nit: <?php echo $listadoLlamada['BdCliente']['bd_identificacion']; ?><br>
                <b>Ubicación:</b> <?php echo $listadoLlamada['BdCliente']['bd_nombre_municipio']; ?><br>
                <b>Actividad:</b> <span style="font-size: 10px; font-style: italic;"><?php echo $listadoLlamada['BdCliente']['bd_descripcion_1']; ?></span><br>
                <b>Pagína Web:</b> <a href="<?php echo $listadoLlamada['BdCliente']['bd_pagina_web']; ?>" target="_blank"><?php echo $listadoLlamada['BdCliente']['bd_pagina_web']; ?></a><br>
                <b>Rep. Legal:</b> <?php echo $listadoLlamada['BdCliente']['bd_representante_legal']; ?><br>
                <b>Teléfonos:</b> <span style="font-size: x-large; color: #ffcc33; text-align: center;"><b><?php echo $listadoLlamada['BdCliente']['bd_telefonos']; ?></b></span>
                <?php echo $this->Form->input('departamento_id', array('type' => 'select', 'options' => $departamentos, 'empty' => 'Seleccione una Opción', 'label' => '<b>Departamento:</b>&nbsp;','selected'=> $listadoLlamada['BdCliente']['bd_departamento_id'])); ?>
                <?php echo $this->Form->input('municipio_id', array('type' => 'select', 'options' => $municipios, 'empty' => 'Seleccione una Opción', 'label' => '<b>Municipio:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','selected'=> $listadoLlamada['BdCliente']['bd_municipio_id'])); ?>
                <?php echo $this->Form->input('cliente_email', array('type' => 'text', 'size'=>'60', 'label' => '<b>E-mail:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'value' => $listadoLlamada['BdCliente']['bd_email'],'required'=>true)); ?>
                <?php echo $this->Form->input('cliente_direccion', array('type' => 'text', 'size'=>'60', 'label' => '<b>Dirección:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'value' => $listadoLlamada['BdCliente']['bd_direccion'])); ?>
                <?php echo $this->Form->input('cliente_telefono', array('type' => 'text', 'size'=>'60', 'label' => '<b>Teléfono:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'value' => $listadoLlamada['BdCliente']['bd_telefonos'])); ?>
                <?php echo $this->Form->input('bd_nombre_contacto', array('type' => 'text', 'size'=>'60', 'label' => '<b>Contacto:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'value' => $listadoLlamada['BdCliente']['bd_nombre_contacto'])); ?>
                <?php echo $this->Form->input('bd_observaciones', array('type' => 'text', 'size'=>'60', 'label' => '<b>Observaciones:</b>', 'value' => $listadoLlamada['BdCliente']['bd_observaciones'])); ?>
            </td>
            <td>
                <b>Detalle Llamada:</b><br>
                <?php echo $this->Form->radio('detalle_encuesta',$detalle_encuesta,array('name'=>'detalle_encuesta','legend'=>false ,'separator'=>'<div style=""></div>')); ?>
                <div id="div_fecha_probable"><?php echo $this->Form->input('fecha_probable', array('type' => 'text', 'value' =>  '','label'=>'Fecha Programada: ')); ?> <?php echo $this->Form->input('hora_probable', array('type' => 'select',  'options' => $hora_probable,'label'=>'Hora Probable: ')); ?></div>            </td>
                <?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'value' =>  $listadoLlamada['BdCliente']['id'])); ?>
        </tr>
    </table>
    <div id="contenedor">
        <div class="reloj" id="Horas">00</div>
        <div class="reloj" id="Minutos">:00</div>
        <div class="reloj" id="Segundos">:00</div>
        <div class="reloj" id="Centesimas">:00</div>
        <!-- <input type="button" class="boton" id="inicio" value="Start &#9658;" onclick="inicio();">
        <input type="button" class="boton" id="parar" value="Stop &#8718;" onclick="parar();" disabled>
        <input type="button" class="boton" id="continuar" value="Resume &#8634;" onclick="inicio();" disabled>
        <input type="button" class="boton" id="reinicio" value="Reset &#8635;" onclick="reinicio();" disabled> -->
    </div>
    <div class="text-center">

        <?php 
        $disabled = "";
        if(count($cotizacion)>0){
            if($cotizacion['0']['Cotizacion']['cotizacion_estado_pedido'] == 1){
                $btn = "btn-success";
                $icono = "glyphicon-shopping-cart";
                $texto = "Iniciar Proceso Cotización";
                $onclick = "window.location.href = '../cotizacion/'".$listado_llamada_id;
            }
            if($cotizacion['0']['Cotizacion']['cotizacion_estado_pedido'] == 2){
             $btn = "btn-info";
             $icono = "glyphicon-remove";
             $texto = "Cotización Cancelada";
             $onclick = "";
             $disabled = 'disabled="disabled"';
            }
            if($cotizacion['0']['Cotizacion']['cotizacion_estado_pedido'] == 3){
             $btn = "btn-info";
             $icono = "glyphicon-ok";   
             $texto = "Cotización Realizada";
             $onclick = "";
             $disabled = 'disabled="disabled"';
            }
        }else{
             $btn = "btn-success";
             $icono = "glyphicon-shopping-cart";
             $texto = "Iniciar Proceso Cotización";   
             $onclick = "proceso_cotizacion('".$listado_llamada_id."');"; // window.location.href = '../cotizacion/".$listado_llamada_id."'"
        }
        ?>
        <button type="button" id="button_cotizar" class="btn <?php echo $btn; ?> btn-lg" onclick="<?php echo $onclick;?>" <?php echo $disabled; ?>> <!-- -->
            <span class="glyphicon <?php echo $icono; ?> "></span> <?php echo $texto; ?>
        </button>
        <button type="button" id="button_agendar" class="btn btn-default btn-lg" onclick="proceso_agendar('<?php echo $listado_llamada_id; ?>')"> <!-- window.location.href = 'orden_pedido';-->
            <span class="glyphicon glyphicon-calendar"></span> Agendar Llamada - Visita
        </button>
        <button type="button" id="button_terminar" class="btn btn-danger btn-lg" onclick="proceso_terminar('<?php echo $listado_llamada_id; ?>')"> <!-- window.location.href = 'orden_pedido';-->
            <span class="glyphicon glyphicon-phone-alt"></span> Terminar Llamada
        </button>
    </div>
</fieldset>