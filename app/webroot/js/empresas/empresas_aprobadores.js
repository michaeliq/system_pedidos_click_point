function search_nombre_regional() {
    // Declare variables 
    var input, filter, table, tr, td, i;
    input = document.getElementById("nombre_regional");
    filter = input.value.toUpperCase();
    table = document.getElementById("permisos_regionales");
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


function search_nombre_usuario() {
    // Declare variables 
    var input, filter, table, tr, td, i;
    input = document.getElementById("nombre_usuario");
    filter = input.value.toUpperCase();
    table = document.getElementById("permisos_regionales");
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


