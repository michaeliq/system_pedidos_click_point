<?php

App::import('Vendor', 'xtcpdf');

ob_end_clean();
// create new PDF document
// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'CISE LOGISTICS S.A.S - Nit 900.415.585-3', 'KM 1.2 Via Siberia, entrada al parque La Florida. Parque Industrial Terrapuerto, Bodega 32 Bogota - Tel: 6068433-4849120 | #000' . $detalles['0']['Pedido']['id'] . '');

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
<h1>Orden de pedido #000" . $detalles['0']['Pedido']['id'] . "</h1>
<table>
    <tr>
        <td><h4>EMPRESA:</h4>
            <div>
                <b>Empresa:</b> " . $detalles['0']['Empresa']['nombre_empresa'] . "<br>
                <b>Nit:</b> " . $detalles['0']['Empresa']['nit_empresa'] . "<br>
                <b>Tel&eacute;fono:</b> " . $detalles['0']['Empresa']['telefono_empresa'] . "<br>
                <b>Direcci&oacute;n:</b> " . $detalles['0']['Empresa']['direccion_empresa'] . "
            </div>
        </td>
        <td><h4>SUCURSAL:</h4>
            <div>
                <b>Sucursal: " . $detalles['0']['Sucursale']['nombre_sucursal'] . "</b><br>
                <b>Regional: " . $detalles['0']['Sucursale']['regional_sucursal'] . "</b><br>
                <b>Ciudad: " . $detalles['0']['Departamento']['nombre_departamento'] . " - " . $detalles['0']['Municipio']['nombre_municipio'] . "</b><br>
                <b>Direcci&oacute;n: " . $detalles['0']['Sucursale']['direccion_sucursal'] . "</b><br>
                <b>Tel&eacute;fono:</b> " . $detalles['0']['Sucursale']['telefono_sucursal'] . " - <br>
                <b>Contacto:</b> " . $detalles['0']['Sucursale']['nombre_contacto'] . ' - ' . $detalles['0']['Sucursale']['telefono_contacto'] . "<br>
            </div>
        </td>
    </tr>
</table>
<div><b>Fecha pedido:</b> " . $detalles['0']['Pedido']['pedido_fecha'] . ' ' . $detalles['0']['Pedido']['pedido_hora'] . "</div>
<div><b>Tipo Pedido:</b> " . $detalles['0']['TipoPedido']['nombre_tipo_pedido'] . " - <b>Tipo Movimiento:</b> " . $detalles['0']['TipoMovimiento']['nombre_tipo_movimiento'] . "</div>
<div><b>Observaciones:</b> " . $detalles['0']['Pedido']['observaciones'] . "</div><br>
";

if($detalles['0']['Pedido']['observaciones'] == 'Masivo' && !empty($detalles['0']['Pedido']['pedido_oi_masivo'])){
    foreach ($detalles as $detalle) :
        if(!empty($detalle['PedidosDetalle']['observacion_producto'])){
            $txt.=$detalle['Producto']['codigo_producto'].' - '.$detalle['PedidosDetalle']['observacion_producto'].'<br>';
        }
    endforeach;
}


$tbl = <<<EOD
    $txt
EOD;

$pdf->writeHTML($tbl, true, false, true, false, '');

// <table border="1" cellspacing="1" cellpadding="1">
$html = '<h4>Detalle del Pedido</h4>
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
    $total_sin_iva = $total_sin_iva + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['cantidad_pedido']);
    $total_final = $total_final + ($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'];

    $nombre = /*$detalle['Producto']['codigo_producto'] . " - " . */$detalle['Producto']['nombre_producto'].' '.$detalle['Producto']['marca_producto'];
    $categoria = $detalle['TipoCategoria']['tipo_categoria_descripcion'];
    $cantidad = number_format($detalle['PedidosDetalle']['cantidad_pedido'],0,",",".");
    $precio = number_format($detalle['PedidosDetalle']['precio_producto'], 0, ',', '.');
    $iva = ($detalle['PedidosDetalle']['iva_producto']*100);
    $precio_iva = number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])), 2, ',', '.');
    // $total = number_format(($detalle['PedidosDetalle']['precio_producto'] + ($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['iva_producto'])) * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.');
    $total = number_format($detalle['PedidosDetalle']['precio_producto'] * $detalle['PedidosDetalle']['cantidad_pedido'], 2, ',', '.');

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
                <td>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _</td>
                <td>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _</td>
                <td>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _</td>
            </tr>
            <tr>
                <td>Recibido Por</td>
                <td>Alistado Por</td>
                <td>Revisado Por</td>
            </tr>            
        </table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// $html = '<div>Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</div>';
// $pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("pedidos/op_000" . $detalles['0']['Pedido']['id'] . ".pdf", 'I');
?>
