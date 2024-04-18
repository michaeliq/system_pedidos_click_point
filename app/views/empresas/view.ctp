<?php
echo $this->Html->script(array('empresas/empresas_edit'));
// print_r($empresa);
/*
 SELECT sucursales.id, json_agg(t) 
 FROM sucursales, sucursales_presupuestos_pedidos AS t
 WHERE sucursales.id = t.sucursal_id
 AND sucursales.id= 5661
 GROUP BY sucursales.id
 *  */
?>
<h2>Ver Empresa <?php echo $empresa['Empresa']['nombre_empresa']; ?></h2>
<table class="table table-striped table-bordered table-condensed" align="center">
    <tr>
        <td><b>Contrato Empresa:</b></td>
        <td><b><?php echo $empresa['Empresa']['contrato_empresa']; ?></b></td>
        <td>Sector:</td>
        <td><?php echo $empresa['Sectore']['nombre_sector']; ?></td>
    </tr>
    <tr>
        <td>CECO:</td>
        <td><?php echo $empresa['Empresa']['ceco_empresa']; ?></td>
        <td># Auxiliares:</td>
        <td><?php echo $empresa['Empresa']['auxiliares_empresa']; ?></td>
    </tr>
    <tr>
        <td>Nit. Empresa:</td>
        <td><?php echo $empresa['Empresa']['nit_empresa']; ?></td>
        <td>Empresa:</td>
        <td><?php echo $empresa['Empresa']['nombre_empresa']; ?></td>
    </tr>
    <tr>
        <td>Departamento:</td>
        <td><?php echo $empresa['Departamento']['nombre_departamento']; ?></td>    
        <td>Municipio:</td>
        <td><?php echo $empresa['Municipio']['nombre_municipio']; ?></td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Empresa:</td>
        <td><?php echo $empresa['Empresa']['direccion_empresa']; ?></td>
        <td>Tel. Empresa:</td>
        <td><?php echo $empresa['Empresa']['telefono_empresa']; ?></td>
    </tr>
    <tr>
        <td>E-mail Empresa:</td>
        <td><?php echo $empresa['Empresa']['email_empresa']; ?></td>
        <td>Asesor Encargado:</td>
        <td title="<?php echo $empresa['User']['email_persona']; ?>"><?php echo $empresa['User']['nombres_persona']; ?></td>
    </tr>
    <tr>
        <td>Vendedor Encargado:</td>
        <td title="<?php echo $empresa['Vendedore']['correo_vendedor']; ?>"><?php echo $empresa['Vendedore']['nombre_vendedor']; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center"><b>Parametro tiempo m&aacute;ximo en pedidos: *</b> <br><span style="font-size: x-small;">Pedidos en Pendiente Aprobaci&oacute;n</span></td>
        <td colspan="2"><?php echo $empresa['Empresa']['parametro_tiempo_pedido']; ?> D&iacute;as</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center"><b>Parametro tiempo m&aacute;ximo en pedidos: *</b> <br><span style="font-size: x-small;">Pedidos en Pendiente Aprobaci&oacute;n</span></td>
        <td colspan="2"><?php echo $empresa['Empresa']['parametro_tiempo_pedido']; ?> D&iacute;as</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center"><b>Parametro IVA en Pesupuesto: *</b> <br><span style="font-size: x-small;">Calculo de IVA en el presupuesto</span></td>
        <td colspan="2"><?php if($empresa['Empresa']['parametro_presupuesto_iva']){ echo 'Si'; }else{ echo "No"; } ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center"><b>Parametro Encuesta en pedidos: *</b> <br><span style="font-size: x-small;">Diligenciar encuesta antes de pedido</span></td>
        <td colspan="2"><?php if($empresa['Empresa']['parametro_encuesta']){ echo 'Si'; }else{ echo "No"; } ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center"><b>Parametro Cronograma: *</b> <br><span style="font-size: x-small;">Debe tener cronograma para crear pedidos</span></td>
        <td colspan="2"><?php if($empresa['Empresa']['parametro_cronograma']){ echo 'Si'; }else{ echo "No"; } ?></td>
    </tr>
    <tr>
        <td colspan="4"><h2>Datos Contacto</h2></td>
    </tr>
    <tr>
        <td>Nombre Contacto:</td>
        <td><?php echo $empresa['Empresa']['nombre_contacto']; ?></td>
        <td>Tel. Contacto:</td>
        <td><?php echo $empresa['Empresa']['telefono_contacto']; ?></td>
    </tr>
    <tr>
        <td>E-mail Contacto:</td>
        <td><?php echo $empresa['Empresa']['email_contacto']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>

<h2>Sucursales</h2>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>OI</th>
        <th>CECO</th>
        <th>Regional</th>
        <th>Sucursal</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Contacto</th>
        <th>Auxiliares</th>

        <th>Presupuesto</th>
        <th>Estado</th>
    </tr> <?php
$i = 0;
$presupuesto_acp = 0;
// print_r($presupuestos);
foreach ($sucursals as $sucursal):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    foreach ($presupuestos as $presupuesto) {
    if($sucursal['Sucursale']['id'] == $presupuesto['0']['id']){
        
          $json = json_decode($presupuesto['0']['json_agg']);
          $array = json_decode(json_encode($json), true);
          foreach ($array as $value) {
             if($value['tipo_pedido_id']=='1'){
                 $presupuesto_acp =  '$ '.number_format($value['presupuesto_asignado'],0,",",".");
             }
   
}
    }
}

    ?>
    <tr<?php echo $class; ?>>
        <td><?php echo $sucursal['Sucursale']['oi_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['ceco_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['regional_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['nombre_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['direccion_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['telefono_sucursal']; ?></td>
        <td><?php echo $sucursal['Sucursale']['nombre_contacto']; ?><br><?php echo $sucursal['Sucursale']['telefono_contacto']; ?><br><?php echo $sucursal['Sucursale']['email_contacto']; ?></td>
        <td><?php echo $sucursal['Sucursale']['numero_auxiliares']; ?></td>
        <td>Aseo-Cafeteria-Papeleria: <br><?php echo $presupuesto_acp; ?>   </td>
        <td>
                <?php
                if ($sucursal['Sucursale']['estado_sucursal']) {
                    echo $html->image('verde.png');
                    // echo " Activo";
                } else {
                    echo $html->image('rojo.png');
                    // echo " Inactivo";
                }
                ?>
        </td>
    </tr>    
    <?php endforeach; ?>
    <tr>
        <td colspan="11" class="text-center" >
            <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_view', 'class' => 'btn btn-warning')); ?>
        </td>
    </tr>

</table>