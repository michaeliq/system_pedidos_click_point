/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var arr_sucursales = [];
var arr_tipos = [];

$(function () {
    $('#regresar_add').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "index";
        }
    });
});

$(function () {
    $('#PedidoEmpresaId').change(function () {
        $('#PedidoRegionalSucursal').empty();
        $('#PedidoSucursalId').empty();
        $('#PedidoTipoPedidoId').empty();

        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                PedidoEmpresaId: $('#PedidoEmpresaId option:selected').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Regionales para esta Empresa');
            } else {
                document.getElementById('PedidoRegionalSucursal').innerHTML = '';
                $('#PedidoRegionalSucursal').attr({
                    disabled: false
                });
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opción';
                        op_default.value = '0';
                        document.getElementById('PedidoRegionalSucursal').add(op_default, null);
                    }
                    var opcion = document.createElement('option');
                    opcion.text = data[i];
                    opcion.value = data[i];
                    document.getElementById('PedidoRegionalSucursal').add(opcion, null);

                }
                if ($('#PedidoEmpresaId').val() == -1)
                    $('#PedidoRegionalSucursal').attr({
                        disabled: true
                    });
            }
        }
    });

    $('#PedidoRegionalSucursal').change(function () {
        $('#PedidoSucursalId').empty();
        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                PedidoEmpresaId: $('#PedidoEmpresaId option:selected').val(),
                PedidoRegionalSucursal_admin: $('#PedidoRegionalSucursal option:selected').text()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Sucursales para esta Empresa');
            } else {
                consultar_cronogramas($('#PedidoEmpresaId option:selected').val());
                document.getElementById('PedidoSucursalId').innerHTML = '';
                $('#PedidoSucursalId').attr({
                    disabled: false
                });

                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opción';
                        op_default.value = '0';
                        document.getElementById('PedidoSucursalId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Sucursale.regional_sucursal + ' - ' + data[i].Sucursale.nombre_sucursal;
                    opcion.value = data[i].Sucursale.id;
                    document.getElementById('PedidoSucursalId').add(opcion, null);

                    arr_sucursales.push(data[i]);

                }
                if ($('#PedidoRegionalSucursal').val() == -1)
                    $('#PedidoSucursalId').attr({
                        disabled: true
                    });
            }
        }
    });

    $('#PedidoSucursalId').change(function () {
        for (var i in arr_sucursales) {
            if ($('#PedidoSucursalId').val() == arr_sucursales[i].Sucursale.id) {
                document.getElementById("data-departamento").innerHTML = '';
                document.getElementById("data-departamento").innerHTML = arr_sucursales[i].Departamento.nombre_departamento;
                document.getElementById("data-municipio").innerHTML = '';
                document.getElementById("data-municipio").innerHTML = arr_sucursales[i].Municipio.nombre_municipio;

                $('#PedidoTipoPedidoId').val(1);
                $('#PedidoPedidoDireccion').val(arr_sucursales[i].Sucursale.direccion_sucursal);
                $('#PedidoPedidoTelefono').val(arr_sucursales[i].Sucursale.telefono_sucursal);

                $('#PedidoDepartamentoId').val(arr_sucursales[i].Empresa.departamento_id);
                $('#PedidoMunicipioId').val(arr_sucursales[i].Empresa.municipio_id);

            }
        }

    });

});





function consultar_cronogramas(empresa) {
    $('#PedidoTipoPedidoId').attr({
        disabled: true
    });

    $.ajax({
        url: '../users/cronogramas/',
        type: 'POST',
        data: {
            PedidoEmpresaId: $('#PedidoEmpresaId option:selected').val(),
        },
        async: false,
        dataType: "json",
        success: onSuccess
    });
    function onSuccess(data) {
        if (data == null) {
            alert('No hay respuesta');
        } else {
            document.getElementById('PedidoTipoPedidoId').innerHTML = '';
            $('#PedidoTipoPedidoId').attr({
                disabled: false
            });

            for (var i in data) {
                if (i == 0) {
                    var op_default = document.createElement('option');
                    op_default.text = 'Seleccione una Opción';
                    op_default.value = '0';
                    document.getElementById('PedidoTipoPedidoId').add(op_default, null);
                }

                var opcion = document.createElement('option');
                opcion.text = data[i].TipoPedido.nombre_tipo_pedido;
                opcion.value = data[i].TipoPedido.id;
                document.getElementById('PedidoTipoPedidoId').add(opcion, null);

                arr_tipos.push(data[i]);

            }
        }
    }
}