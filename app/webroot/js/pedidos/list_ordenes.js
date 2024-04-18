/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $(".check_todos").click(function (event) {
        if ($(this).is(":checked")) {
            $(".ck:checkbox:not(:checked)").attr("checked", "checked");
        } else {
            $(".ck:checkbox:checked").removeAttr("checked");
        }
    });
});

$(function () {
    $('#PedidoEmpresaId').change(function () {
        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                UserEmpresaId: $('#PedidoEmpresaId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Sucursales para esta Empresa');
            } else {
                document.getElementById('PedidoSucursalId').innerHTML = '';
                document.getElementById('PedidoRegionalSucursal').innerHTML = '';
                $('#PedidoSucursalId').attr({
                    disabled: false
                });
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Todos';
                        op_default.value = '0';
                        document.getElementById('PedidoSucursalId').add(op_default, null);

                        var op_default_reg = document.createElement('option');
                        op_default_reg.text = 'Todos';
                        op_default_reg.value = '0';
                        document.getElementById('PedidoRegionalSucursal').add(op_default_reg, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Sucursale.nombre_sucursal;
                    opcion.value = data[i].Sucursale.id;
                    document.getElementById('PedidoSucursalId').add(opcion, null);

                    var opcion_reg = document.createElement('option');
                    opcion_reg.text = data[i].Sucursale.regional_sucursal;
                    opcion_reg.value = data[i].Sucursale.regional_sucursal;
                    document.getElementById('PedidoRegionalSucursal').add(opcion_reg, null);

                }
                $("#PedidoRegionalSucursal option").each(function () {
                    $(this).siblings("[value='" + this.value + "']").remove();
                });

                if ($('#PedidoEmpresaId').val() == -1)
                    $('#PedidoSucursalId').attr({
                        disabled: true
                    });
            }
        }
    });
});

$(function () {
    $('#PedidoRegionalSucursal').change(function () {
        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                PedidoEmpresaId2: $('#PedidoEmpresaId option:selected').val(),
                PedidoRegionalSucursal2: $('#PedidoRegionalSucursal option:selected').text()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Sucursales para esta Empresa');
            } else {
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

                }
                if ($('#PedidoRegionalSucursal').val() == -1)
                    $('#PedidoSucursalId').attr({
                        disabled: true
                    });
            }
        }
    });
});
$(function () {
    $('#PedidoPedidoFecha').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        //yearRange: "-120:+0",
        maxDate: '0'
    });
});