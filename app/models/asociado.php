<?php

class Asociado extends AppModel
{

    var $name = 'Asociado';
    var $validate = array(
        "nombre_asociado" => array(
            "notempty" => array(
                "rule" => "notempty",
                "message" => "Este campo no debe estar vacio"
            )
        ),
        "numero_contrato" => array(
            "notempty" => array(
                "rule" => "notempty",
                "message" => "Este campo no debe estar vacio"
            )
        )
    );
    var $hasMany = array(
        "Consecutivo" => array(
            "className" => "Consecutivo",
            "foreignKey" => "id"
        ),
    );
    var $belongsTo = array(
        "Empresa" => array(
            "className" => "Empresa",
            "foreignKey" => "id_empresa"
        ),
    );
}
