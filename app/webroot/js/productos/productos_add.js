/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    $('#regresar_add').click(function(){
        if(confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")){
            window.location = "index";
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
});