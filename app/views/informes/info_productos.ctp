<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    img.mediana{width: 150px; height: 150px;}
</style>
<h2><span class="glyphicon glyphicon-book"></span>INFORME DE PRODUCTOS</h2>
<div class="index">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Imagen</th>
            <th>Código</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Unidad Medida</th>
            <th>FT</th>
            <th>RI</th>
            <th>HS</th>
            <th>RB</th>
            <th>OT</th>
        </tr> 
        <?php
        $i = 0;
        foreach ($productos as $producto):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class; ?>>
            <td><?php echo $html->image('productos/'.$producto['Producto']['codigo_producto'].'.jpg', array('class'=>'mediana','width'=>'40%','height'=>'40%','alt' => $producto['Producto']['nombre_producto'])) ?></td>
            <td><?php echo $producto['Producto']['codigo_producto']; ?></td>
            <td><?php echo $producto['Producto']['nombre_producto']; ?></td>
            <td><?php echo $producto['TipoCategoria']['tipo_categoria_descripcion']; ?></td>
            <td><?php echo $producto['Producto']['medida_producto']; ?></td>
            <td>
                <?php 
                if ($producto['Producto']['archivo_adjunto']) { 
                    echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                }
                ?>
            </td>
            <td>
                <?php 
                if ($producto['Producto']['archivo_adjunto_2']) { 
                    echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_2'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                }
                ?>
            </td>
            <td>
                <?php 
                if ($producto['Producto']['archivo_adjunto_3']) { 
                    echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_3'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                }
                ?>
            </td>
            <td>
                <?php 
                if ($producto['Producto']['archivo_adjunto_4']) { 
                    echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_4'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                }
                ?>
            </td>
            <td>
                <?php 
                if ($producto['Producto']['archivo_adjunto_5']) { 
                    echo '<a href="/pedidos/img/productos/archivos/'.$producto['Producto']['archivo_adjunto_5'].'" target="_blank">&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>';
                }
                ?>
            </td>
        </tr>    
        <?php endforeach; ?>
    </table>
</div>