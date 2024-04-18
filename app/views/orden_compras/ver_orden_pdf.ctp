<?php

App::import('Vendor', 'xtcpdf');

ob_end_clean();
//$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTML($headerData['string'], true, false, true, false, '');
    }
    
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, $this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '#000' . $detalles['0']['Pedido']['id'] . '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('dejavusans', '', 9);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(true);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 9);

// set header
$pdf->setHeaderData($ln='', $lw=2, $ht='', $hs='<span align="center">ORDEN DE COMPRA N°: #000' . $ordenes['0']['OrdenCompra']['id'] . '</span>', $tc=array(0,0,0), $lc=array(0,0,0));

// add a page
$pdf->AddPage();

// Print a text
$pdf->Image(K_PATH_IMAGES.'cise_logo.png', 10, 11, 27, '', '', '', '', false, 300);
$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000;" align="center"><h3>ORDEN DE COMPRA DE INSUMOS / SERVICIOS</h3><br>GESTIÓN DE COMPRAS</td>
    </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

//  print_r($ordenes); 
$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td colspan="4" style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; background-color:#C0C0C0;" align="center"><b>INFORMACION GENERAL</b></td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>ORDEN DE COMPRA N°:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;" colspan="3"><span style="color:red"><b>#000'.$ordenes['0']['OrdenCompra']['id'].'</b></span></td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>Proveedor:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['Proveedore']['nombre_proveedor'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>Fecha Solicitud:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; ">'.$ordenes['0']['OrdenCompra']['fecha_orden_compra'].'</td>
    </tr>  
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>Nit:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['Proveedore']['nit_proveedor'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>% ReteFuente:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['OrdenCompra']['rte_fuente'].' %</td>
    </tr>   
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>Contacto:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['Proveedore']['persona_contacto'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>% ReteICA:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['OrdenCompra']['rte_ica'].' %</td>
    </tr> 
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>Telefono:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['Proveedore']['telefono_proveedor'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>Tipo de Orden:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['TipoOrden']['nombre_tipo_orden'].'</td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dirección:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000;">'.$ordenes['0']['Proveedore']['direccion_proveedor'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000;" colspan="2"></td>
    </tr>
    <tr>
        <td colspan="4" style="border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="border-right: 1px solid #000000; border-left: 1px solid #000000; background-color:#C0C0C0;" align="center"><b>INFORMACION DE ENTREGA</b></td>
    </tr>
    <tr>
        <td style="border-left: 1px solid #000000;border-top: 1px solid #000000; border-right: 1px solid #000000;" ><b>Nombre:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['Empresa']['nombre_empresa'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>Teléfono:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;">8966099</td>
    </tr>
    <tr>
        <td style="border-left: 1px solid #000000;border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>Dirección:</b></td>
        <td colspan="3" style="border-top: 1px solid #000000; border-right: 1px solid #000000;">KM 1.2 Via Siberia, entrada al parque La Florida. Parque Industrial Terrapuerto, Bodega 32 Bogota</td>
    </tr>

    <tr>
        <td style="border-left: 1px solid #000000;border-top: 1px solid #000000; border-right: 1px solid #000000;  border-bottom: 1px solid #000000;"><b>Forma de Pago:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;  border-bottom: 1px solid #000000;">'.$ordenes['0']['TipoFormasPago']['nombre_forma_pago'].'</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Contacto:</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000;">'.$ordenes['0']['Empresa']['nombre_contacto'].'</td>
    </tr>
    <tr>
        <td colspan="4" style="border-left: 1px solid #000000; border-right: 1px solid #000000;  border-bottom: 1px solid #000000;" align="center">ADJUNTAR  A LA FACTURA COPIA DE LA ORDEN DE COMPRA  Y REMISION DE ENTREGA, LAS FACTURAS  SE DEBEN RADICAR  EN  LAS OFICINAS  DE  TERRAPUERTO BODEGA 32</td>
     </tr>   
</table>';
// <td colspan="2" style="border-top: 1px solid #000000; border-right: 1px solid #000000;">'.$ordenes['0']['OrdenCompra']['observaciones'].'</td>
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" width="50" align="center"><b>Codigo</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="267" align="center"><b>Descripcion</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="30" align="center"><b>Und</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="33" align="center"><b>Can</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="70" align="center"><b>Precio</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="40" align="center"><b>IVA</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="70" align="center"><b>Valor IVA</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; " width="70" align="center"><b>Valor Producto</b></td>
        <td style="background-color:#C0C0C0;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="86" align="center"><b>Total</b></td>        
    </tr>';
    $sub_total_final = 0;
    $precio_iva = 0;
    $total_final = 0;
    if (count($detalles) > 0) {
        foreach ($detalles as $detalle) :
            $sub_total_final = $sub_total_final + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['cantidad_orden']);
            $precio_iva = $precio_iva + (($detalle['OrdenComprasDetalle']['precio_producto']*$detalle['OrdenComprasDetalle']['iva_producto']) * $detalle['OrdenComprasDetalle']['cantidad_orden'] );
            $total_final = $total_final + ($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])) * $detalle['OrdenComprasDetalle']['cantidad_orden'];
$html.= '
    <tr>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">'.$detalle['Producto']['codigo_producto'].'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;">'.$detalle['Producto']['nombre_producto'].'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">'.$detalle['Producto']['medida_producto'].'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">'.$detalle['OrdenComprasDetalle']['cantidad_orden'].'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right">$ '.number_format($detalle['OrdenComprasDetalle']['precio_producto'], 0, ',', '.').'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right">'.($detalle['OrdenComprasDetalle']['iva_producto']*100).' %</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right">$ '.number_format(($detalle['OrdenComprasDetalle']['precio_producto'] * ($detalle['OrdenComprasDetalle']['iva_producto'])), 0, ',', '.').'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right">$ '.number_format(($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])), 0, ',', '.').'</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right">$ '.number_format(($detalle['OrdenComprasDetalle']['precio_producto'] + ($detalle['OrdenComprasDetalle']['precio_producto'] * $detalle['OrdenComprasDetalle']['iva_producto'])) * $detalle['OrdenComprasDetalle']['cantidad_orden'], 0, ',', '.').'</td>
    </tr>';
        endforeach;
        $rtefuente = $sub_total_final*($ordenes['0']['OrdenCompra']['rte_fuente']);
        $rteica = ($sub_total_final*$ordenes['0']['OrdenCompra']['rte_ica'])/1000;
    }
$html.= '
    <tr>
        <td colspan="8" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b> SUBTOTAL</b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b>$ '.number_format($sub_total_final, 0, ',', '.').'</b></td>
    </tr>
    <tr>
        <td colspan="6" style="background-color:#C0C0C0;border-left: 1px solid #000000; border-bottom: 1px solid #000000;" align="center"><b>OBSERVACIONES </b></td>
        <td colspan="2" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b> IVA</b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b>$ '.number_format($precio_iva, 0, ',', '.').'</b></td>
    </tr>
    <tr>
        <td colspan="6" style="border-left: 1px solid #000000;" align="center">'.$ordenes['0']['OrdenCompra']['observaciones'].'</td>
        <td colspan="2" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b> RETEFUENTE </b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b>$ '.number_format($rtefuente, 0, ',', '.').'</b></td>
    </tr>
    <tr>
        <td colspan="6" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000;" align="center"></td>
        <td colspan="2" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b> RETEICA </b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b>$ '.number_format($rteica, 0, ',', '.').'</b></td>
    </tr>
    <tr>
        <td colspan="8" style="border-left: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b> TOTAL </b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="right"><b>$ '.number_format($sub_total_final+$precio_iva-$rtefuente-$rteica, 0, ',', '.').'</b></td>
    </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');
 

$html= '<br><br><table cellspacing="1" cellpadding="1">
        <tr>
            <td><b>ELABORADO POR</b></td>
            <td><b>APROBADO POR</b></td>
        </tr>
        <tr>
            <td><b>'. strtoupper($ordenes['0']['User']['nombres_persona'])  .'</b></td>
            <td><b>'. strtoupper($ordenes['0']['User2']['nombres_persona'])  .'</b></td>
        </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');					
				
					
//Close and output PDF document
$pdf->Output("pedidos/oc_000".$ordenes['0']['OrdenCompra']['id'].".pdf", 'FI');

//============================================================+
// END OF FILE
//============================================================+