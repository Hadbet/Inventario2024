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
    ROUND(COALESCE(I.Cantidad, 0), 2) AS CantidadInventarioSap, 
    COALESCE(B.StorageBin, I.STBin) AS StorageBin
FROM 
    Bitacora_Inventario B
LEFT JOIN 
    InventarioSap I ON B.NumeroParte = I.GrammerNo 
    AND B.StorageBin = I.STBin 
WHERE 
    B.Area = $area
    AND B.Estatus = 1
    AND (I.GrammerNo IS NULL 
    OR I.Cantidad = 0
    OR B.PrimerConteo = 0
    OR ABS(B.PrimerConteo - IFNULL(I.Cantidad, 0)) >= 10000
    OR B.PrimerConteo != ROUND(I.Cantidad, 2))
    AND ABS(COALESCE(B.PrimerConteo, 0) - ROUND(COALESCE(I.Cantidad, 0), 2)) >= 10

UNION

SELECT 
    COALESCE(B.NumeroParte, I.GrammerNo) AS NumeroParte, 
    B.FolioMarbete,
    COALESCE(B.PrimerConteo, 0) AS CantidadBitacora, 
    ROUND(I.Cantidad, 2) AS CantidadInventarioSap, 
    COALESCE(B.StorageBin, I.STBin) AS StorageBin
FROM 
    InventarioSap I
LEFT JOIN 
    Bitacora_Inventario B ON I.GrammerNo = B.NumeroParte 
    AND I.STBin = B.StorageBin 
WHERE 
    I.AreaCve = $area
    AND (B.NumeroParte IS NULL 
    OR I.Cantidad = 0
    OR IFNULL(B.PrimerConteo, 0) = 0
    OR ABS(IFNULL(B.PrimerConteo, 0) - I.Cantidad) >= 10000
    OR B.PrimerConteo != ROUND(I.Cantidad, 2))
    AND ABS(COALESCE(B.PrimerConteo, 0) - ROUND(I.Cantidad, 2)) >= 10;");

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