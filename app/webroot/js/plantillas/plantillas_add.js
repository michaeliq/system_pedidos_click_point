/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    $('#regresar_add').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "index";
        }
    });
});

$(document).ready(function () {
    $(".check_todos").click(function (event) {
        if ($(this).is(":checked")) {
            $(".ck:checkbox:not(:checked)").attr("checked", "checked");
        } else {
            $(".ck:checkbox:checked").removeAttr("checked");
        }
    });

    $("#PlantillaTipoPedidoId").change(function () {
        if ($("#PlantillaTipoPedidoId").val() == '1') {
            $('.class_1').show();
            $('.class_2').show();
            $('.class_3').show();
            $('.class_4').show();
            $('.class_5').hide();
            $('.class_6').hide();
            $('.class_7').hide();
        }

        if ($("#PlantillaTipoPedidoId").val() == '2') {
            $('.class_1').hide();
            $('.class_2').hide();
            $('.class_3').hide();
            $('.class_4').hide();
            $('.class_5').show();
            $('.class_6').hide();
            $('.class_7').hide();
        }

        if ($("#PlantillaTipoPedidoId").val() == '3') {
            $('.class_1').hide();
            $('.class_2').hide();
            $('.class_3').hide();
            $('.class_4').hide();
            $('.class_5').show(); // 05/01/2021
            $('.class_6').show();
            $('.class_7').hide();
        }

        if ($("#PlantillaTipoPedidoId").val() == '4') {
            $('.class_1').show();
            $('.class_2').show();
            $('.class_3').show();
            $('.class_4').show();
            $('.class_5').hide();
            $('.class_6').hide();
            $('.class_7').show();
        }

        if ($("#PlantillaTipoPedidoId").val() == '5') {
            $('.class_1').hide();
            $('.class_2').show();
            $('.class_3').hide();
            $('.class_4').hide();
            $('.class_5').hide();
            $('.class_6').hide();
            $('.class_7').hide();
        }
    });

});



// 