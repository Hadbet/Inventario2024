<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT COALESCE(B.NumeroParte, I.GrammerNo) AS NumeroParte, B.FolioMarbete, 
COALESCE(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END, 0) AS CantidadBitacora, 
COALESCE(I.Cantidad, 0) AS CantidadInventarioSap, 
COALESCE(B.StorageBin, I.STBin) AS StorageBin, 
(COALESCE(I.Cantidad, 0) - COALESCE(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END, 0)) AS DiferenciaCantidad, 
A.AreaNombre 
FROM Bitacora_Inventario B 
LEFT JOIN InventarioSap I ON B.NumeroParte = I.GrammerNo AND B.StorageBin = I.STBin 
LEFT JOIN Area A ON B.Area = A.IdArea 
WHERE B.Estatus = 1 AND (I.GrammerNo IS NULL OR 
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END != I.Cantidad OR I.Cantidad = 0 OR 
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END = 0 OR ABS(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END - IFNULL(I.Cantidad, 0)) >= 10000)

UNION

SELECT COALESCE(B.NumeroParte, I.GrammerNo) AS NumeroParte, B.FolioMarbete, 
COALESCE(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END, 0) AS CantidadBitacora, 
I.Cantidad AS CantidadInventarioSap, 
COALESCE(B.StorageBin, I.STBin) AS StorageBin, 
(I.Cantidad - COALESCE(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END, 0)) AS DiferenciaCantidad, 
A.AreaNombre 
FROM InventarioSap I 
LEFT JOIN Bitacora_Inventario B ON I.GrammerNo = B.NumeroParte AND I.STBin = B.StorageBin 
LEFT JOIN Area A ON I.AreaCve = A.IdArea 
WHERE (B.NumeroParte IS NULL OR 
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END != I.Cantidad OR I.Cantidad = 0 OR 
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END = 0 OR ABS(
    CASE WHEN B.TercerConteo != 0 THEN B.TercerConteo 
         WHEN B.SegundoConteo != 0 THEN B.SegundoConteo 
         ELSE B.PrimerConteo 
    END - I.Cantidad) >= 10000);");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>