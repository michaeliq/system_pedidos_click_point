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
});

$(document).ready(function(){
    $(".check_todos").click(function(event){
        if($(this).is(":checked")) {
            $(".ck:checkbox:not(:checked)").attr("checked", "checked");
        }else{
            $(".ck:checkbox:checked").removeAttr("checked");
        }
    });
});

function label_int_ext(parameter){
    if($('#'+parameter).is(":checked")){
        $("label[for='"+parameter+"']").text("Interno");    
    }else{
        $("label[for='"+parameter+"']").text("Externo a CLEANEST L&C");    
    }
}