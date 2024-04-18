/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    $('#PedidoFechaEntrega1').datepicker({
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
    
  $('#PedidoFechaEntrega2').datepicker({
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

