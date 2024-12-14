<?php

include_once('db/db_Inventario.php');


$marbete = $_GET['marbete'];

ContadorApu($marbete);

function ContadorApu($marbete)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    B.Id_Bitacora, 
    B.NumeroParte, 
    B.FolioMarbete, 
    B.Fecha, 
    B.Usuario, 
    B.UsuarioVerificacion, 
    B.Estatus, 
    B.PrimerConteo, 
    B.SegundoConteo, 
    B.TercerConteo, 
    B.SegFolio, 
    B.UserSeg, 
    B.Comentario, 
    B.StorageBin, 
    B.StorageType, 
    B.Area, 
    COALESCE(S.Id_StorageUnit, 'NA') AS StorageUnit,
    COALESCE(S.Cantidad, 'NA') AS CantidadStorage
FROM 
    Bitacora_Inventario B 
LEFT JOIN 
    Storage_Unit S 
ON 
    B.FolioMarbete = S.FolioMarbete AND S.Estatus=1
WHERE 
    B.FolioMarbete = '$marbete' 
    and B.Estatus = 1;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>