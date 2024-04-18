<div class="Solicitudes form">
<?php echo $this->Form->create('Solicitud', array('type' => 'file','url' => array('controller' => 'solicitudes', 'action' => 'registrar_solicitud'))); ?>
    <fieldset>
        <legend><?php __('Nueva Solicitud PQR'); ?></legend>
        <table class="table table-striped table-bordered table-condensed" align="center">
            <tr>
                <td><b>Tipo de Solicitud: *</b></td>
                <td><?php echo $this->Form->input('tipo_solicitud_id', array('type' => 'select', 'options' => $tipoSolicitud, 'label' => false, 'empty'=>'Seleccione una Opción')); ?> </td>            
            </tr>  
            <tr>
                <td><b>Motivo / Asunto: *</b></td>
                <td><?php echo $this->Form->input('tipo_motivo_solicitud_id', array('type' => 'select', 'options' => $tipoMotivo, 'label' => false, 'empty'=>'Seleccione una Opción')); // echo $this->Form->input('motivo_asunto', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '100')); ?></td>
            </tr>
            <tr>
                <td><b>Detalles de la solicitud: *</b></td>
                <td><?php echo $this->Form->input('detalles_solicitud', array('type' => 'textarea', 'label' => false, 'rows' => '4','cols'=>'42')); ?></td>
            </tr>

            <tr>
                <td><b>No. Documentos: </b></td>
                <td><?php echo $this->Form->input('no_documento', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '15','value'=>$this->Session->read('Auth.User.no_identificacion_persona'))); ?></td>
            </tr>
            <tr>
                <td><b>Nombres y Apellidos: </b></td>
                <td><?php echo $this->Form->input('nombres_apellidos', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '120','value'=>$this->Session->read('Auth.User.nombres_persona'))); ?></td>
            </tr>
            <tr>
                <td><b>Celular - Teléfono: </b></td>
                <td><?php echo $this->Form->input('celular_telefono', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '120')); ?></td>
            </tr>
            <tr>
                <td><b># Orden de Pedido: </b></td>
                <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'label' => false, 'size' => '40', 'maxlength' => '6')); ?></td>
            </tr>
            <tr>
                <td><b>Adjuntar Archivo:</b> </td>
                <td>
                    <input type="file" name="data[Solicitud][archivo_adjunto][]" id="file" multiple>
                    <?php // echo $this->Form->input('archivo_adjunto', array('type' => 'file', 'label' => false,'multiple')); ?><br><span style="font-size: x-small;">Solo se aceptan archivos en cualquier formato válido</span></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" >
                <?php echo $this->Form->button('Regresar', array('type' => 'button', 'id' => 'regresar', 'onclick'=>'history.back()', 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Form->button('Registrar Solicitud', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <?php echo $this->Form->end();?>
</div>