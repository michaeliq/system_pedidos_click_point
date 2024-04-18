<?php

$detalle_encuesta = array('1' => 'No contestó',
                '2' => 'No está interesado',
                '3' => 'Llamar luego (Agendar)',
                '4' => 'Realizar visita',
                '5' => 'Cotizar inmediatamente',
                '6' => 'Cotizar cuando cliente envíe información',
                '7' => 'No volver a llamar',
                '8' => 'Empresa inexistente',
                '9' => 'Datos Erroneos');
?>
<script>

    $(function () {
        $('#ListadoLlamadaFechaInicio').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            minDate: -365,
            maxDate: 0
        });
    });

    function search_acciones() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("acciones_sh");
        
        filter = input.value.toUpperCase();
        table = document.getElementById("detalle_callcenter");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[5];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME GENERAL DE CALL CENTER</h2>
<?php echo $this->Form->create('ListadoLlamada', array('url' => array('controller' => 'informes', 'action' => 'info_general_call    '))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <th>Cliente: </th>
            <td><?php echo $this->Form->input('bd_razon_social', array('type' => 'text', 'size' => '40', 'maxlength' => '120', 'label' => false)); ?></td>
            <th>Usuario: </th>
            <td><?php echo $this->Form->input('user_id', array('type' => 'select', 'options' => $users, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
            <th>Fecha Gestión: </th>
            <td><?php echo $this->Form->input('fecha_inicio', array('type' => 'text', 'size' => '20', 'maxlength' => '12', 'label' => false,'value'=>$fecha)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table-striped table-bordered table-hover table-condensed" align='center'>
    <tr>
        <th colspan="6" style="text-align: center;">Llamadas para el <?php echo $fecha; ?></th>
    </tr>
    <tr>
        <th>Usuario</th>
        <th>Cantidad<br>Llamadas</th>
        <th>Promedio de duración<br>(hh:mm:ss)</th>
        <th>Tiempo total</th>
        <th>Inició</th>
        <th>Finalizó</th>
    </tr>
    <?php
    $i = 0;
    foreach ($detalle_general as $consolidado):
    $class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
                ?>
    <tr <?php echo $class;?>>
        <td><?php echo $consolidado['User']['nombres_persona']; ?></td>
        <td style="text-align: center;"><?php echo $consolidado[0]['count']; ?></td>
        <td style="text-align: center;"><?php echo $consolidado[0]['avg']; ?></td>
        <td style="text-align: center;"><?php echo $consolidado[0]['sum']; ?></td>
        <td style="text-align: center;"><?php echo $consolidado[0]['min']; ?></td>
        <td style="text-align: center;"><?php echo $consolidado[0]['max']; ?></td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
<div>&nbsp;</div>
<div>
    <span class="glyphicon glyphicon-arrow-up" style="color: #e32;"></span> Tiempo Alto <b>(Mayor a 15 minutos)</b><br>
    <span class="glyphicon glyphicon-arrow-right" style="color: #0a0;"></span> Tiempo Normal <b>(3 - 14 minutos)</b><br>
    <span class="glyphicon glyphicon-arrow-down" style="color: #FECA40;"></span> Tiempo Bajo <b>(0 - 2 minutos)</b>
</div>
<table id="detalle_callcenter" class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Id</th>
        <th>Usuario</th>
        <th>Fecha Llamada</th>
        <th>Duración<br>(hh:mm:ss)</th>
        <th>Indicador</th>
        <th>Cliente</th>
        <th>Acción<br><select id="acciones_sh" onchange="search_acciones()">
                <option>Seleccione</option>
                <option value="contestó">No contestó</option>
                <option value="No está interesado">No está interesado</option>
                <option value="Llamar luego (Agendar)">Llamar luego (Agendar)</option>
                <option value="Realizar visita">Realizar visita</option>
                <option value="Cotizar inmediatamente">Cotizar inmediatamente</option>
                <option value="Cotizar cuando cliente envíe información">Cotizar cuando cliente envíe información</option>
                <option value="No volver a llamar">No volver a llamar</option>
                <option value="Empresa inexistente">Empresa inexistente</option>
                <option value="Datos Erroneos">Datos Erroneos</option>
            </select></th>
    </tr>
    
	<?php
        // print_r($listadoLlamadas);
	$i = 0;        
	foreach ($listadoLlamadas as $listadoLlamada):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
                $time    = explode(':', $listadoLlamada['ListadoLlamadasDetalle']['duracion']);
                $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
                
                $arrow = null;
                $title = null;
                if($minutes > 14){
                    $arrow = '<span class="glyphicon glyphicon-arrow-up" style="color: #e32;"></span>';
                    $title = 'Tiempo alto';
                }else{
                    if($minutes <= 2){
                        $arrow = '<span class="glyphicon glyphicon-arrow-down" style="color: #FECA40;"></span>';
                        $title = 'Tiempo bajo';
                    }else{
                        $arrow = '<span class="glyphicon glyphicon-arrow-right" style="color: #0a0;"></span>';   
                        $title = 'Tiempo normal';
                    }
                }
	?>
    <tr <?php echo $class;?>>
        <td><?php echo $listadoLlamada['ListadoLlamada']['id']; ?></td>
        <td><?php echo $listadoLlamada['User']['nombres_persona']; ?></td>
        <td><b>Inicio</b> <?php echo $listadoLlamada['ListadoLlamadasDetalle']['fecha_inicio']; ?><br>
            <b>Fin</b> <?php echo $listadoLlamada['ListadoLlamadasDetalle']['fecha_fin']; ?></td>
        <td><?php echo $listadoLlamada['ListadoLlamadasDetalle']['duracion'];?></td>
        <td style="text-align: center;" title="<?php echo $title; ?>"><?php echo $arrow; ?> </td>
        <td><?php echo $listadoLlamada['BdCliente']['bd_razon_social']; ?><br><b>Contacto:</b> <?php echo $listadoLlamada['BdCliente']['bd_nombre_contacto']; ?></td>
        <td><?php 
                $object = json_decode($listadoLlamada['ListadoLlamadasDetalle']['detalle_encuesta'],true); 
                                       
                
                if(!empty($object['detalle_encuesta'])){
                    echo $detalle_encuesta[$object['detalle_encuesta']]; 
                }
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>