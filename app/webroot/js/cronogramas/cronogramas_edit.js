/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    $('#regresar_edit').click(function(){
        if(confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")){
            window.location = "../index";
        }
    });
});

$(function(){
    $('#regresar_view').click(function(){
        window.location = "../index";
    });
});

$(function () {
    $('#CronogramaFechaInicio').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        yearRange: '2016',
        minDate: 0,
        maxDate: +365
    });
    
  $('#CronogramaFechaFin').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        yearRange: '2016',
        minDate: 0,
        maxDate: +365
    });
});

