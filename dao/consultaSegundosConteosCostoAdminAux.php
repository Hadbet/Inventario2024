<?php

include_once('db/db_Inventario.php');


ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    B.FolioMarbete,
    B.NumeroParte,
    A.AreaNombre,
    COALESCE(
        CASE 
            WHEN B.TercerConteo != 0 THEN B.TercerConteo
            WHEN B.SegundoConteo != 0 THEN B.SegundoConteo
            ELSE B.PrimerConteo
        END, 
    0) AS CantidadContada,
    COALESCE(I.Cantidad, 0) AS CantidadInventarioSap,
    ROUND(
        COALESCE(
            CASE 
                WHEN B.TercerConteo != 0 THEN B.TercerConteo
                WHEN B.SegundoConteo != 0 THEN B.SegundoConteo
                ELSE B.PrimerConteo
            END, 
        0) - COALESCE(I.Cantidad, 0), 
    3) AS Diferencia,
    B.StorageBin ,
    I.STType
FROM 
    Bitacora_Inventario B 
LEFT JOIN 
    InventarioSap I 
ON 
    B.Area = I.AreaCve AND B.StorageBin = I.STBin AND B.NumeroParte = I.GrammerNo 
LEFT JOIN
    Area A
ON
    B.Area = A.IdArea
WHERE 
    B.Estatus = 1  ");
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>