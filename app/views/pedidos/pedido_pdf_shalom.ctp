<?php

App::import('Vendor', 'xtcpdf');
// 2021-07-28
$meses = array('0'=>'INSTALACION','1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');

ob_end_clean();
$page_num = "";
//$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
class MYPDF extends TCPDF {
    
    public function GetDate(){
        return $this-> getAliasNumPage()." de ".$this->getAliasNbPages();
    }
    
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
//$pdf->setHeaderData($ln='', $lw=2, $ht='', $hs='<span align="center">N°. Pedido: #000' . $detalles['0']['Pedido']['id'] . '</span>', $tc=array(0,0,0), $lc=array(0,0,0));

// add a page
$pdf->AddPage();

//$cantidad_final = 0;
//foreach ($detalles as $detalle) :
//    $cantidad_final = $cantidad_final + $detalle['PedidosDetalle']['cantidad_pedido'];
//endforeach;

// Print a text
$pdf->Image(K_PATH_IMAGES . 'logo_shalom.jpg', 10, 11, 27, '', '', '', '', false, 300);
    $page_num = "<span style='display=flex;'>Página ".$pdf->GetDate()."</span>";
    $html = '<table>
    <tr>
        <td style="border-top: 1px solid #000000;  border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000; width:17%" align="center">
            
        </td>
        <td style="border-top: 1px solid #000000;border-left: 1px solid #000000; border-bottom: 1px solid #000000; width:63%;" align="center">
            <table>
                    <tr><td>FORMATO MODELO DE REMISIÓN</td></tr>
                    <tr><td>SUMINISTRO DE ALIMENTOS PERECEDEROS Y NO PERECEDEROS</td></tr>
                    <tr><td>SECRETARIA DISTRITAL DE INTEGRACIÓN SOCIAL</td></tr>
                    <tr><td>PROYECTO 7745 “COMPROMISO POR UNA ALIMENTACIÓN INTEGRAL EN BOGOTÁ”</td></tr>
            </table>
        </td>
        <td style="border-top: 1px solid #000000;  border-right: 1px solid #000000; border-bottom: 1px solid #000000; width:20%" align="center">
            <table>
                    <tr><td style="border-right: 1px solid #000000;  border-left: 1px solid #000000;" align="center">FORMATO NO CONTROLADO</td></tr>
                    <tr><td style="border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000" align="center"></td></tr>
                    <tr><td style="border-right: 1px solid #000000;  border-left: 1px solid #000000; border-bottom: 1px solid #000000" align="center">Versión: 2</td></tr>
                    <tr><td style="border-right: 1px solid #000000; width:100%; border-left: 1px solid #000000;" align="center">
                        '.$page_num.'
                    </td></tr>
            </table>
        </td>
        </tr>
    </table>';
    $pdf->writeHTML($html, false, false, false, false, '');
    $timestamp = strtotime($detalles[0]['Pedido']['fecha_entrega_1']);
    $semana_numero =  idate('W', $timestamp);
    $fecha_entrega = date("j-n-Y",$timestamp); 
    $localidad_nombre = explode("-",$localidad["LocalidadRelRuta"]["nombre_rel"])[0]; 
    $html = '
    <table>
        <tr style="">
            <td style=" border-right: 1px solid #000000; border-left: 1px solid #000000; width:30%;" align="center"><b>Nombre del Comitente Vendedor</b></td>
            <td style=" border-right: 1px solid #000000; width:40%;" align="center"><b>GRUPO EMPRESARIAL SHALOM GES SAS</b></td>
            <td style=" border-right: 1px solid #000000; width:30%;" align="left"><b>Numero de remisión: ' . $detalles[0]['Pedido']['id'] . '</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="border-top: 1px solid #000000; width:30%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>NIT:</b></td>
            <td style="border-top: 1px solid #000000; width:40%; border-right: 1px solid #000000;" align="center"><b>900.664.206-4</b></td>
            <td style="border-top: 1px solid #000000; width:30%; border-right: 1px solid #000000;"><b>Semana: ' . $semana_numero . '</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="border-top: 1px solid #000000; width:15%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>No operación:</b></td>
            <td style="border-top: 1px solid #000000; width:15%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>60138606</b></td>
            <td style="border-top: 1px solid #000000; width:10%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>Grupo:</b></td>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>' . $detalles['0']['TipoPedido']['nombre_tipo_pedido'] . '</b></td>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>Fecha de entrega:</b></td>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center"><b>' .$fecha_entrega. '</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;border-bottom: 1px solid #000000;" align="center"><b>Localidad de entrega:</b></td>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;border-bottom: 1px solid #000000;" align="center"><b>' . $localidad_nombre . '</b></td>
            <td style="border-top: 1px solid #000000; width:20%; border-right: 1px solid #000000; border-left: 1px solid #000000;border-bottom: 1px solid #000000;" align="center"><b>Nombre de unidad operativa:</b></td>
            <td style="border-top: 1px solid #000000; width:40%; border-right: 1px solid #000000; border-left: 1px solid #000000;border-bottom: 1px solid #000000;" align="center"><b>' . $detalles['0']['Sucursale']['nombre_sucursal'] . '</b></td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '<table>
    <tr>
        <td style="border-top: 1px solid #000000; border-right: 1px solid #000000;  border-left: 1px solid #000000; background-color:#C0C0C0;"  align="center" width="100%"><b>DETALLE DEL PEDIDO</b></td>
    </tr>
    <tr style="background-color:#C0C0C0;">
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" width="10%" align="center"><b>ITEM</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="30%" align="center"><b>PRODUCTO</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="10%" align="center"><b>MARCA</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="15%" align="center"><b>PRESENTACIÓN</b></td>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="10%" align="center"><b>CANTIDAD</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="10%" align="center"><b>LOTE</b></td>        
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" width="15%" align="center"><b>FECHA DE VENCIMIENTO</b></td>        
    </tr>';
    $i = 1;
    $total_final = 0;
    $observaciones = null;
    $cantidad_final = 0;
    foreach ($detalles as $key => $detalle) :
        $codigo = $detalle['Producto']['codigo_producto'];
        $nombre = $detalle['Producto']['nombre_producto'];
        $marca = $detalle['Producto']['marca_producto'];
        $unidad = utf8_decode($detalle['Producto']['medida_producto']);
        $cantidad = $detalle['PedidosDetalle']['cantidad_pedido'];
        $lote = $detalle['PedidosDetalle']['lote'];
        $timestamp_expiracion = strtotime($detalle['PedidosDetalle']['fecha_expiracion']);
        $fecha_vencimiento = date("j-n-Y",$timestamp_expiracion); 
        $observacion_producto = $detalle['PedidosDetalle']['observacion_producto'];
        $cantidad_final = $cantidad_final + $detalle['PedidosDetalle']['cantidad_pedido'];

