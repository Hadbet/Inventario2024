<?php

include_once('db/db_Inventario.php');

$area = $_GET['area'];
ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $consulta = "SELECT 
    ISap.GrammerNo, 
    ISap.STBin, 
    SUM(ISap.Cantidad) AS 'Total_InventarioSap', 
    SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Total_Bitacora_Inventario', 
    SUM(ISap.Cantidad) - SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Diferencia'
FROM 
    InventarioSap ISap
LEFT JOIN 
    Bitacora_Inventario BInv ON ISap.GrammerNo = BInv.NumeroParte AND ISap.STBin = BInv.StorageBin AND BInv.Estatus = 1
    WHERE BInv.Area = ?
GROUP BY 
    ISap.GrammerNo, 
    ISap.STBin;";

    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $area);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $resultado = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}

?>