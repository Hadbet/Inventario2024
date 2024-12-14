<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT `Id_Bitacora`, `NumeroParte`, `FolioMarbete`, `Fecha`, `Usuario`,
    IF(`Estatus` = 1, 
        '<span class=\"badge badge-pill badge-success\">Liberado</span>', 
        '<span class=\"badge badge-pill badge-danger\">Pendiente</span>'
    ) AS `Estatus`,
    `PrimerConteo`, `SegundoConteo`, `TercerConteo`, `Comentario`, `StorageBin`, `StorageType`, `Area` 
FROM `Bitacora_Inventario` WHERE 1;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>