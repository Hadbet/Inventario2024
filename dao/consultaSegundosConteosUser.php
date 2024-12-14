<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    COALESCE(B.NumeroParte, I.GrammerNo) AS NumeroParte, 
    B.FolioMarbete,
    COALESCE(B.PrimerConteo, 0) AS CantidadBitacora, 
    COALESCE(I.Cantidad, 0) AS CantidadInventarioSap, 
    COALESCE(B.StorageBin, I.STBin) AS StorageBin,
    P.Costo / P.Por AS CostoUnitario,
    (P.Costo / P.Por) * COALESCE(B.PrimerConteo, 0) AS CostoBitacora,
    (P.Costo / P.Por) * COALESCE(I.Cantidad, 0) AS CostoInventarioSap
FROM 
    Bitacora_Inventario B
LEFT JOIN 
    InventarioSap I ON B.NumeroParte = I.GrammerNo 
    AND B.StorageBin = I.STBin 
LEFT JOIN
    Parte P ON COALESCE(B.NumeroParte, I.GrammerNo) = P.GrammerNo
WHERE 
    B.Area = $area
    AND (B.SegFolio = 2 OR B.SegFolio IS NULL AND (
        B.Estatus = 1
        AND I.GrammerNo IS NOT NULL 
        AND B.PrimerConteo = I.Cantidad
        AND I.Cantidad != 0
        AND B.PrimerConteo != 0
        AND ABS(B.PrimerConteo - IFNULL(I.Cantidad, 0)) < 10000
        AND ABS((P.Costo / P.Por) * COALESCE(I.Cantidad, 0) - (P.Costo / P.Por) * COALESCE(B.PrimerConteo, 0)) <= 3000
        AND ABS(COALESCE(I.Cantidad, 0) - COALESCE(B.PrimerConteo, 0)) <= 100
    ))

UNION

SELECT 
    COALESCE(B.NumeroParte, I.GrammerNo) AS NumeroParte, 
    B.FolioMarbete,
    COALESCE(B.PrimerConteo, 0) AS CantidadBitacora, 
    I.Cantidad AS CantidadInventarioSap, 
    COALESCE(B.StorageBin, I.STBin) AS StorageBin,
    P.Costo / P.Por AS CostoUnitario,
    (P.Costo / P.Por) * IFNULL(B.PrimerConteo, 0) AS CostoBitacora,
    (P.Costo / P.Por) * I.Cantidad AS CostoInventarioSap
FROM 
    InventarioSap I
LEFT JOIN 
    Bitacora_Inventario B ON I.GrammerNo = B.NumeroParte 
    AND I.STBin = B.StorageBin 
LEFT JOIN
    Parte P ON COALESCE(B.NumeroParte, I.GrammerNo) = P.GrammerNo
WHERE 
    I.AreaCve = $area
    AND (B.SegFolio = 2 OR B.SegFolio IS NULL AND (
        B.NumeroParte IS NOT NULL 
        AND B.PrimerConteo = I.Cantidad
        AND I.Cantidad != 0
        AND IFNULL(B.PrimerConteo, 0) != 0
        AND ABS(IFNULL(B.PrimerConteo, 0) - I.Cantidad) < 10000
        AND ABS((P.Costo / P.Por) * I.Cantidad - (P.Costo / P.Por) * IFNULL(B.PrimerConteo, 0)) <= 3000
        AND ABS(I.Cantidad - COALESCE(B.PrimerConteo, 0)) <= 100
    ));");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);

    foreach($resultado as $row) {
        $folioMarbete = $row['FolioMarbete'];
        if($folioMarbete != null) {
            $updateQuery = "UPDATE `Bitacora_Inventario` SET `SegFolio`='2' WHERE `FolioMarbete` = '$folioMarbete'";
            if(!mysqli_query($conex, $updateQuery)){
                echo "Error updating record: " . mysqli_error($conex);
                return; // Si hay un error, detiene la ejecución
            }
        }
    }

    // Aquí es donde puedes agregar tu segunda consulta UPDATE
    $updateQuery = "UPDATE Area SET Conteo='2' WHERE IdArea = '$area'";
    if(mysqli_query($conex, $updateQuery)){
        echo json_encode(array("data" => $resultado));
    } else {
        echo "Error updating record: " . mysqli_error($conex);
    }

}


?>