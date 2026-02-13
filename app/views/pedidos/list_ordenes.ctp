<?php echo $this->Html->script(array('pedidos/list_ordenes.js?var=' . date('dymhmis'))); ?>
<h2><span class="glyphicon glyphicon-list-alt"></span> ORDENES DE PEDIDO (EN PROCESO - PENDIENTES DE APROBACIÓN)</h2>
<?php echo $this->Form->create('Pedido'); ?>
<?php /*<table class="table table-condensed ">
    <tr>
        <td>No. Orden:</td>
        <td><?php echo $this->Form->input('id', array('type' => 'text', 'size' => '5', 'maxlength' => '5', 'label' => false)); ?></td>
        <td>Fecha Orden:</td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td>Sucursales:</td>
        <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
</table> */ ?>
<table class="table table-condensed ">
    <tr>
        <td>Empresas:</td>
        <td colspan="3"><?php echo $this->Form->input('empresa_id', array('type' => 'select', 'options' => $empresas, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Regional:</td>
        <td><?php echo $this->Form->input('regional_sucursal', array('type' => 'select', 'options' => $regional, 'empty' => 'Todos', 'label' => false)); ?></td>
        <td>Sucursales:</td>
        <td><?php echo $this->Form->input('sucursal_id', array('type' => 'select', 'options' => $sucursales, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>No. Orden:</td>
        <td><?php echo $this->Form->input('pedido_id', array('type' => 'text', 'size' => '6', 'maxlength' => '6', 'label' => false)); ?></td>
        <td>Fecha Orden:</td>
        <td><?php echo $this->Form->input('pedido_fecha', array('type' => 'text', 'size' => '12', 'maxlength' => '12', 'label' => false)); ?></td>
    </tr>
    <tr>
        <td>Tipo Pedido:</td>
        <td><?php echo $this->Form->input('tipo_pedido_id', array('type' => 'select', 'options' => $tipo_pedido, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
        <td>Estado Orden:</td>
        <td><?php echo $this->Form->input('pedido_estado_pedido', array('type' => 'select', 'options' => $estados, 'empty' => 'Seleccione una Opción', 'label' => false)); ?></td>
    </tr>
</table>
<div class="text-center">
    <?php echo $this->Form->button('Regresar', array('type' => 'button', 'class' => 'btn btn-warning', 'onclick' => 'window.history.back();')); ?>
    <?php echo $this->Form->button('Buscar', array('type' => 'submit', 'class' => 'btn btn-success')); ?>
</div>
<?php echo $this->Form->end(); ?>
<div>&nbsp;</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>No. Orden</th>
        <th>No. Consecutivo</th>
        <th>Datos Empresa</th>
        <th>Fecha Orden</th>
        <th>Direcci&oacute;n Envio</th>
        <th>Tipo Pedido</th>
        <th>Estado Orden</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($pedidos) > 0) {
        foreach ($pedidos as $pedido):
    ?>
            <div
                id='<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>'
                style="
                position:fixed; 
                top:0; 
                left:0; 
                width:100%; 
                height:100vh; 
                background-color:#0003;
                justify-content:center;
                align-items:center;
                display:none;">
                <div class="print-dialog-box" style="padding:2rem; width: 600px; height: auto; display:flex;flex-direction:column;row-gap:1rem; justify-content:center; align-items:center; background-color: #ddd; border-radius:25px;">
                    <div class="header-dialog-box">
                        <h3 class=""><?php echo "Generar reporte de Orden #" . $pedido['Pedido']['id']; ?></h3>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CENTRO ASEO <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte MEGAEXPERTOS <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_megaexpertos', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>

                            <?php echo " " . $this->Html->link("Reporte CLICK POINT <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_click_point', $pedido['Pedido']['id']), array('class' => '', 'target' => '_blank', 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL ZOE <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_zoe', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO 1A <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_1a', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO KAPITAL <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_consorcio_kapital', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL BIOCENTER <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_biocenter', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte UNION TEMPORAL CCE AMP IV 2022 <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_ut_cce_amp', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte LIMPIO PLUS <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_limpio_plus', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <button type="button" class="btn btn-primary btn-lg">
                        <h6>
                            <?php echo " " . $this->Html->link("Reporte CONSORCIO KLEAN Y LOGISTIC <p class='glyphicon glyphicon-print'></p>", array('controller' => 'pedidos', 'action' => 'pedido_pdf_klean_logist', $pedido['Pedido']['id']), array('class' => '', "target" => "_blank", 'escape' => false, 'style' => 'color:#fff')); ?>
                        </h6>
                    </button>
                    <div class="footer-container-btn">
                        <button type="button" id='<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>' class="btn btn-secondary btn-lg m-2 p-3">CERRAR</button>
                    </div>
                </div>
            </div>
            <tr>
                <td><span style='color:red;'>#000<?php echo $pedido['Pedido']['id']; ?></span></td>
                <td><span style='color:red; font-weight: bold;'><?php echo $pedido['Pedido']['consecutivo']; ?></span></td>
                <td><b>Emp:</b> <?php echo $pedido['Empresa']['nombre_empresa']; ?><br><b>Suc:</b> <?php echo $pedido['Sucursale']['nombre_sucursal']; ?><br><b>Reg:</b> <?php echo $pedido['Sucursale']['regional_sucursal']; ?></td>
                <td><?php echo $pedido['Pedido']['pedido_fecha'] . ' ' . $pedido['Pedido']['pedido_hora']; ?> </td>
                <td><?php echo $pedido['Departamento2']['nombre_departamento']; ?> - <?php echo $pedido['Municipio2']['nombre_municipio']; ?><br><?php echo $pedido['Sucursale']['direccion_sucursal']; ?> </td>
                <td><?php echo $pedido['TipoPedido']['nombre_tipo_pedido'] ?></td>
                <td><?php echo $pedido['EstadoPedido']['nombre_estado']; ?> </td>
                <td>
                    <?php
                    if ($pedido['Pedido']['pedido_estado_pedido'] == '1') {
                    ?>
                        <div title="Continuar Pedido"><?php echo $this->Html->link(__('', true), array('action' => 'detalle_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-arrow-right', 'escape' => false)); ?></div>
                    <?php
                    } else {
                    ?>
                        <div class="ver_pedido" title="Ver"><?php echo $this->Html->link(__('', true), array('action' => 'ver_pedido', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-search', 'escape' => false)); ?></div>

                        <!-- <div title="Imprimir Reporte Shalom"><?php /*  echo $this->Html->link(__('', true), array('controller' => 'pedidos', 'action' => 'pedido_pdf_shalom', $pedido['Pedido']['id']), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank', 'escape' => false)); */ ?></div> -->

                        <div id='<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>' title="Imprimir Reporte Shalom">
                            <p class="glyphicon glyphicon-print text-primary" style="cursor:pointer"></p>
                        </div>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <script>
                $('#<?php echo "show-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click",function(){
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display","flex")
                })

                $('#<?php echo "close-box-btn" . $pedido['Pedido']['id']; ?>').
                on("click",function(e){
                    $('#<?php echo "print-dialog-box-container-" . $pedido['Pedido']['id']; ?>').
                    css("display","none")
                })
            </script>
        <?php
        endforeach;
    } else {
        ?>
        <tr>
            <td colspan="7" class="text-center">No existen ordenes de pedido en el sistema.</td>
        </tr>
    <?php
    }
    ?>
</table>
<p class="text-center">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página %page% de %pages%, mostrando %current% registros de %count% total, iniciando en %start%, finalizando en %end%', true)
    ));
    ?> </p>

<div class="text-center">
    <?php echo $this->Paginator->prev('<< ' . __('Anterior', true), array(), null, array('class' => 'disabled')); ?>
    | <?php echo $this->Paginator->numbers(); ?>
    |
    <?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
</div>