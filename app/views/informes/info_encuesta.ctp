<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * https://live.amcharts.com/new/edit/
 */
?>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/pie.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
    $(function () {
        $('#EncuestaEncuestaFechaInicio').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            yearRange: "-2:+0",
            maxDate: '0'
        });
    });
    $(function () {
        $('#EncuestaEncuestaFechaFin').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            yearRange: "-2:+0",
            maxDate: '0'
        });
    });


</script>
<h2><span class="glyphicon glyphicon-book"></span> INFORME ENCUESTAS</h2>
<?php echo $this->Form->create('Encuesta', array('url' => array('controller' => 'informes', 'action' => 'info_encuesta'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa: *</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
	<!--<tr>
            <td>Sucursal:</td>
            <td colspan="3"><?php // echo $this->Form->input('sucursal_id', array('type' => 'select','options' => $sucursales, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
	</tr> -->
        <tr>
            <td>Fecha Inicio: *<br></td>
            <td><?php echo $this->Form->input('encuesta_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Fecha Fin: *<br></td>
            <td><?php echo $this->Form->input('encuesta_fecha_fin', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
    </table>
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<?php if(count($encuestaDiligenciada)>0){ ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<h3>CANTIDAD ENCUESTAS DILIGENCIADAS: <?php echo  $encuestaDiligenciada; ?></h3>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Pregunta</th>
        <th>Excelente</th>
        <th>Bueno</th>
        <th>Regular</th>
        <th>Malo</th>
        <th>No Aplica</th>
    </tr>
    <?php
    $i = 0;
    foreach ($resultados as $value) :
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>

    <tr<?php echo $class; ?>>
        <td><?php echo $value['0']['pregunta_encuesta']; ?></td>
        <td style="vertical-align:middle; text-align: center; "><?php 
        if($value['0']['excelente']>0){
            echo number_format((($value['0']['excelente'] * 100)/$encuestaDiligenciada),2).' %'; 
        }else{
            echo "0 %";
        }
        ?></td>
        <td style="vertical-align:middle; text-align: center; "><?php 
        if($value['0']['bueno']>0){
            echo number_format((($value['0']['bueno'] * 100)/$encuestaDiligenciada),2).' %'; 
        }else{
            echo "0 %";
        }
        ?></td>
        <td style="vertical-align:middle; text-align: center; "><?php 
        if($value['0']['regular']>0){
            echo number_format((($value['0']['regular'] * 100)/$encuestaDiligenciada),2).' %'; 
        }else{
            echo "0 %";
        }
        ?></td>
        <td style="vertical-align:middle; text-align: center; "><?php 
        if($value['0']['malo']>0){
            echo number_format((($value['0']['malo'] * 100)/$encuestaDiligenciada),2).' %'; 
        }else{
            echo "0 %";
        }
        ?></td>
        <td style="vertical-align:middle; text-align: center; "><?php 
        if($value['0']['no_aplica']>0){
            echo number_format((($value['0']['no_aplica'] * 100)/$encuestaDiligenciada),2).' %'; 
        }else{
            echo "0 %";
        }
        ?></td>
    </tr>

    <?php endforeach; ?>
</table>

<?php foreach ($resultados as $value) : ?>
<script type="text/javascript">
    /*AmCharts.makeChart("chartdiv_<?php //echo $value['0']['id']; ?>",
            {
                "type": "serial",
                "categoryField": "category",
                "rotate": true,
                "startDuration": 1,
                "theme": "light",
                "categoryAxis": {
                    "gridPosition": "start"
                },
                "trendLines": [],
                "graphs": [
                    {
                        "balloonText": "[[title]] de [[category]]:[[value]]",
                        "fillAlphas": 1,
                        "id": "AmGraph-1",
                        "title": "Respuesta",
                        "type": "column",
                        "valueField": "column-1"
                    }
                ],
                "guides": [],
                "valueAxes": [
                    {
                        "id": "ValueAxis-1",
                        "title": ""
                    }
                ],
                "allLabels": [],
                "balloon": {},
                "legend": {
                    "enabled": true,
                    "useGraphSettings": true
                },
                "titles": [
                    {
                        "id": "Title-1",
                        "size": 15,
                        "text": "<?php //echo $value['0']['pregunta_encuesta']; ?>"
                    }
                ],
                "dataProvider": [
                    {
                        "category": "Excelente",
                        "column-1": <?php //echo ($value['0']['excelente'] * 100)/$encuestaDiligenciada; ?>
                    },
                    {
                        "category": "Bueno",
                        "column-1": <?php //echo ($value['0']['bueno']* 100)/$encuestaDiligenciada; ?>
                    },
                    {
                        "category": "Regular",
                        "column-1": <?php //echo ($value['0']['regular']* 100)/$encuestaDiligenciada;?>
                    },
                    {
                        "category": "Malo",
                        "column-1": <?php //echo ($value['0']['malo']* 100)/$encuestaDiligenciada; ?>
                    },
                    {
                        "category": "No Aplica",
                        "column-1": <?php //echo ($value['0']['no_aplica']* 100)/$encuestaDiligenciada; ?>
                    }
                ]
            }
    ); */

    AmCharts.makeChart("chartdiv_pie_<?php echo $value['0']['id']; ?>",
            {
                "type": "pie",
                "angle": 12,
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "depth3D": 15,
                "innerRadius": "40%",
                "titleField": "category",
                "valueField": "column-1",
                "theme": "light",
                "allLabels": [],
                "balloon": {},
                "legend": {
                    "enabled": true,
                    "align": "center",
                    "markerType": "circle"
                },
                "titles": [],
                "dataProvider": [
                    {
                        "category": "Excelente",
                        "column-1": <?php echo $value['0']['excelente'];?>
                    },
                    {
                        "category": "Bueno",
                        "column-1": <?php echo $value['0']['bueno'];?>
                    },
                    {
                        "category": "Regular",
                        "column-1": <?php echo $value['0']['regular'];?>
                    },
                    {
                        "category": "Malo",
                        "column-1": <?php echo $value['0']['malo'];?>
                    },

                    {
                        "category": "No Aplica",
                        "column-1": <?php echo $value['0']['no_aplica'];?>
                    }
                ]
            }
    );
</script>
<h3><?php echo $value['0']['pregunta_encuesta']; ?></h3>
<!--<div id="chartdiv_<?php //echo $value['0']['id']; ?>" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>-->
<div id="chartdiv_pie_<?php echo $value['0']['id']; ?>" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
<?php endforeach; ?>
<?php } ?>