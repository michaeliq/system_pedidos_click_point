$(function () {
    $('#regresar_add').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "index";
        }

    });

    $("#inv_empresa_label").hide();
    $("#inv_empresa_value").hide();

    $("#inv_no_pedido_label").hide();
    $("#inv_no_pedido_value").hide();

});

$(function () {
    $('#MovimientosEntradaFechaMovimiento').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        minDate: -120,
        maxDate: 0
    });

    $('#MovimientosEntradaFacturaFechaVencimiento').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        minDate: -120,
        maxDate: +365
    });

    $("#tooltip_tipo_movimiento").tooltip({});

    /*  ORDENES DE COMPRA   */
    $('#MovimientosEntradaOrdenCompraId').change(function () {
        $.ajax({
            url: 'consultar_orden_compra',
            type: 'POST',
            data: {
                MovimientosEntradaOrdenCompraId: $('#MovimientosEntradaOrdenCompraId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('Error consultando los tipos de movimiento.');
            } else {
                for (var i in data) {
                    $('#MovimientosEntradaTipoMovimientoId').val('1');
                    $('#MovimientosEntradaProveedorId').val(data[i].OrdenCompra.proveedor_id);
                    $('#MovimientosEntradaTipoFormasPagoId').val(data[i].OrdenCompra.tipo_formas_pago_id);
                    $('#MovimientosEntradaFacturaSubtotal').val(data[i].OrdenCompra.orden_valor_sin_iva);
                    $('#MovimientosEntradaFacturaIva').val(data[i].OrdenCompra.orden_valor_iva);
                    $('#MovimientosEntradaFacturaTotal').val(data[i].OrdenCompra.orden_valor_total);
                    $('#MovimientosEntradaFacturaFechaVencimiento').val(data[i].TipoFormasPago.fecha_vencimiento);
                }
            }
        }
    });
    /*  FIN ORDENES DE COMPRA   */

    $('#MovimientosEntradaTipoMovimientoId').change(function () {
        $.ajax({
            url: 'consultar_proveedor_cliente',
            type: 'POST',
            data: {
                MovimientosEntradaTipoMovimientoId: $('#MovimientosEntradaTipoMovimientoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('Error consultando los tipos de movimiento.');
            } else {
                for (var i in data) {
                    if (data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE01' ||
                            data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE02' ||
                            data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE05' ||
                            data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE06') {
                        $("#MovimientosEntradaEmpresaId").val('');
                        $("#inv_proveedor_label").show();
                        $("#inv_proveedor_value").show();
                        $("#inv_empresa_label").hide();
                        $("#inv_empresa_value").hide();

                        $("#inv_no_pedido_label").hide();
                        $("#inv_no_pedido_value").hide();
                        $("#inv_no_factura_label").show();
                        $("#inv_no_factura_value").show();

                        $('#tooltip_tipo_movimiento').attr('data-original-title', data[i].TipoMovimiento.descripcion_tipo_movimiento + ' (Desde: ' + data[i].TipoMovimiento.flujo_inicial + '  Hacia: ' + data[i].TipoMovimiento.flujo_final + ')');
                    }
                    if (data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE03') {
                        $("#MovimientosEntradaProveedorId").val('');
                        $("#inv_proveedor_label").hide();
                        $("#inv_proveedor_value").hide();
                        $("#inv_empresa_label").show();
                        $("#inv_empresa_value").show();

                        $("#MovimientosEntradaFacturaNumero").val('');
                        $("#MovimientosEntradaPedidoId").val('');

                        $('#MovimientosEntradaTipoFormasPagoId').val('');
                        $('#MovimientosEntradaFacturaFechaVencimiento').val('');


                        $('#registrarMovimiento').attr({
                            disabled: true
                        });

                        $("#inv_no_pedido_label").show();
                        $("#inv_no_pedido_value").show();
                        $("#inv_no_factura_label").hide();
                        $("#inv_no_factura_value").hide();

                        $('#tooltip_tipo_movimiento').attr('data-original-title', data[i].TipoMovimiento.descripcion_tipo_movimiento + ' (Desde: ' + data[i].TipoMovimiento.flujo_inicial + '  Hacia: ' + data[i].TipoMovimiento.flujo_final + ')');
                    }
                    if (data[i].TipoMovimiento.codigo_tipo_movimiento == 'IVE04') {
                        $("#MovimientosEntradaProveedorId").val('');
                        $("#inv_proveedor_label").hide();
                        $("#inv_proveedor_value").hide();
                        $("#inv_empresa_label").show();
                        $("#inv_empresa_value").show();

                        /* 
                         for (j = 2; j < 200; j++) {
                         $("#MovimientosEntradaEmpresaId option[value='" + j + "']").remove();
                         }
                         */
                        $('#tooltip_tipo_movimiento').attr('data-original-title', data[i].TipoMovimiento.descripcion_tipo_movimiento + ' (Desde: ' + data[i].TipoMovimiento.flujo_inicial + '  Hacia: ' + data[i].TipoMovimiento.flujo_final + ')');
                    }
                    tipo_movimiento = data[i].TipoMovimiento.codigo_tipo_movimiento;
                }
                consultar_empresas(tipo_movimiento);
                // consultar_categorias(tipo_movimiento);
            }
        }
    });

    function consultar_empresas(tipo_movimiento) {
        $.ajax({
            url: 'consultar_empresas',
            type: 'POST',
            data: {
                MovimientosEntradaTipoMovimientoId: $('#MovimientosEntradaTipoMovimientoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('Error consultando los tipos de movimiento.');
            } else {
                $('#MovimientosEntradaEmpresaId').empty();
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opción';
                        op_default.value = '0';
                        document.getElementById('MovimientosEntradaEmpresaId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Empresa.nombre_empresa;
                    opcion.value = data[i].Empresa.id;
                    document.getElementById('MovimientosEntradaEmpresaId').add(opcion, null);

                    if (tipo_movimiento == 'IVE03') {
                        $("#MovimientosEntradaEmpresaId option[value='1']").remove();

                    }
                    if (tipo_movimiento == 'IVE04') {
                        for (j = 2; j < 200; j++) {
                            $("#MovimientosEntradaEmpresaId option[value='" + j + "']").remove();
                        }
                    }
                }
            }
        }
    }

    function consultar_categorias(tipo_movimiento) {
        $.ajax({
            url: 'consultar_categorias',
            type: 'POST',
            data: {},
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('Error consultando los tipos de movimiento.');
            } else {
                $('#MovimientosEntradaTipoCategoriaId').empty();
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opción';
                        op_default.value = '0';
                        document.getElementById('MovimientosEntradaTipoCategoriaId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].TipoCategoria.tipo_categoria_descripcion;
                    opcion.value = data[i].TipoCategoria.id;
                    document.getElementById('MovimientosEntradaTipoCategoriaId').add(opcion, null);

                    if (tipo_movimiento == 'IVE06') {
                        $("#MovimientosEntradaTipoCategoriaId option[value='1']").remove();
                        $("#MovimientosEntradaTipoCategoriaId option[value='2']").remove();
                        $("#MovimientosEntradaTipoCategoriaId option[value='3']").remove();
                        $("#MovimientosEntradaTipoCategoriaId option[value='4']").remove();
                        $("#MovimientosEntradaTipoCategoriaId option[value='5']").remove();

                    }
                    /*if (tipo_movimiento == 'IVE04') {
                     $("#MovimientosEntradaTipoCategoriaId option[value='1']").remove();
                     $("#MovimientosEntradaTipoCategoriaId option[value='2']").remove();
                     $("#MovimientosEntradaTipoCategoriaId option[value='3']").remove();
                     $("#MovimientosEntradaTipoCategoriaId option[value='4']").remove();
                     }*/
                }
            }
        }
    }

    $('#MovimientosEntradaPedidoId').change(function () {
        $.ajax({
            url: 'validar_pedido',
            type: 'POST',
            data: {
                MovimientosEntradaPedidoId: $('#MovimientosEntradaPedidoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == '') {
                $('#registrarMovimiento').attr({
                    disabled: true
                });
                document.getElementById('sucursal').innerHTML = "";
                alert('El pedido (' + $('#MovimientosEntradaPedidoId').val() + ') no existe o no se encuentra en estado despachado. Por favor verificar.');
            } else {
                for (var i in data) {
                    $('#MovimientosEntradaEmpresaId').val(data[i].Pedido.empresa_id);
                    document.getElementById('sucursal').innerHTML = data[i].Sucursale.nombre_sucursal + ' - ' + data[i].TipoPedido.nombre_tipo_pedido;
                }
                $('#registrarMovimiento').attr({
                    disabled: false
                });
            }
        }
    });

    $('#MovimientosEntradaProveedorId').change(function () {
        $.ajax({
            url: 'consultar_forma_pago',
            type: 'POST',
            data: {
                MovimientosEntradaProveedorId: $('#MovimientosEntradaProveedorId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('Sin parametro de forma de pago en Proveedores. Actualice el proveedor.');
            } else {
                for (var i in data) {
                    $('#MovimientosEntradaTipoFormasPagoId').val(data[i].Proveedore.tipo_formas_pago_id);
                    $('#MovimientosEntradaFacturaFechaVencimiento').val(data[i].TipoFormasPago.fecha_vencimiento);
                }
            }
        }

    });


    $('#MovimientosEntradaFacturaSubtotal').change(function () {
        var subtotal = $('#MovimientosEntradaFacturaSubtotal').val();
        var iva = $('#MovimientosEntradaFacturaIva').val();
        var total = 0;
        total = subtotal + iva;

        $('#MovimientosEntradaFacturaTotal').val(total);
    });


    $('#MovimientosEntradaFacturaIva').change(function () {
        var subtotal = $('#MovimientosEntradaFacturaSubtotal').val();
        var iva = $('#MovimientosEntradaFacturaIva').val();
        var total = 0;
        total = (subtotal * 1) + (iva * 1);

        $('#MovimientosEntradaFacturaTotal').val(total);
    });

});
