<?php

include_once('db/db_Inventario.php');

$area = $_GET['area'];
ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $consulta = "SELECT 
    ISap.STBin, 
    ISap.STType, 
    ISap.GrammerNo, 
    ISap.Cantidad as Total_InventarioSap, 
    (SELECT COALESCE( 
        CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
        CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
        BInv.PrimerConteo 
    ) FROM Bitacora_Inventario BInv WHERE BInv.NumeroParte = ISap.GrammerNo AND BInv.StorageBin = ISap.STBin AND BInv.Area = ? AND BInv.Estatus = 1) AS 'Total_Bitacora_Inventario',
    ISap.Cantidad - (SELECT COALESCE( 
        CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
        CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
        BInv.PrimerConteo 
    ) FROM Bitacora_Inventario BInv WHERE BInv.NumeroParte = ISap.GrammerNo AND BInv.StorageBin = ISap.STBin AND BInv.Area = ? AND BInv.Estatus = 1) AS 'Diferencia'
FROM 
    InventarioSap ISap 
WHERE 
    ISap.GrammerNo IN (SELECT NumeroParte FROM Bitacora_Inventario WHERE Area = ?)  
ORDER BY 
    ISap.GrammerNo ASC;";

    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "iii", $area,$area,$area);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $resultado = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}

?>