<?php

App::import('Vendor', 'xtcpdf');
// 2021-07-28
$meses = array('0'=>'INSTALACION','1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');

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
$pdf->setHeaderData($ln='', $lw=2, $ht='', $hs='<span align="center">N°. Pedido: #000' . $detalles['0']['Pedido']['id'] . '</span>', $tc=array(0,0,0), $lc=array(0,0,0));

// add a page
$pdf->AddPage();

//$cantidad_final = 0;
//foreach ($detalles as $detalle) :
//    $cantidad_final = $cantidad_final + $detalle['PedidosDetalle']['cantidad_pedido'];
//endforeach;

// Print a text
$pdf->Image(K_PATH_IMAGES.'logo_consorcio_kapital.jpg', 10, 11, 13, '', '', '', '', false, 300);
$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000;" align="center"><h3>ORDEN DE ALISTAMIENTO</h3><br>GESTION DE LOGISTICA</td>
    </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; background-color:#C0C0C0;" align="center"><b>REMITENTE</b></td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; background-color:#C0C0C0;" align="center"><b>DESTINATARIO</b></td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>DE:</b> CONSORCIO KAPITAL
        </td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>CLIENTE:</b> ' . $detalles['0']['Sucursale']['regional_sucursal'] . '</td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>DIRECCION:</b> Calle 77 28 A 48</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>SEDE:</b> ' . $detalles['0']['Sucursale']['nombre_sucursal'] . ' | <b>CIUDAD:</b> '.$detalles['0']['Municipio']['nombre_municipio'].'</td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>TELEFONO:</b> (601) 6068433 | <b>FECHA:</b> ' . $detalles['0']['Pedido']['fecha_aprobado_pedido']  . '</td>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;"><b>DIRECCION:</b> ' . $detalles['0']['Sucursale']['direccion_sucursal'] . ' | <b>DEPARTAMENTO:</b> ' . $detalles['0']['Departamento']['nombre_departamento'] . '</td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>RESPONSABLE DE DESPACHO:</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;"><b>TEL:</b> '.$detalles['0']['Sucursale']['telefono_sucursal'].' | <b># GUIA:</b> '.$detalles['0']['Pedido']['guia_despacho'].'</td>
    </tr>
    <tr>
        <td style=" border-bottom: 1px solid #000000; border-left: 1px solid #000000;"><b>Este  pedido es despachado por CONSORCIO KAPITAL
        <br>Nit 800094755-7 | Calle 77 28 A 48 Bogota - Tel: (601) 6068433.</b></td>
        <td style=" border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><span style="color:red"><b>N°. Pedido: #000' . $detalles['0']['Pedido']['id'] . '</b></span><br><b>ORDEN INTERNA: '.$detalles['0']['Sucursale']['oi_sucursal'].'</b><br><b>TIPO DE PEDIDO:</b> ' . $detalles['0']['TipoPedido']['nombre_tipo_pedido'] . '<br><span style="font-size: 29px;"><b>FECHA ENTREGA:</b> Desde el <b>'.$detalles[0]['Pedido']['fecha_entrega_1'].'</b> hasta el <b>'.$detalles[0]['Pedido']['fecha_entrega_2'].'</b></span><br><span style="font-size: 29px;"><b>MES PEDIDO:</b> '.$meses[$detalles[0]['Pedido']['mes_pedido']].'</span><br><span style="font-size: 29px;"><b>CLASIFICACION:</b> '.$detalles[0]['Pedido']['clasificacion_pedido'].'</span></td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table cellspacing="1" cellpadding="1">
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;  border-left: 1px solid #000000; background-color:#C0C0C0;"  align="center" width="734"><b>DETALLE DEL PEDIDO</b></td>
    </tr>
    <tr style="background-color:#C0C0C0;">
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" width="75" align="center"><b>CODIGO</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="350" align="center"><b>DESCRIPCION</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="70" align="center"><b>UNIDAD</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="70" align="center"><b>CANTIDAD</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="165" align="center"><b>OBSERVACION</b></td>        
    </tr>';
$i = 1;
$total_final = 0;
$cantidad_final = 0;
foreach ($detalles as $detalle) :
    $total_sin_iva = $total_sin_iva + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['cantidad_pedido']);
    $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];
    $cantidad_final = $cantidad_final + $detalle['PedidosDetalle']['cantidad_pedido'];
            
    $codigo = $detalle['Producto']['codigo_producto'];
    $nombre = $detalle['Producto']['nombre_producto'].' '.$detalle['Producto']['marca_producto'];
    $unidad = $detalle['Producto']['medida_producto'];
    $cantidad = $detalle['PedidosDetalle']['cantidad_pedido'];
    $observacion_producto = $detalle['PedidosDetalle']['observacion_producto'];

    $html.='
    <tr>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center">' . $codigo . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="left">' . $nombre . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $unidad . '</td>     
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $cantidad . '</td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $observacion_producto . '</td>             
    </tr>';
    $i++;
endforeach;

$html.='<tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="right" colspan="3"><b>Cantidad de Productos:</b></td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center" colspan="1" align="center"><b>'.$cantidad_final.'</b></td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center" colspan="1" align="center"><b>Items:</b> '.($i-1).'</td>
        </tr>';

if($i > 30){
    $html.='
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>';
}

    foreach ($detalles as $detalle) :
        if(!empty($detalle['PedidosDetalle']['observacion_producto'])){
            $observaciones.= $detalle['Producto']['codigo_producto'].' - '.$detalle['PedidosDetalle']['observacion_producto'].'<br>';
        }
    endforeach;


$html.='
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; background-color:#C0C0C0;" colspan="5"  align="center"><b>OBSERVACIONES</b></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" colspan="5">'.$detalles['0']['Pedido']['observaciones'].' '.$observaciones.'</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>ALISTADO POR</b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>ENTREGÓ:</b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>RECIBIÓ (NOMBRE LEGIBLE):</b></td>
        </tr>
        <tr>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style=" border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style=" border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>REVISADO POR</b></td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style=" border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>CC</b></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>EMPACADO POR</b></td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>NOMBRE:</b></td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>FECHA Y HORA</b></td>
        </tr>
         <tr>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style=" border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
            <td style="color: #C0C0C0;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;[ DD ] [ MM ] [ AAAA ] - [ HH ]:[ MM ]</td>
        </tr>
    </table>';
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output("pedidos/op_000".$detalles['0']['Pedido']['id'].".pdf", 'FI');

//============================================================+
// END OF FILE
//============================================================+