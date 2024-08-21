$(function () {
    $('#LocalidadesEmpresaId').change(function () {
        $('#LocalidadesSucursalId').empty();

        $('#LocalidadesSucursalId').attr({
            disabled: true
        });

        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                PedidoEmpresaId: $('#LocalidadesEmpresaId option:selected').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Regionales para esta Empresa');
            } else {
                document.getElementById('LocalidadesSucursalId').innerHTML = '';
                $('#LocalidadesRegionalId').attr({
                    disabled: false
                });

                var op_default = document.createElement('option');
                op_default.text = 'Seleccione una Opción';
                op_default.value = '0';
                document.getElementById('LocalidadesRegionalId').add(op_default, null);

                for (var i in data) {
                    var opcion = document.createElement('option');
                    opcion.text = data[i];
                    opcion.value = data[i];
                    document.getElementById('LocalidadesRegionalId').add(opcion, null);
                }
            }
        }
    });

    $('#LocalidadesRegionalId').change(function () {
        $('#LocalidadesSucursalId').empty();
        $.ajax({
            url: '../users/sucursales/',
            type: 'POST',
            data: {
                PedidoEmpresaId: $('#LocalidadesEmpresaId option:selected').val(),
                PedidoRegionalSucursal_admin: $('#LocalidadesRegionalId option:selected').text()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Sucursales para esta Empresa');
            } else {
                document.getElementById('LocalidadesSucursalId').innerHTML = '';
                $('#LocalidadesSucursalId').attr({
                    disabled: false
                });

                var op_default = document.createElement('option');
                op_default.text = 'Seleccione una Opción';
                op_default.value = '0';
                document.getElementById('LocalidadesSucursalId').add(op_default, null);

                for (var i in data) {

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Sucursale.nombre_sucursal;
                    opcion.value = data[i].Sucursale.id;
                    document.getElementById('LocalidadesSucursalId').add(opcion, null);

                    //arr_sucursales.push(data[i]);

                }
                if ($('#LocalidadesRegionalId').val() == -1)
                    $('#LocalidadesSucursalId').attr({
                        disabled: true
                    });
            }
        }
    });
});