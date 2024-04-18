/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    $('#regresar_edit').click(function(){
        if(confirm("ATENCIÓN: Esta seguro de regresar, se perderá la información digitada")){
            window.location = "../index";
        }
    });
});

$(function(){
    $('#regresar_view').click(function(){
        window.location = "../index";
    });
});

$(function() {
    $('#UserFechaNacimiento').datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        yearRange: '1950',
        maxDate: 0
    });
});

/*  Cargar municipios a partir del departamento seleccionado. */
$(function() {
    $('#UserDepartamentoId').change(function(){
        $.ajax({
            url:'../municipios',
            type:'POST',
            data:{
                UserDepartamentoId:$('#UserDepartamentoId').val()
            },
            async:false,
            dataType:"json",
            success:onSuccess
        });
        function onSuccess(data){
            if(data==null){
                alert('No hay Municipios para este Departamento');
            } else{
                document.getElementById('UserMunicipioId').innerHTML='';
                $('#UserMunicipioId').attr({
                    disabled:false
                });
                for ( var i in data ){
                    if(i == 0){
                        var op_default=document.createElement('option');
                        op_default.text='Seleccione una Opcion';
                        op_default.value='0';
                        document.getElementById('UserMunicipioId').add(op_default,null);
                    }   
                    
                    var opcion=document.createElement('option');
                    opcion.text=data[i].Municipio.nombre_municipio;
                    opcion.value=data[i].Municipio.id;
                    document.getElementById('UserMunicipioId').add(opcion,null);

                }
                if($('#UserDepartamentoId').val()==-1)
                    $('#UserMunicipioId').attr({
                        disabled:true
                    });
            }
        }
    });
});

/*  Cargar sucursales a partir de la empresa seleccionada. */
$(function() {
    $('#UserEmpresaId').change(function(){
        $.ajax({
            url:'../sucursales',
            type:'POST',
            data:{
                UserEmpresaId:$('#UserEmpresaId').val()
            },
            async:false,
            dataType:"json",
            success:onSuccess
        });
        function onSuccess(data){
            if(data==null){
                alert('No hay Sucursales para esta Empresa');
            } else{
                document.getElementById('UserSucursalId').innerHTML='';
                $('#UserSucursalId').attr({
                    disabled:false
                });
                for ( var i in data ){
                    if(i == 0){
                        var op_default=document.createElement('option');
                        op_default.text='Seleccione una Opcion';
                        op_default.value='0';
                        document.getElementById('UserSucursalId').add(op_default,null);
                    }   
                    
                    var opcion=document.createElement('option');
                    opcion.text = data[i].Sucursale.regional_sucursal+' - '+data[i].Sucursale.nombre_sucursal;
                    opcion.value=data[i].Sucursale.id;
                    document.getElementById('UserSucursalId').add(opcion,null);

                }
                if($('#UserEmpresaId').val()==-1)
                    $('#UserSucursalId').attr({
                        disabled:true
                    });
            }
        }
    });
});