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
            <div>
                <?php echo $content_for_layout; ?>
            </div>
        </div>
    </body>
</html>