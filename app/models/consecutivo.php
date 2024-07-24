<?php

class Consecutivo extends AppModel
{
    var $name = "Consecutivo";
    var $primaryKey = 'id';
    var $virtualFields = array(
        'asociado_rel_consecutivo' => "Asociado.nombre_asociado || ' - ' || Consecutivo.numero_contrato"
    );
    
    var $belongsTo = array(
        "Asociado" => array(
            "className" => "Asociado",
            "foreignKey" => "asociado_id"
        )
    );
}