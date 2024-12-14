<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    `Id_Bitacora`, 
    `NumeroParte`, 
    `FolioMarbete`, 
    `Fecha`, 
    `Usuario`,
    CASE 
        WHEN `Estatus` = 1 THEN '<span class=\"badge badge-pill badge-success\">Liberado</span>'
        WHEN `Estatus` = 2 THEN '<span class=\"badge badge-pill badge-warning\">En Validacion</span>'
        WHEN `Estatus` = 5 THEN '<span class=\"badge badge-pill badge-danger\">Cancelado</span>'
        ELSE '<span class=\"badge badge-pill badge-danger\">Pendiente</span>'
    END AS `Estatus`,
    `PrimerConteo`, 
    `SegundoConteo`, 
    `TercerConteo`, 
    `Comentario`, 
    `StorageBin`, 
    `StorageType`, 
    `Area` 
FROM `Bitacora_Inventario` WHERE `Area` = '$area';");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>