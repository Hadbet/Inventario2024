<?php

include_once('db/db_Inventario.php');

$area = $_GET['area'];
ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $consulta = "SELECT 
    P.GrammerNo, 
    P.Descripcion, 
    P.UM, 
    P.Costo / P.Por AS 'Costo_Unitario', 
    SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Total_Bitacora_Inventario', 
    ISap.Total_Cantidad AS 'Total_InventarioSap', 
    ISap.Total_Cantidad - SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Diferencia'
FROM 
    Parte P
INNER JOIN 
    (SELECT GrammerNo, SUM(Cantidad) AS Total_Cantidad FROM InventarioSap GROUP BY GrammerNo) ISap ON P.GrammerNo = ISap.GrammerNo
LEFT JOIN 
    Bitacora_Inventario BInv ON P.GrammerNo = BInv.NumeroParte AND BInv.Estatus = 1
WHERE 
    BInv.Area = ?
GROUP BY 
    P.GrammerNo;";

    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $area);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $resultado = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}

?>