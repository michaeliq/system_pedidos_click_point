<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('PEDIDOS CENTRO INTEGRAL DE SERVICIOS EMPRESARIALES S.A.S'); ?>
            <?php echo $title_for_layout; ?>
        </title>
        <link rel="icon" href="<?php echo $this->webroot; ?>cise.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo $this->webroot; ?>cise.ico" type="image/x-icon" />
        <?php
        echo $html->script(array('jquery-1.8.3', 'jquery-ui-1.9.2.custom', 'bootstrap.min.js')); // 'autoNumeric-1.7.5'
        echo $html->css(array('bootstrap.min.css', 'smoothness/jquery-ui-1.9.2.custom.min', 'kopan'));
        echo $scripts_for_layout;
        echo $this->element('element_authorize');
        ?>
    </head>
    <body>
        <div class="container">
            <div class="text-center" ><?php  echo $html->image('cabezote.png?id=20220614', array('alt' => 'Kopan&Coba')) ?></div>
            <?php if ($this->Session->read('Auth.User.id') > 0) { ?>
            <div class="text-right"><b>Bienvenido:</b> <?php echo $this->Session->read('Auth.User.nombres_persona'); ?></div>
            <?php } ?>
            <div>
                <?php echo $this->element('element_menu'); ?>
<!--                <p style="font-size: x-small; background-color:  red; color: white;">&nbsp;&nbsp;&nbsp;<b>AMBIENTE DE PRUEBAS!!!</b>&nbsp;&nbsp;&nbsp;</p>-->
                <?php echo $this->Session->flash(); ?>
                <?php if(date('Y-m-d') <= '2020-12-14' ){ ?>
                    <p style="font-size: x-small; background-color:  red; color: white;">&nbsp;&nbsp;&nbsp;<b>ATENCIÓN!!! El sistema no estará disponible a partir de las 10 pm del 2020-12-14 por motivos de Mantenimiento! Este mantenimiento tardará una hora aproximadamente.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;</p>
                <?php } ?>
                <?php echo $content_for_layout; ?>
            </div>
            <?php echo $this->element('element_footer'); ?>
        </div>

        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>