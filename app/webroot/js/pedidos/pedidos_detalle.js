/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    const fecha_vencimiento = $(".fecha_vencimiento");
    fecha_vencimiento.each((i, obj)=>{
        obj.type = "date"
    })
});

function modificar(texto_producto, cantidad_producto, id_detalle) {
    $('#add_producto').hide();
    $('#edit_producto').show();
    $('#PedidosDetalleProductoId2Edit').val(texto_producto);
    $('#PedidosDetalleCantidadPedidoEdit').val(cantidad_producto);
    $('#PedidosDetalleIdEdit').val(id_detalle);
}

function modificar2(texto_producto, cantidad_producto, id_detalle) {
    $('#add_producto').hide();
    $('#edit_producto_' + id_detalle).show();
    //$('#PedidosDetalleProductoId2Edit').val(texto_producto);
    $('#PedidosDetalleCantidadPedidoEdit' + id_detalle).val(cantidad_producto);
    $('#PedidosDetalleIdEdit' + id_detalle).val(id_detalle);
}


function actualizar_pedido() {
    $.ajax({
        url: 'modificar_pedido',
        type: 'POST',
        data: {
            PedidosDetalleId: $('#PedidosDetalleIdEdit').val(),
            PedidosDetalleCantidadPedido: $('#PedidosDetalleCantidadPedidoEdit').val(),
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

function actualizar_pedido_2() {
    $.ajax({
        url: '../modificar_pedido',
        type: 'POST',
        data: {
            PedidosDetalleId: $('#PedidosDetalleIdEdit').val(),
            PedidosDetalleCantidadPedido: $('#PedidosDetalleCantidadPedidoEdit').val(),
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

function actualizar_pedido_3(id_detalle) {
    $.ajax({
        url: '../modificar_pedido',
        type: 'POST',
        data: {
            PedidosDetalleId: $('#PedidosDetalleIdEdit' + id_detalle).val(),
            PedidosDetalleCantidadPedido: $('#PedidosDetalleCantidadPedidoEdit' + id_detalle).val(),
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

function actualizar_pedido_4(id_detalle) {
    $.ajax({
        url: 'modificar_pedido',
        type: 'POST',
        data: {
            PedidosDetalleId: $('#PedidosDetalleIdEdit' + id_detalle).val(),
            PedidosDetalleCantidadPedido: $('#PedidosDetalleCantidadPedidoEdit' + id_detalle).val(),
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

function cerrar_actualizar() {
    $('#add_producto').show();
    $('#edit_producto').hide();
    $('#PedidosDetalleProductoId2Edit').val('');
    $('#PedidosDetalleCantidadPedidoEdit').val('');
    $('#PedidosDetalleIdEdit').val('');
}

function cerrar_actualizar2(id_detalle) {
    $('#add_producto').show();
    $('#edit_producto_' + id_detalle).hide();
    $('#PedidosDetalleProductoId2Edit' + id_detalle).val('');
    $('#PedidosDetalleCantidadPedidoEdit' + id_detalle).val('');
    $('#PedidosDetalleIdEdit' + id_detalle).val('');
}

function quitar(id) {
    var r = confirm("¿Esta seguro de quitar este producto de la orden de pedido?");
    if (r == true)
    {
        $.ajax({
            url: 'quitar_pedido',
            type: 'POST',
            data: {
                PedidosDetalleId: id
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

function quitar_2(id) {
    var r = confirm("¿Esta seguro de quitar este producto de la orden de pedido?");
    if (r == true)
    {
        $.ajax({
            url: '../quitar_pedido',
            type: 'POST',
            data: {
                PedidosDetalleId: id
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


function cancelar_pedido(id) {
    var r = confirm("¿Esta seguro de cancelar esta orden de pedido?");
    if (r == true)
    {
        $.ajax({
            url: 'cancelar_pedido',
            type: 'POST',
            data: {
                PedidosDetalleId: id
            },
            async: false,
            dataType: "text",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == true) {
                window.location = "index";
            } else {
                location.href = document.URL;
            }
        }
    }
}

function cancelar_pedido_2(id) {
    var r = confirm("¿Esta seguro de cancelar esta orden de pedido?");
    if (r == true)
    {
        $.ajax({
            url: '../cancelar_pedido',
            type: 'POST',
            data: {
                PedidosDetalleId: id
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
}

function terminar_pedido(id) {
    $.ajax({
        url: 'terminar_pedido',
        type: 'POST',
        data: {
            PedidosDetalleId: id,
            PedidosDetalleObservaciones: $('#PedidosDetalleObservaciones').val()
        },
        async: false,
        dataType: "text",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == true) {
            window.location = "ver_pedido";
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
            PedidosDetalleId: id
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

