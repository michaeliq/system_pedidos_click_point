<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><span class="glyphicon glyphicon-question-sign"></span> PREGUNTAS DE ENCUESTAS</h2>
<?php echo $this->Form->create('Encuesta'); ?>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Pregunta</th>
            <th>Orden Pregunta</th>
            <th class="actions"><?php __('Acciones'); ?></th>
        </tr>
        <?php 
        $i = 0;
        foreach ($preguntas as $pregunta):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $pregunta['EncuestasPregunta']['pregunta_encuesta']; ?></td>
            <td><?php echo $pregunta['EncuestasPregunta']['orden_pregunta']; ?></td>
            <td></td>
        </tr>
        <?php
            endforeach;
        ?>
    </table>
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar_add', 'class' => 'btn btn-warning')); ?>
</div>