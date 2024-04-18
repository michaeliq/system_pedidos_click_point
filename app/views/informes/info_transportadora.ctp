<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2><span class="glyphicon glyphicon-book"></span> INFORME TRANSPORTADORA</h2>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>
<?php echo $this->Form->create('Empresa', array('url' => array('controller' => 'informes', 'action' => 'info_transportadora'))); ?>
<fieldset>
    <legend><?php __('Filtro del Informe'); ?></legend>
    <table class="table table-striped table-bordered table-condensed" align="center">
        <tr>
            <td>Empresa: *</td>
            <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => array('0'=>'Todos'), 'label' => false)); ?></td>
        </tr>
    </table>
</fieldset>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<div>&nbsp;</div>
<?php echo $this->Form->end(); ?>
<div class="text-center"><a href="../<?php echo $file_name; ?>"> <i class="icon-download"></i> Descargar Excel</a></div>