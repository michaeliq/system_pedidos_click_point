<?php ?>
<style>
    .info_general_pedidos,
    .info_detallado_pedidos,
    .info_productos,
    .info_pedidos_aprobados,
    .info_plantillas,
    .info_cantidad_productos,
    .info_consolidado_facturado,
    .info_acumulado_productos,
    .info_detallado_despachos,
    .inv_general,
    .inv_detalle_producto,
    .info_facturas_compra,
    .info_general_call,
    .info_pdf_masivo,
    .info_detallado_parciales,
    .info_cantidades_ep,
    .info_pedidos_estados,
    .info_estado_pedido,
    .info_sap,
    .info_solicitudes,
    .info_encuestas,
    .info_transportadora,
    .info_avance_pedidos, 
    v_informe_plantilla_precio
    {
        display: none;
    }
</style>
<h2><span class="glyphicon glyphicon-book"></span> INFORMES</h2>
<div></div>
<table class="table">
    <tr>
        <td><div class="info_general_pedidos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe General de Pedidos', true), array('action' => '/info_general_pedidos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
        <td><div class="info_detallado_pedidos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Detallado de Pedidos', true), array('action' => '/info_detallado_pedidos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
    </tr>
    <tr>
        <td colspan="2"><div class="info_avance_pedidos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe % Avance Pedidos', true), array('action' => '/info_avance_pedidos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td colspan="2"><div class="v_informe_plantilla_precio"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Precios Plantilla Vs Operadores', true), array('action' => '/info_plantilla_precio', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>

    <tr>
        <td><div class="info_productos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Productos', true), array('action' => '/info_productos', 'controller' => 'informes'), array('escape' => false,'target' => '_blank')); ?></div></td>
        <td><div class="info_pedidos_aprobados"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Pedidos Aprobados', true), array('action' => '/info_pedidos_aprobados', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td><div class="info_plantillas"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Plantillas', true), array('action' => '/info_plantillas', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
        <td><div class="info_cantidad_productos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Cantidad de Productos Fechas', true), array('action' => '/info_cantidad_productos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td><div class="info_consolidado_facturado"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Consolidado Facturado', true), array('action' => '/info_consolidado_facturado', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
        <td><div class="info_acumulado_productos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Acumulado Productos x Sucursal', true), array('action' => '/info_acumulado_productos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td><div class="info_facturas_compra"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Facturas de Compra', true), array('action' => '/info_facturas_compra', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
        <td><div class="info_detallado_despachos"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Detallado Despachos', true), array('action' => '/info_detallado_despachos', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
    </tr>
    <tr>
        <td><div class="info_pdf_masivo"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Ordenes Masivas PDF', true), array('action' => '/info_pdf_masivo', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
        <td><div class="info_pedidos_estados"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Pedidos por Estado (cantidades)', true), array('action' => '/info_pedidos_estados', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
    </tr>
    <tr>
        <td><div class="info_detallado_parciales"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Ordenes de Entregas Parciales', true), array('action' => '/info_detallado_parciales', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
        <td><div class="info_cantidades_ep"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Cantidades Parciales', true), array('action' => '/info_cantidades_ep', 'controller' => 'informes'), array('escape' => false)); ?></div></td>        
    </tr>
    <tr>
        <td><div class="info_estado_pedido"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Estado Pedido', true), array('action' => '/info_estado_pedido', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
        <td><div class="info_solicitudes"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Solicitudes PQR', true), array('action' => '/info_solicitudes', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td><div class="info_encuestas"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Encuestas', true), array('action' => '/info_encuesta', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
        <td><div class="info_transportadora"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Transportadora', true), array('action' => '/info_transportadora', 'controller' => 'informes'), array('escape' => false)); ?></div></td>

    </tr>

</table>
<h2><span class="glyphicon glyphicon-book"></span> INFORMES INVENTARIOS</h2>
<table class="table">
    <tr>
        <td><div class="inv_general"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe General de Inventario', true), array('action' => '/inv_general', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
    <tr>
        <td><div class="info_sugeridos_compra"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe Sugeridos de Compra', true), array('action' => '/info_sugeridos_compra', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
</table>
<h2><span class="glyphicon glyphicon-book"></span> INFORMES INTEGRACIÓN SAP</h2>
<table class="table">
    <tr>
        <td><div class="info_sap"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe SAP', true), array('action' => '/info_sap', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
</table>
<?php /*
<h2><span class="glyphicon glyphicon-book"></span> INFORMES CALL CENTER</h2>
<table class="table">
    <tr>
        <td><div class="info_general_call"><?php echo $this->Html->link(__('<i class="glyphicon glyphicon-book"></i> Informe General Call Center', true), array('action' => '/info_general_call', 'controller' => 'informes'), array('escape' => false)); ?></div></td>
    </tr>
</table>
*/ ?>