/*  Cargar sucursales a partir de la empresa seleccionada. */
$(function() {
    $('#UserEmpresaIdF').change(function(){
        $.ajax({
            url:'sucursales',
            type:'POST',
            data:{
                UserEmpresaId:$('#UserEmpresaIdF').val()
            },
            async:false,
            dataType:"json",
            success:onSuccess
        });
        function onSuccess(data){
            if(data==null){
                alert('No hay Sucursales para esta Empresa');
            } else{
                document.getElementById('UserSucursalIdF').innerHTML='';
                $('#UserSucursalIdF').attr({
                    disabled:false
                });
                for ( var i in data ){
                    if(i == 0){
                        var op_default=document.createElement('option');
                        op_default.text='Seleccione una Opcion';
                        op_default.value='0';
                        document.getElementById('UserSucursalIdF').add(op_default,null);
                    }   
                    
                    var opcion=document.createElement('option');
                    opcion.text = data[i].Sucursale.regional_sucursal+' - '+data[i].Sucursale.nombre_sucursal;
                    opcion.value=data[i].Sucursale.id;
                    document.getElementById('UserSucursalIdF').add(opcion,null);

                }
                if($('#UserEmpresaIdF').val()==-1)
                    $('#UserSucursalIdF').attr({
                        disabled:true
                    });
            }
        }
    });
});