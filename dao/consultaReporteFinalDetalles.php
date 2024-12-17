<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    SUM(
        CASE 
            WHEN ISap.Cantidad > COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) OR BInv.NumeroParte IS NULL THEN 1
            ELSE 0
        END
    ) AS 'Cantidad_Total_Negativa',
    SUM(
        CASE 
            WHEN ISap.Cantidad < COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) AND BInv.Estatus = 1 THEN 1
            ELSE 0
        END
    ) AS 'Cantidad_Total_Positiva',
    SUM(
        CASE 
            WHEN (ISap.Cantidad * (Parte.Por / Parte.Costo)) > (COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) * (Parte.Por / Parte.Costo)) AND BInv.Estatus = 1 THEN (ISap.Cantidad * (Parte.Por / Parte.Costo)) - (COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) * (Parte.Por / Parte.Costo))
            ELSE 0
        END
    ) AS 'Costo_Total_Negativo',
    SUM(
        CASE 
            WHEN (ISap.Cantidad * (Parte.Por / Parte.Costo)) < (COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) * (Parte.Por / Parte.Costo)) AND BInv.Estatus = 1 THEN (COALESCE( 
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END, 
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END, 
                BInv.PrimerConteo 
            , 0.00) * (Parte.Por / Parte.Costo)) - (ISap.Cantidad * (Parte.Por / Parte.Costo))
            ELSE 0
        END
    ) AS 'Costo_Total_Positivo'
FROM 
    InventarioSap ISap
LEFT JOIN 
    Bitacora_Inventario BInv ON ISap.GrammerNo = BInv.NumeroParte AND ISap.STBin = BInv.StorageBin
JOIN 
    Parte ON ISap.GrammerNo = Parte.GrammerNo;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>