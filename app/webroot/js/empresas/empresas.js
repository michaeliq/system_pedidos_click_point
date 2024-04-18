

/*  Cargar municipios a partir del departamento seleccionado. */
$(function () {
    $('#EmpresaDepartamentoId').change(function () {
        $.ajax({
            url: '../users/municipios',
            type: 'POST',
            data: {
                UserDepartamentoId: $('#EmpresaDepartamentoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Municipios para este Departamento');
            } else {
                document.getElementById('EmpresaMunicipioId').innerHTML = '';
                $('#EmpresaMunicipioId').attr({
                    disabled: false
                });
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opcion';
                        op_default.value = '0';
                        document.getElementById('EmpresaMunicipioId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Municipio.nombre_municipio;
                    opcion.value = data[i].Municipio.id;
                    document.getElementById('EmpresaMunicipioId').add(opcion, null);

                }
                if ($('#EmpresaDepartamentoId').val() == -1)
                    $('#EmpresaMunicipioId').attr({
                        disabled: true
                    });
            }
        }
    });
});