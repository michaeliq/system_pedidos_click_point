<?php

ini_set('memory_limit', '512M'); ?>
<?php
if(!empty($id)){
    $movimiento_id = $id;
}else{
    $movimiento_id = null;
}
    // Crear archivo plano csv
    $file_name = 'informes/InformeFacturasCompra_000'.$movimiento_id.'.csv';
    $file = fopen($file_name, 'w');
?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME FACTURAS DE COMPRA</h2>
<?php echo $this->Form->create('MovimientosEntrada', array('url' => array('controller' => 'informes', 'action' => 'info_facturas_compra'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Movimiento de Entrada:</td>
            <td><?php echo $this->Form->input('id', array('type' => 'select', 'options' => $movimientos, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
         <tr>
            <td>Desde el movimiento de Entrada:</td>
            <td><?php echo $this->Form->input('desde_id', array('type' => 'select', 'options' => $movimientos, 'empty' => array(''=>'Desde el Movimiento #'), 'label' => false)); ?></td>
        </tr>
         <tr>
            <td>Hasta el movimiento de Entrada:</td>
            <td><?php echo $this->Form->input('hasta_id', array('type' => 'select', 'options' => $movimientos, 'empty' => array(''=>'Hasta el Movimiento #'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Numero Documento:</td>
            <td><?php echo $this->Form->input('movimiento_numero_documento', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Compro:</td>
            <td><?php echo $this->Form->input('movimiento_compro', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Documento Externo:</td>
            <td><?php echo $this->Form->input('movimiento_doc_externo', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Nota Concepto:</td>
            <td><?php echo $this->Form->input('movimiento_nota_concepto', array('type' => 'text', 'size' => '45', 'maxlength' => '60', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Forma de Pago:</td>
            <td><?php $forma_pago =array('Credito'=>'Credito','Contado'=>'Contado'); echo $this->Form->input('movimiento_forma_pago', array('type' => 'select', 'options' => $forma_pago, 'label' => false)); ?></td>    
        </tr>

    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<table class="table table-hover">
    <tr>						
        <th>Mov. Ent.</th>
        <th>Empresa</th>
        <th>Tipo_Doc</th>
        <th>Prefijo</th>
        <th>No_Doc</th>
        <th>Fecha</th>
        <th>Beneficiario</th>
        <th>Compro</th>
        <th>Concepto</th>
        <th>Doc_Externo</th>
        <th>Forma_Pago</th>
        <th>Verificado</th>
        <th>Anulado</th>
        <th>Producto</th>
        <th>Bodega</th>
        <th>Unidad_Medida</th>
        <th>Cantidad</th>
        <th>Valor_Unitario</th>
        <th>Iva</th>
        <th>Centro_Costos</th>
        <th>Vencimiento</th>
        <th>Tercero</th>
        <th>Descuento</th>
        <th>Factor_Conversion_Cantidad</th>
        <th>Factor_Conversion</th>
    </tr>
    <?php
    $total_final = 0;
    if (count($detalles) > 0) {
       
    // Escribir encabezado del arhivo
    $data_csv = utf8_decode("Empresa;Tipo_Doc;Prefijo;No_Doc;Fecha;Beneficiario;Compro;Concepto;Doc_Externo;Forma_Pago;Verificado;Anulado;Producto;Bodega;Unidad_Medida;Cantidad;Valor_Unitario;Iva;Centro_Costos;Vencimiento;Tercero;Descuento;Factor_Conversion_Cantidad;Factor_Conversion;MovEntrada;\n");
    fwrite($file, $data_csv);
    foreach ($detalles as $detalle) :
    ?>
    <tr>
        <td><?php echo $detalle['VFacturaCompra']['movimientos_entradas_id']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['empresa']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['tipo_doc']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['prefijo']; ?></td>
        <td><?php echo $this->data['MovimientosEntrada']['movimiento_numero_documento']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['fecha']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['beneficiario']; ?></td>
        <td><?php echo $this->data['MovimientosEntrada']['movimiento_compro']; ?></td>
        <td><?php echo $this->data['MovimientosEntrada']['movimiento_nota_concepto']; ?></td>
        <td><?php echo $this->data['MovimientosEntrada']['movimiento_doc_externo']; // echo $detalle['VFacturaCompra']['bloq_act']; ?></td>        
        <td><?php echo $this->data['MovimientosEntrada']['movimiento_forma_pago']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['verificado']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['anulado']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['producto']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['bodega']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['unidad_medida']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['cantidad']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['valor_unitario']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['iva']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['centro_costos']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['vencimiento']; ?></td>
        <td><?php echo $detalle['VFacturaCompra']['beneficiario']; // Tercero ?></td> 
        <td><?php echo $detalle['VFacturaCompra']['descuento']; ?></td>        
        <td>1<?php // echo $detalle['VFacturaCompra']['factor_conversion_cantidad']; ?></td>
        <td>1<?php // echo $detalle['VFacturaCompra']['factor_conversion']; ?></td>
    </tr> 
        <?php
        // number_format($total_final,2,",",".")
            $data_csv =  utf8_decode($detalle['VFacturaCompra']['empresa']). ';' .
                utf8_decode($detalle['VFacturaCompra']['tipo_doc']). ';' .
                utf8_decode($detalle['VFacturaCompra']['prefijo']). ';' .
                utf8_decode($this->data['MovimientosEntrada']['movimiento_numero_documento']). ';' .
                utf8_decode($detalle['VFacturaCompra']['fecha']). ';' .
                utf8_decode($detalle['VFacturaCompra']['beneficiario']). ';' .
                utf8_decode($this->data['MovimientosEntrada']['movimiento_compro']). ';' .
                utf8_decode($this->data['MovimientosEntrada']['movimiento_nota_concepto']). ';' .
                utf8_decode($this->data['MovimientosEntrada']['movimiento_doc_externo']). ';' .
                // utf8_decode($detalle['VFacturaCompra']['bloq_act']). ';' .        
                utf8_decode($this->data['MovimientosEntrada']['movimiento_forma_pago']). ';' .
                utf8_decode($detalle['VFacturaCompra']['verificado']). ';' .
                utf8_decode($detalle['VFacturaCompra']['anulado']). ';' .
                utf8_decode($detalle['VFacturaCompra']['producto']). ';' .
                utf8_decode($detalle['VFacturaCompra']['bodega']). ';' .
                utf8_decode($detalle['VFacturaCompra']['unidad_medida']). ';' .
                utf8_decode($detalle['VFacturaCompra']['cantidad']). ';' .
                utf8_decode($detalle['VFacturaCompra']['valor_unitario']). ';' .
                utf8_decode($detalle['VFacturaCompra']['iva']). ';' .
                utf8_decode($detalle['VFacturaCompra']['centro_costos']). ';' .
                utf8_decode($detalle['VFacturaCompra']['vencimiento']). ';' .
                utf8_decode($detalle['VFacturaCompra']['beneficiario']). ';' . // Tercero
                utf8_decode($detalle['VFacturaCompra']['descuento']). ';' .                
                    '1;'. // factor_conversion_cantidad
                    '1;'. // factor_conversion
               utf8_decode($detalle['VFacturaCompra']['movimientos_entradas_id']);
                // utf8_decode($detalle['VFacturaCompra']['factor_conversion_cantidad']). ';' .
//                utf8_decode($detalle['VFacturaCompra']['factor_conversion']);
            fwrite($file, $data_csv);
            fwrite($file, chr(13) . chr(10));
            endforeach;
            fclose($file);
        }
        ?>
</table>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>

