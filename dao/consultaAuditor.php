<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();

function ContadorApu()
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
        WHEN `Estatus` = 0 THEN '<span class=\"badge badge-pill badge-danger\">No Capturado</span>'
        WHEN `Estatus` = 1 THEN '<span class=\"badge badge-pill badge-success\">Capturado</span>'
        WHEN `Estatus` = 2 THEN '<span class=\"badge badge-pill badge-warning\">En Verificación</span>'
        WHEN `Estatus` = 5 THEN '<span class=\"badge badge-pill badge-secondary\">Cancelado</span>'
        ELSE '<span class=\"badge badge-pill badge-info\">Estado Desconocido</span>'
    END AS `Estatus`,
    `PrimerConteo`, 
    `SegundoConteo`, 
    `TercerConteo`, 
    `Comentario`, 
    `StorageBin`, 
    `StorageType`, 
    `Area` 
FROM 
    `Bitacora_Inventario` 
WHERE 1;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>