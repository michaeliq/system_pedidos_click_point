/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function search_codigo_producto() {
    // Declare variables 
    var input, filter, table, tr, td, i;
    input = document.getElementById("codigo_producto_sh");
    filter = input.value.toUpperCase();
    table = document.getElementById("productos_plantilla");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function search_nombre_producto() {
    // Declare variables 
    var input, filter, table, tr, td, i;
    input = document.getElementById("nombre_producto_sh");
    filter = input.value.toUpperCase();
    table = document.getElementById("productos_plantilla");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

