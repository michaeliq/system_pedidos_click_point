<?php

echo $html->script(array('jquery-1.8.3', 'jquery-ui-1.9.2.custom', 'bootstrap.min.js')); // 'autoNumeric-1.7.5'
echo $html->css(array('bootstrap.min.css', 'smoothness/jquery-ui-1.9.2.custom.min', 'kopan'));
echo $scripts_for_layout;        
echo $content_for_layout;
echo $this->element('sql_dump'); 

?>
