<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * background-color: #f5f5f5;
 */
?>
<style>
    #footer {
        border-top: #666666 solid 1px;
        border-bottom: #666666 solid 1px;

        padding-right: 30px;
        padding-top: 10px;
        text-align: right;
    }
</style>
<div>&nbsp;</div>
<div id="footer">
    <!--    <address>
            <strong>CLEANEST PRODUCTOS DE LIMPIEZA Y CONSERVACION S.A.S - &copy; 2017</strong><br>
            KM 1.2 V&iacute;a Siberia, entrada al parque La Florida. <br> Parque Industrial Terrapuerto, Bodega 32. Bogot&aacute; D.C<br>
            <abbr title="Telefonos">Tel:</abbr> (1) 606 84 33  / 484 91 20 <br>
            <a href="mailto: servicioalcliente@cleanest.com.co"> servicioalcliente@cleanest.com.co</a>
        </address>-->
    <address>
        <?php
        if (strpos($_SERVER['HTTP_HOST'], "clickpoint") !== false) {
            echo "<strong>CLICK POINT SAS - &copy; 2017</strong><br>";
        } else if (strpos($_SERVER['HTTP_HOST'], "centroaseo") !== false) {
            echo "<strong>CENTRO ASEO MANTENIMIENTO PROFESIONAL SAS - &copy; 2017</strong><br>";
        } else {
            echo "";
        }
        ?>
        Carrera 28B #77-12 <br> Barrio Santa Sofía, Bogot&aacute; D.C<br>
        <abbr title="Telefonos">Tel:</abbr> (601) 4849120 / (601) 4849129 / (601) 6068433 <br>
        <a href="mailto: servicolaborador@centroaseo.com">servicolaborador@centroaseo.com</a>
    </address>
</div>