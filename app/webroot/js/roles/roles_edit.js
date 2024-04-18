/* 
 * To change this template, choose Tools | Templates
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

$(document).ready(function(){
    $(".check_todos").click(function(event){
        if($(this).is(":checked")) {
            $(".ck:checkbox:not(:checked)").attr("checked", "checked");
        }else{
            $(".ck:checkbox:checked").removeAttr("checked");
        }
    });
    
    if($('#RoleRolInternoExterno').is(":checked")){
        $("label[for='RoleRolInternoExterno']").text("Interno");    
    }else{
        $("label[for='RoleRolInternoExterno']").text("Externo a CLEANEST L&C");    
    }
});

function label_int_ext(parameter){
    if($('#'+parameter).is(":checked")){
        $("label[for='"+parameter+"']").text("Interno");    
    }else{
        $("label[for='"+parameter+"']").text("Externo a CLEANEST L&C");    
    }
}
