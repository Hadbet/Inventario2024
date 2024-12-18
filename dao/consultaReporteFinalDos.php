<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    ISap.STBin, 
    ISap.STType, 
    ISap.GrammerNo, 
    ISap.Cantidad as Total_InventarioSap, 
    (SELECT COALESCE( 
        CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
        CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
        BInv.PrimerConteo 
    ) FROM Bitacora_Inventario BInv WHERE BInv.NumeroParte = ISap.GrammerNo AND BInv.StorageBin = ISap.STBin AND BInv.Estatus = 1) AS 'Total_Bitacora_Inventario',
    ISap.Cantidad - (SELECT COALESCE( 
        CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
        CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
        BInv.PrimerConteo 
    ) FROM Bitacora_Inventario BInv WHERE BInv.NumeroParte = ISap.GrammerNo AND BInv.StorageBin = ISap.STBin AND BInv.Estatus = 1) AS 'Diferencia'
FROM 
    InventarioSap ISap 
WHERE 
    ISap.GrammerNo IN (SELECT NumeroParte FROM Bitacora_Inventario)  
ORDER BY 
    ISap.GrammerNo ASC;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>