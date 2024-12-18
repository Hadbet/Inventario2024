<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
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
    SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    )-ISap.Total_Cantidad AS 'Diferencia'
FROM 
    Parte P
INNER JOIN 
    (SELECT GrammerNo, SUM(Cantidad) AS Total_Cantidad FROM InventarioSap GROUP BY GrammerNo) ISap ON P.GrammerNo = ISap.GrammerNo
LEFT JOIN 
    Bitacora_Inventario BInv ON P.GrammerNo = BInv.NumeroParte AND BInv.Estatus = 1
GROUP BY 
    P.GrammerNo;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>