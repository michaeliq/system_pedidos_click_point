$(function () {
    let sucursalId = null
    
    $('.RutaSucursal').on("click", function () {
        sucursalId = $(this).attr('id')
        let rutaId = $('#ruta_id').attr('value')
        
        $.ajax({
            url: '../update_sucursale_en_ruta',
            type: 'POST',
            data: {
                ruta_id: rutaId,
                sucursal_id: sucursalId
            },
            async: true,
            dataType: "json",
            success: onSuccess
        });

        function onSuccess(data) {
            
            if (data == null) {
                alert('Hubo un fallo al actualizar los datos');
            } else {
                if(data.ruta_id == rutaId){
                    $(`#${sucursalId} a`).attr('class',"glyphicon glyphicon-remove")
                }else{
                    $(`#${sucursalId} a`).attr('class',"glyphicon glyphicon-ok")
                }

                alert("Registro actualizado!")
            }
        }
    })
})