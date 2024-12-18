<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    // Consultas
    $queryCostoNegativo = "
        SELECT ROUND(SUM(i.Cantidad * (p.Costo / p.Por)), 2) AS Costo_Total_Negativo
        FROM InventarioSap i
        JOIN Parte p ON i.GrammerNo = p.GrammerNo;
    ";

    $queryCostoPositivo = "
        SELECT ROUND(SUM(
            CASE 
                WHEN (SegundoConteo IS NULL OR SegundoConteo = 0) AND (TercerConteo IS NULL OR TercerConteo = 0) THEN PrimerConteo
                WHEN (TercerConteo IS NULL OR TercerConteo = 0) AND (SegundoConteo IS NOT NULL AND SegundoConteo != 0) THEN SegundoConteo
                WHEN (TercerConteo IS NOT NULL AND TercerConteo != 0) THEN TercerConteo
            END * (p.Costo / p.Por)), 2) AS Costo_Total_Positivo
        FROM Bitacora_Inventario b
        JOIN Parte p ON p.GrammerNo = b.NumeroParte
        WHERE b.Estatus = 1;
    ";

    $queryCantidadNegativa = "
        SELECT ROUND(SUM(i.Cantidad), 2) AS Cantidad_Total_Negativa
        FROM InventarioSap i
        JOIN Parte p ON i.GrammerNo = p.GrammerNo;
    ";

    $queryCantidadPositiva = "
        SELECT ROUND(SUM(
            CASE 
                WHEN (SegundoConteo IS NULL OR SegundoConteo = 0) AND (TercerConteo IS NULL OR TercerConteo = 0) THEN PrimerConteo
                WHEN (TercerConteo IS NULL OR TercerConteo = 0) AND (SegundoConteo IS NOT NULL AND SegundoConteo != 0) THEN SegundoConteo
                WHEN (TercerConteo IS NOT NULL AND TercerConteo != 0) THEN TercerConteo
            END), 2) AS Cantidad_Total_Positiva
        FROM Bitacora_Inventario b
        JOIN Parte p ON p.GrammerNo = b.NumeroParte
        WHERE b.Estatus = 1;
    ";

    // Ejecutar las consultas
    $resultCostoNegativo = mysqli_query($conex, $queryCostoNegativo);
    $resultCostoPositivo = mysqli_query($conex, $queryCostoPositivo);
    $resultCantidadNegativa = mysqli_query($conex, $queryCantidadNegativa);
    $resultCantidadPositiva = mysqli_query($conex, $queryCantidadPositiva);

    // Verificar errores en las consultas
    if (!$resultCostoNegativo || !$resultCostoPositivo || !$resultCantidadNegativa || !$resultCantidadPositiva) {
        echo json_encode(["error" => "Error en las consultas SQL"]);
        return;
    }

    // Obtener los resultados
    $costoNegativo = mysqli_fetch_assoc($resultCostoNegativo)['Costo_Total_Negativo'] ?? 0;
    $costoPositivo = mysqli_fetch_assoc($resultCostoPositivo)['Costo_Total_Positivo'] ?? 0;
    $cantidadNegativa = mysqli_fetch_assoc($resultCantidadNegativa)['Cantidad_Total_Negativa'] ?? 0;
    $cantidadPositiva = mysqli_fetch_assoc($resultCantidadPositiva)['Cantidad_Total_Positiva'] ?? 0;

    // Devolver resultados en JSON
    echo json_encode([
        "data" => [
            [
                "Cantidad_Total_Negativa" => $cantidadNegativa,
                "Cantidad_Total_Positiva" => $cantidadPositiva,
                "Costo_Total_Negativo" => $costoNegativo,
                "Costo_Total_Positivo" => $costoPositivo
            ]
        ]
    ]);
}



/*

"SELECT
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
    Parte ON ISap.GrammerNo = Parte.GrammerNo;"


    */
?>