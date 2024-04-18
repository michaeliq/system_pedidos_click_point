<?php
/*
App::import('Vendor', 'xtcpdf');

ob_end_clean();
// create new PDF document
/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Full page background
 * @author Nicola Asuni
 * @since 2009-04-16
 */
/*

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        
        //$img_file = K_PATH_IMAGES.'membrete_kopan.png';
        //$this->Image($img_file, 0, 0, 216, 282, '', '', '', false, 300, '', false, false, 0);
        
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'KOPANCOBA DELIVERY SAS - Nit 900.751.920-8', 'Calle 13 No. 28-51 - Tel: 3512515 - 3603134 | #000' . $detalles['0']['Cotizacion']['id'] . '');
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 051');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'KOPANCOBA DELIVERY SAS - Nit 900.751.920-8', 'Calle 13 No. 28-51 - Tel: 3512515 - 3603134 | #000' . $detalles['0']['Cotizacion']['id'] . '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

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
*/


App::import('Vendor', 'xtcpdf');

ob_end_clean();
// create new PDF document
// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'KOPANCOBA DELIVERY SAS - Nit 900.751.920-8', 'Calle 20 C No. 44 - 70 - Tel: 7498501 - 7046213  | #000' . $detalles['0']['Cotizacion']['id'] . '');

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

// add a page
$pdf->AddPage();


$txt = "
<h1>Cotización #000" . $detalles['0']['Cotizacion']['id'] . "</h1>
<table>
    <tr>
        <td><h4>EMPRESA:</h4>
            <div>
                <b>Empresa:</b> " . $llamada['0']['BdCliente']['bd_razon_social'] . " - <b>Nit:</b> " . $llamada['0']['BdCliente']['bd_identificacion'] . "<br>
                <b>Tel&eacute;fono:</b> " . $llamada['0']['BdCliente']['bd_telefonos'] . " - <b>Direcci&oacute;n:</b> " . $llamada['0']['BdCliente']['bd_direccion'] . " <br>
                <b>E-mail:</b> " . $detalles['0']['Cotizacion']['cotizacion_email'] . "
            </div>
        </td>
    </tr>
</table>
<div>&nbsp;</div>
<div><b>Fecha Cotización:</b> " . $detalles['0']['Cotizacion']['fecha_cotizacion']. "</div>
<div><b>Observaciones:</b> " . $detalles['0']['Cotizacion']['observaciones'] . "</div><br>
";


$tbl = <<<EOD
    $txt
EOD;

$pdf->writeHTML($tbl, true, false, true, false, '');

// <table border="1" cellspacing="1" cellpadding="1">
$html = '<h4>Detalle de Cotizacion</h4>
<table cellspacing="1" cellpadding="1" width="1200">
    <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" width="350"><b>Descripci&oacute;n</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="70" align="center"><b>Categor&iacute;a</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="70" align="center"><b>Cantidad</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="100" align="center"><b>Valor Producto</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="40" align="center"><b>IVA</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="100" align="center"><b>Total sin IVA</b></td>
    </tr>';
$total_final = 0;
foreach ($detalles as $detalle) :
    $total_sin_iva = $total_sin_iva + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['cantidad_pedido']);
    $total_final = $total_final + ($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])) * $detalle['CotizacionDetalle']['cantidad_pedido'];

    $nombre = /*$detalle['Producto']['codigo_producto'] . " - " . */$detalle['Producto']['nombre_producto'];
    $categoria = $detalle['TipoCategoria']['tipo_categoria_descripcion'];
    $cantidad = number_format($detalle['CotizacionDetalle']['cantidad_pedido'],0,",",".");
    $precio = number_format($detalle['CotizacionDetalle']['precio_producto'], 0, ',', '.');
    $iva = ($detalle['CotizacionDetalle']['iva_producto']*100);
    $precio_iva = number_format(($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])), 2, ',', '.');
    // $total = number_format(($detalle['CotizacionDetalle']['precio_producto'] + ($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['iva_producto'])) * $detalle['CotizacionDetalle']['cantidad_pedido'], 2, ',', '.');
    $total = number_format($detalle['CotizacionDetalle']['precio_producto'] * $detalle['CotizacionDetalle']['cantidad_pedido'], 2, ',', '.');

    $html.='
    <tr>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">' . $nombre . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $categoria . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $cantidad . '</td>     
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">$' . $precio . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $iva . '%</td>             
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">$' . $total . '</td>
    </tr>';
endforeach;
$html.='<tr><td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"colspan="5">TOTAL SIN IVA</td>'
        . '<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">$'.number_format($total_sin_iva, 2, ',', '.').'</td></tr>'
        . '<tr><td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"colspan="5">IVA</td>'
        . '<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">$'.number_format($total_final - $total_sin_iva, 2, ',', '.').'</td></tr>'
        . '<tr><td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"colspan="5">TOTAL</td>'
        . '<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">$'.number_format($total_final, 2, ',', '.').'</td></tr></table>';

$html.='<div>&nbsp;</div>
        <div>&nbsp;</div>
        <table>
            <tr>
                <td><p>Realizada Por:  '.$llamada['0']['User']['nombres_persona']  .'</p></td>
            </tr>
        </table>
        <div style="font-size:25px;">Notas:<br>
        - Los montos incluyen IVA.<br>
        - La cotización es v&aacute;lida por 30 d&iacute;as naturales. No tendr&aacute; efecto despu&eacute;s de este periodo.<br>
        
        </div>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// $html = '<div>Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</div>';
// $pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("cotizacion/ct_000" . $detalles['0']['Cotizacion']['id'] . ".pdf", 'FI');


?>
