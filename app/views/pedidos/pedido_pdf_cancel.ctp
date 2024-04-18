<?php

App::import('Vendor', 'xtcpdf');

ob_end_clean();
// create new PDF document
// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'KOPANCOBA DELIVERY SAS - Nit 900.751.920-8', 'Calle 20 C No. 44 - 70 - Tel: 7498501 - 7046213  | #000' . $detalles['0']['Pedido']['id'] . '');

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
<div><b>Tipo Pedido:</b> " . $detalles['0']['TipoPedido']['nombre_tipo_pedido'] . "</div>

<div><h1>CANCELADO</h1></div>";

$tbl = <<<EOD
    $txt
EOD;

$pdf->writeHTML($tbl, true, false, true, false, '');

// $html = '<div>Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</div>';
// $pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("pedidos/op_000" . $detalles['0']['Pedido']['id'] . ".pdf", 'FI');
?>
