/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
    $('#regresar_edit').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "../index";
        }
    });

    $("#ProductoTipoCategoriaId").change(function(){
        if(this.value == 11 || this.value == 10){
            $("#ProductoCapacidadProducto").get(0).type = "text";
            $("#ProductoCapacidadProducto").get(0).className = "col-md-1";
        }else{
            $("#ProductoCapacidadProducto").get(0).type = "hidden";
        }
    });

    $('#regresar_edit2').click(function () {
        window.location = "../index";
    });

    document.getElementById('nuevo_precio').innerHTML = '';
    $('#button_actualizar').attr({
        disabled: true
    });
    $('#ProductoPorcentaje').change(function () {
        document.getElementById('nuevo_precio').innerHTML = '';
        // alert($('#ProductoPorcentaje').val());
        porcentaje = $('#ProductoPorcentaje').val() / 100;
        precio = $('#ProductoPrecioProducto').val();

        nuevo_precio = (precio * 1 * porcentaje * 1) + precio * 1;
        document.getElementById('nuevo_precio').innerHTML = '<b>Precio Producto: </b>' + precio + '<br><b>Porcentaje: </b>' + porcentaje + '%<br><b>Nuevo Precio: ' + nuevo_precio + '</b>';

        $('#button_actualizar').attr({
            disabled: false
        });
    });
});

function label_int_ext(parameter) {
    if ($('#' + parameter).is(":checked")) {
        $("label[for='" + parameter + "']").text("CLICK POINT");
    } else {
        $("label[for='" + parameter + "']").text("Otro");
    }
}