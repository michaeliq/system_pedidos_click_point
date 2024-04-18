/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function ultimo_precio() {
    $.ajax({
        url: '../movimientos_entradas/ultimo_precio_proveedor',
        type: 'POST',
        data: {
            MovimientosEntradasDetalleProductoId2: $('#OrdenComprasDetalleProductoId2').val()
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == null) {
            null;
        } else {
            $('#OrdenComprasDetallePrecioProducto').val(data);
        }
    }
}

function agregar_sugerido(cantidad, precio, producto) {
//    alert(cantidad);
//    alert(precio);
//    alert(producto);
    $.ajax({
        url: 'agregar_sugerido',
        type: 'POST',
        data: {
            cantidadOrden: cantidad,
            precioProducto: precio,
            productoId: producto,
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            location.href = document.URL;
        } else {
            location.href = document.URL;
        }
    }

}

function cancelar_orden(id) {
    $.ajax({
        url: 'cancelar_orden',
        type: 'POST',
        data: {
            OrdenCompraId: id
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            location.href = 'listar_ordenes';
        } else {
            location.href = document.URL;
        }
    }
}

function terminar_orden(id) {
    $.ajax({
        url: 'terminar_orden',
        type: 'POST',
        data: {
            OrdenCompraId: id
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            location.href = 'listar_ordenes';
        } else {
            location.href = document.URL;
        }
    }
}

function quitar_producto(id) {
    var r = confirm("¿Esta seguro de quitar este producto de la entrada de la orden?");
    if (r == true)
    {
        $.ajax({
            url: 'quitar_producto',
            type: 'POST',
            data: {
                OrdenComprasDetalleId: id
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                location.href = document.URL;
            } else {
                location.href = document.URL;
            }
        }
    }
}