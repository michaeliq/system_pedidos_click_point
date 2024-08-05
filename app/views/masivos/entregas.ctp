<?php
    
?>

<h2>Cargador Masivo de Entregas</h2>
<?php echo $this->Form->create('Masivo', array('type' => 'file')); ?>
<table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 50%;">
    <tr>
        <td colspan="2" class="text-center"><b>Seleccione un archivo para cargar los datos de las entregas:</b><br>Tenga en cuenta lo siguiente:<br>
            <b>Extensión de Archivo:</b> CSV (separado por punto y coma ;) - ejemplo: <b>archivo_entregas.csv</b><br><br>
        </td>
    </tr>
    <tr>
        <td colspan="2" align='center'><?php echo $this->Form->input('archivo_csv', array('type' => 'file', 'class' => 'btn btn-file','label'=>false,'div'=>false, 'required' => true, 'accept' => "text/csv")); ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <b>Seleccione el(los) archivo(s) para cargar el(los) PDF(s) con las firmas. Tenga en cuenta lo siguiente:</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left">
            
            <b>Varios Documentos:</b> Se revisará cada hoja en busca del número de consecutivo/orden que se haya establecido en el CSV y se vinculará con este registro.<br>
            <b>Un Documento con varias hojas: </b> Se vinculará un solo documento con multiples pedidos/remisones, asociando el número de hoja donde se encuentra la información coincidente.<br>
        </td>
    </tr>
    <tr>
        <td colspan="2" align='center'>
            <?php echo $this->Form->input('archivos_pdf. ', array('type' => 'file', 'class' => 'btn btn-file','label'=>false,'div'=>false, 'required' => true, "multiple" => "multiple", "accept" => "application/pdf")); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <?php echo $this->Form->button('Cargar Archivo', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
            <?php echo $this->Form->input('fecha_hora_carga',array('type'=>'hidden', 'value'=>date('Y-m-d H:i:s')));?></td>
    </tr>
</table>
<?php if (count($entregas_validas) > 0) { ?>
    <table class="table table-striped table-bordered table-hover table-condensed" align='center' style="width: 80%;">
        <tr>
            <th>No. Orden</th>
            <th>No. Consecutivo</th>
            <th>Fecha de entrega</th>
            <th>Estado</th>
            <th>Documento</th>
        </tr>
        <?php foreach ($entregas_validas as $entrega_valida) : ?>
            <tr>
                <td><?php echo empty($entrega_valida["NO_ORDEN"]) ? "SIN DEFINIR" : $entrega_valida["NO_ORDEN"]; ?></td>
                <td><?php echo empty($entrega_valida["NO_CONSECUTIVO"]) ? "SIN DEFINIR" : $entrega_valida["NO_CONSECUTIVO"]; ?></td>
                <td><?php echo $entrega_valida["FECHA_ENTREGA"]; ?></td>
                <?php if($entrega_valida["existe"]){
                    echo "<td class='text-center'><i title='Está en base de datos, será actualizado' style='color:blue;' class='glyphicon glyphicon-arrow-up'></i></td>";
                }else{
                    echo "<td class='text-center'><i title='No hay coincidencias del No. de orden ni el consecutivo' style='color:red;' class='glyphicon glyphicon-exclamation-sign'></i></td>";
                } ?>
                <?php if($entrega_valida["doc_encontrado"]){
                    echo "<td class='text-center' style='color:green; font-weight:bold'>ENCONTRADO EN CSV</td>";
                }else{
                    echo "<td class='text-center' style='color:red; font-weight:bold'>NO ENCONTRADO</td>";
                } ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php }; ?>
<?php echo $this->Form->end(); ?>