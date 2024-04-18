/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
    $('#regresar_edit').click(function () {
        if (confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")) {
            window.location = "../index/" + $('#SucursaleIdEmpresa').val();
        }
    });
});

$(function () {
    $('#regresar_view').click(function () {
        window.location = "../index/" + $('#SucursaleIdEmpresa').val();
    });
});

/*  Cargar municipios a partir del departamento seleccionado. */
$(function () {
    $('#SucursaleDepartamentoId').change(function () {
        $.ajax({
            url: '../../users/municipios/',
            type: 'POST',
            data: {
                UserDepartamentoId: $('#SucursaleDepartamentoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Municipios para este Departamento');
            } else {
                document.getElementById('SucursaleMunicipioId').innerHTML = '';
                $('#SucursaleMunicipioId').attr({
                    disabled: false
                });
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opcion';
                        op_default.value = '0';
                        document.getElementById('SucursaleMunicipioId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Municipio.nombre_municipio;
                    opcion.value = data[i].Municipio.id;
                    document.getElementById('SucursaleMunicipioId').add(opcion, null);

                }
                if ($('#SucursaleDepartamentoId').val() == -1)
                    $('#SucursaleMunicipioId').attr({
                        disabled: true
                    });
            }
        }
    });
});
//31052018
$(function () {
    $('#SucursaleRegionalId').change(function () {
        regional_sucursal = $('#SucursaleRegionalId option:selected').text();
        $('#SucursaleRegionalSucursal').val(regional_sucursal);
    });
});