        $html .= '
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="center">' . $i . '</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="left">' . $nombre . '</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $marca . '</td>     
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $unidad . '</td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $cantidad . '</td>             
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $lote . '</td>             
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center">' . $fecha_vencimiento . '</td>             
        </tr>';
        
        $i++;

    endforeach;
    $html .= '
    <tr>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" align="right" colspan="4"><b>Cantidad de Productos:</b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center" colspan="1" align="center"><b>' . $cantidad_final . '</b></td>
        <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" align="center" colspan="2" align="center"><b>Items:</b> ' . ($i - 1) . '</td>
    </tr>';
    if ($i > 30) {
        $html .= '
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>';
    }

    $html .= '
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; background-color:#C0C0C0;" colspan="7"  align="center"><b>OBSERVACIONES</b></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;" colspan="7">
            <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
            </td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width:20%;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>FECHA DE ENTREGA: </b></td>
            <td style="width:20%;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
            <td style="width:20%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>HORA DE LLEGADA: </b></td>
            <td style="width:10%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
            <td style="width:20%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>HORA DE SALIDA: </b></td>
            <td style="width:10%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
        </tr>
    </table>
    ';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html = '
    <table>
        <tr>
            <td style="width:25%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>NOMBRE Y APELLIDO DE QUIEN ENTREGA:</b></td>
            <td style="width:25%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
            <td style="width:25%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>NOMBRE Y APELLIDO DE QUIEN RECIBE:</b></td>
            <td style="width:25%;border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>FIRMA DE QUIEN ENTREGA:<br/></b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>FIRMA DE QUIEN RECIBE:</b><br/></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>NÚMERO DE DOCUMENTO:<br/></b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b>NÚMERO DE DOCUMENTO:<br/></b></td>
            <td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><b></b></td>
        </tr>
        
    </table>';
    $pdf->writeHTML($html, false, false, true, false, '');

    $html = '
        <table style="border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;">
            <tr><td align="left"><i>DIRECCIÓN Y TELÉFONO DE CONTACTO DE LA EMPRESA</i></td></tr>
            <tr><td align="left"><i>Dirección: Cl 4a 34-44</i></td></tr>
            <tr><td align="left"><i>Tel: 3195067414-3143884510</i></td></tr>
            <tr><td align="left"><i>Email: shalomsdispollo@gmail.com-shalomoperacionsdis@gmail.com</i></td></tr>
            <tr><td style="border-top: 1px solid #000000;border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><i>*Nota: Este formato no es válido para reposiciones.</i></td></tr>

        </table>
    ';
    // output the HTML content
    $pdf->writeHTML($html, true, false, false, false, '');

    $html = '
        <table>
            <tr><td align="left"><h4>'. $detalles['0']['Sucursale']['direccion_sucursal'] . ' | <b>DEPARTAMENTO:</b> ' . $detalles['0']['Departamento']['nombre_departamento'] .'</h4></td></tr>
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output("pedidos/op_000".$detalles['0']['Pedido']['id'].".pdf", 'FI');

//============================================================+
// END OF FILE
//============================================================+