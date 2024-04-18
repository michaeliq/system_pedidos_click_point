/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function ultimo_precio() {
    $.ajax({
        url: 'ultimo_precio_proveedor',
        type: 'POST',
        data: {
            MovimientosEntradasDetalleProductoId2: $('#MovimientosEntradasDetalleProductoId2').val()
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == null) {
            null;
        } else {
            $('#MovimientosEntradasDetallePrecioProducto').val(data);
        }
    }
}
function terminar_entrada(id) {
    $.ajax({
        url: 'terminar_entrada',
        type: 'POST',
        data: {
            MovimientosEntradasId: id
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            location.href = 'index';
        } else {
            location.href = document.URL;
        }
    }
}

function quitar_entrada(id, producto) {
    var r = confirm("¿Esta seguro de quitar este producto de la entrada del inventario?");
    if (r == true)
    {
        $.ajax({
            url: 'quitar_entrada',
            type: 'POST',
            data: {
                MovimientosEntradasDetalleId: id,
                productoId: producto
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

function agregar_movimiento(cantidad, precio, producto, orden, cantidad_original) {
//    alert(cantidad);
//    alert(precio);
//    alert(producto);
    $.ajax({
        url: 'agregar_movimiento',
        type: 'POST',
        data: {
            cantidadOrden: cantidad,
            precioProducto: precio,
            productoId: producto,
            ordenId: orden,
            cantidadOrdenOriginal: cantidad_original,
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

