<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        // Handler for .ready() called.
        // Por defecto se ocultan los menus que no sean activos para el usuario
        // Luego de verificar si esta autorizado se muestran
        $j(".administracion").css("display","none");
        $j(".pedidos").css("display","none");
        $j(".informes").css("display","none");
        $j(".movimientosentradas").css("display","none");
        $j(".callcenter").css("display","none");
        $j(".solicitudes").css("display","none");
        $j(".ordencompras").css("display","none");
<?php

foreach ($menus as $menu) :
    ?>
                $j(".<?php echo strtolower($menu['Menu']['menu_controller']); ?>").css("display","block");
    <?php
endforeach;
if($authorize){
    foreach ($authorize as $authorize) :
        ?>
                    $j(".<?php echo $authorize; ?>").css("display","block");
        <?php
    endforeach;
}
?>
    });
    var $ = jQuery.noConflict();
</script>