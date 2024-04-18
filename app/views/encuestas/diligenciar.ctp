<h2><span class="glyphicon glyphicon-question-sign"></span> DILIGENCIAR ENCUESTA PARA LA ORDEN DE PEDIDO <?php /* <span style="color:red; font-weight: bold;"> #000<?php echo $this->Session->read('Pedido.pedido_id'); ?></span>*/?></h2>
<?php echo $this->Form->create('Encuesta', array('url' => array('controller' => 'encuestas', 'action' => 'diligenciar/'.$this->Session->read('Pedido.pedido_id')))); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false,'value'=>$this->Session->read('Pedido.pedido_id'))); ?>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>No.</th>
            <th>Pregunta</th>
            <th>Respuesta</th>
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
            <td style="vertical-align:middle; text-align: center; "><?php echo $i; ?></td>
            <td  style="vertical-align:middle; text-align: center; "><h4><?php echo $pregunta['EncuestasPregunta']['pregunta_encuesta']; ?></h4></td>
            <td><?php echo $this->Form->radio('encuestas_respuestas_id_'.$pregunta['EncuestasPregunta']['id'],$puntajes,array('name'=>'encuestas_respuestas_id_'.$pregunta['EncuestasPregunta']['id'],'legend'=>false ,'separator'=>'<div style=""></div>','required'=>'required')); ?></td>
        </tr>
        <?php
            endforeach;
        ?>
        <tr>
            <td colspan="3" style="text-align: center;">
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'onclick' => 'history.back()', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Diligenciar Encuesta', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>

</div>

<?php echo $this->Form->end(); ?>