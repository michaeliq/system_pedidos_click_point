<?php

ini_set('max_execution_time','300');
    ini_set('memory_limit', '912M'); 
    
    // Crear archivo plano csv
    $file_name_cabecera = 'informes/InformeCabeceraSAP.csv';
    $file_name_detalle = 'informes/InformeDetalleSAP.csv';
    
    $file_cabecera = fopen($file_name_cabecera, 'w');
    $file_detalle = fopen($file_name_detalle, 'w');
    
    echo $this->Html->script(array('informes/informes_sap')); 
?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME SAP</h2>
<?php echo $this->Form->create('PedidosDetalle', array('url' => array('controller' => 'informes', 'action' => 'info_sap'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa:</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Regional:</td>
            <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select',  'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Sucursal:</td>
            <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Fecha Inicio: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_inicio', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
            <td>Fecha Corte: *<br><span style="font-size: x-small; color:red;">Aprobado</span></td>
            <td><?php echo $this->Form->input('pedido_fecha_corte', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        </tr>
        <tr>
            <td>Estado Orden: </td>                
            <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
            <td>Tipo Pedido:</td>
            <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipoPedido, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>        
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name_cabecera; ?>"> <i class="icon-download"></i> Descargar Cabecera</a></div>
<div class="text-center"><a href="../<?php echo $file_name_detalle; ?>"> <i class="icon-download"></i> Descargar Detalle</a></div>
<?php

if (count($cabeceras) > 0) {
    $docnum = 1;
    $docentry = 0;
    
    // Escribir encabezado del arhivo
    $data_csv = utf8_decode("DocNum;DocEntry;DocType;DocDate;DocDueDate;CardCode;NumAtCard;DocCurrency;DocTotal;Comments;SalesPersonCode;Series;TaxDate;ControlAccount;ShipToCode\n"); // DeferredTax;
    fwrite($file_cabecera, $data_csv);

    $data_csv_d = utf8_decode("ParentKey;LineNum;ItemCode;Quantity;Price;Currency;WarehouseCode;SalesPersonCode;AccountCode;TaxCode;TaxType;TaxLiable;WTliable;LineTotal;TransactionType\n");
    fwrite($file_detalle, $data_csv_d);
    foreach ($cabeceras as $cabecera) :
        $total = 0;
        $linenum = 0;
        
        foreach ($detalles as $detalle) :
            if($cabecera['VPedidoCabeceraSap']['pedido_id'] == $detalle['VPedidoDetallesSap']['pedido_id']){
                $data_csv_d = utf8_decode($docnum) . ';' . // $cabecera['VPedidoCabeceraSap']['docnum']
                    utf8_decode($linenum) . ';' . // $cabecera['VPedidoCabeceraSap']['docentry']
                    utf8_decode($detalle['VPedidoDetallesSap']['itemcode']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['quantity']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['price']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['currency']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['whscode']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['slpcode']) . ';' . //utf8_decode($cabecera['VPedidoCabeceraSap']['salespersoncode']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['acctcode']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['taxcode']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['taxtype']) . ';' .
                    // utf8_decode(date('Y-m-d')) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['taxliable']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['wtliable']) . ';' .
                    // utf8_decode($detalle['VPedidoDetallesSap']['deferrtax']) . ';' . // DeferredTax;
                    utf8_decode($detalle['VPedidoDetallesSap']['linetotal']) . ';' .
                    utf8_decode($detalle['VPedidoDetallesSap']['trantype']);
                fwrite($file_detalle, $data_csv_d);
                fwrite($file_detalle, chr(13) . chr(10));
                $linenum++;
                // $total = $total+($detalle['VPedidoDetallesSap']['price']*$detalle['VPedidoDetallesSap']['quantity']);
            }
        endforeach;

        $data_csv = utf8_decode($docnum) . ';' . // $cabecera['VPedidoCabeceraSap']['docnum']
            utf8_decode($docentry) . ';' . // $cabecera['VPedidoCabeceraSap']['docentry']
            utf8_decode($cabecera['VPedidoCabeceraSap']['doctype']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['docdate']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['docduedate']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['cardcode']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['pedido_id']) . ';' . // numatcard
            utf8_decode($cabecera['VPedidoCabeceraSap']['doccurrency']) . ';' .
            ';' .//utf8_decode($cabecera['VPedidoCabeceraSap']['doctotal']) . ';' .
            // utf8_decode($cabecera['VPedidoCabeceraSap']['comments']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['salespersoncode']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['series']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['taxdate']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['deferredtax']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['controlaccount']) . ';' .
            utf8_decode($cabecera['VPedidoCabeceraSap']['shiptocode']);
        fwrite($file_cabecera, $data_csv);
        fwrite($file_cabecera, chr(13) . chr(10));
        $docnum++;
        $docentry++;
    endforeach;
    fclose($file_cabecera);
    fclose($file_detalle);
}
?>