<?php

for($i=0;$i<$rotulos;$i++){
    $padding = 'padding: 15px;';
       if($i%2==0){
        $float = 'float:left;';
    }else{
        $float = '';
    }
    
?>
<div style="font-size: 20px; border-bottom: #000000 thin; <?php  echo $padding; echo $float; ?>">
    <b>No Orden: #000<?php echo  $detalles['0']['PedidosDetalle']['pedido_id'] ?></b><br>
    <b>CLEANEST PRODUCTOS DE LIMPIEZA Y CONSERVACION S.A.S<br> Nit 900.415.585-3 <br> KM 1.2 V&iacute;a Siberia, entrada al parque La Florida. <br> Parque Industrial Terrapuerto, Bodega 32. Bogot&aacute;.<br> Tel: 6068433 - 4849120</b><br><br>
    <b>Sucursal: <?php echo  $detalles['0']['Sucursale']['nombre_sucursal'] ?></b><br>
    <b>Regional: <?php echo  $detalles['0']['Sucursale']['regional_sucursal'] ?></b><br>
    <b>Ciudad: <?php echo  $detalles['0']['Departamento']['nombre_departamento'] ?> - <?php echo  $detalles['0']['Municipio']['nombre_municipio'] ?></b><br>
    <b>Direcci&oacute;n: <?php echo  $detalles['0']['Sucursale']['direccion_sucursal'] ?></b><br>
    <b>Tel&eacute;fono:</b> <?php echo  $detalles['0']['Sucursale']['telefono_sucursal'] ?> - <br>
    <b>Contacto:</b> <?php echo  $detalles['0']['Sucursale']['nombre_contacto'] . ' - ' . $detalles['0']['Sucursale']['telefono_contacto'] ?><br>
</div>
<?php } ?>