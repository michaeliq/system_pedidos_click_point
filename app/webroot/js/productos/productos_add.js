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