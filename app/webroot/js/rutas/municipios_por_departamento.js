$(function () {
    $('#RutaDepartamentoId').change(function () {
        $.ajax({
            url: '../users/municipios/',
            type: 'POST',
            data: {
                UserDepartamentoId: $('#RutaDepartamentoId').val()
            },
            async: false,
            dataType: "json",
            success: onSuccess
        });
        function onSuccess(data) {
            if (data == null) {
                alert('No hay Municipios para este Departamento');
            } else {
                document.getElementById('RutaMunicipioId').innerHTML = '';
                $('#RutaMunicipioId').attr({
                    disabled: false
                });
                for (var i in data) {
                    if (i == 0) {
                        var op_default = document.createElement('option');
                        op_default.text = 'Seleccione una Opcion';
                        op_default.value = '0';
                        document.getElementById('RutaMunicipioId').add(op_default, null);
                    }

                    var opcion = document.createElement('option');
                    opcion.text = data[i].Municipio.nombre_municipio;
                    opcion.value = data[i].Municipio.id;
                    document.getElementById('RutaMunicipioId').add(opcion, null);

                }
                if ($('#RutaDepartamentoId').val() == -1)
                    $('#RutaMunicipioId').attr({
                        disabled: true
                    });
            }
        }
    });
});