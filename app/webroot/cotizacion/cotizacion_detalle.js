/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function modificar(texto_producto, cantidad_producto, id_detalle) {
    $('#add_producto').hide();
    $('#edit_producto_' + id_detalle).show();
    //$('#CotizacionDetalleProductoId2Edit').val(texto_producto);
    $('#CotizacionDetalleCantidadPedidoEdit' + id_detalle).val(cantidad_producto);
    $('#CotizacionDetalleIdEdit' + id_detalle).val(id_detalle);
}

function actualizar_cotizacion(id_detalle) {
    $.ajax({
        url: 'modificar_cotizacion',
        type: 'POST',
        data: {
            CotizacionDetalleId: $('#CotizacionDetalleIdEdit' + id_detalle).val(),
            CotizacionDetalleCantidadPedido: $('#CotizacionDetalleCantidadPedidoEdit' + id_detalle).val(),
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

function cerrar_actualizar(id_detalle) {
    $('#add_producto').show();
    $('#edit_producto_' + id_detalle).hide();
    $('#CotizacionDetalleProductoId2Edit' + id_detalle).val('');
    $('#CotizacionDetalleCantidadPedidoEdit' + id_detalle).val('');
    $('#CotizacionDetalleIdEdit' + id_detalle).val('');
}

function quitar(id) {
    var r = confirm("¿Esta seguro de quitar este producto de la cotización?");
    if (r == true)
    {
        $.ajax({
            url: 'quitar_cotizacion',
            type: 'POST',
            data: {
                CotizacionDetalleId: id
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

function cancelar_cotizacion(id, llamada) {
    var r = confirm("¿Esta seguro de cancelar esta cotización?");
    if (r == true)
    {
        $.ajax({
            url: 'cancelar_cotizacion',
            type: 'POST',
            data: {
                CotizacionDetalleId: id
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                window.location = "iniciar_llamada/" + llamada;
            } else {
                location.href = document.URL;
            }
        }
    }
}

function terminar_cotizacion(id, llamada) {
    $.ajax({
        url: 'terminar_cotizacion',
        type: 'POST',
        data: {
            CotizacionDetalleId: id
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            url = "../listadoLlamadas/cotizacion_pdf/" + id;
            window.open(url, '_blank');
            window.location = "iniciar_llamada/" + llamada;
        } else {
            location.href = document.URL;
        }
    }
}

function aprobar_pedido_ok(id) {
    $.ajax({
        url: '../aprobar_pedido_ok',
        type: 'POST',
        data: {
            CotizacionDetalleId: id
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            window.location = "../list_ordenes";
        } else {
            location.href = document.URL;
        }
    }

}

