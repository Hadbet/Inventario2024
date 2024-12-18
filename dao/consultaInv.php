<?php
include_once('db/db_Inventario.php');
ContadorApu();

function ContadorApu() {
    $con = new LocalConector();
    $conex = $con->conectar();

    $query = "(SELECT 
    T.InventoryItem, 
    T.StorageType, 
    T.StorageBin, 
    T.NumeroParte, 
    T.Plant, 
    T.Cantidad, 
    T.StorageUnit, 
    InvSap.InvRecount
FROM 
    ( SELECT 
        (SELECT InventoryItem FROM InvSap WHERE StorageType = Storage_Unit.Storage_Type and StorageBin = Storage_Unit.Storage_Bin LIMIT 1) AS InventoryItem, 
        Storage_Unit.Storage_Type AS StorageType, 
        Storage_Unit.Storage_Bin AS StorageBin, 
        Storage_Unit.Numero_Parte AS NumeroParte, 
        (SELECT Plant FROM InvSap WHERE StorageBin = Storage_Unit.Storage_Bin and StorageType = Storage_Unit.Storage_Type LIMIT 1) AS Plant, 
        Storage_Unit.Cantidad AS Cantidad, 
        Storage_Unit.Id_StorageUnit AS StorageUnit 
    FROM 
        Storage_Unit 
    LEFT JOIN 
        InvSap ON Storage_Unit.Numero_Parte = InvSap.Material AND Storage_Unit.Storage_Bin = InvSap.StorageBin AND Storage_Unit.Storage_Type = InvSap.StorageType 
    WHERE 
        InvSap.Material IS NULL 
    ORDER BY 
        Storage_Unit.Storage_Bin) AS T 
LEFT JOIN 
    InvSap ON T.StorageBin = InvSap.StorageBin AND T.StorageType = InvSap.StorageType
WHERE 
    T.InventoryItem IS NOT NULL AND T.InventoryItem != '')

UNION 

(SELECT 
    T.InventoryItem, 
    T.StorageType, 
    T.StorageBin, 
    T.NumeroParte, 
    T.Plant, 
    T.Cantidad, 
    NULL AS StorageUnit, 
    InvSap.InvRecount
FROM 
    ( SELECT 
        (SELECT `InventoryItem` FROM `InvSap` WHERE `StorageType` = Bitacora_Inventario.StorageType and `StorageBin` = Bitacora_Inventario.StorageBin LIMIT 1) AS InventoryItem, 
        Bitacora_Inventario.StorageType AS StorageType, 
        Bitacora_Inventario.StorageBin AS StorageBin, 
        Bitacora_Inventario.NumeroParte AS NumeroParte, 
        (SELECT `Plant` FROM `InvSap` WHERE `StorageBin` = Bitacora_Inventario.StorageBin and `StorageType` = Bitacora_Inventario.StorageType LIMIT 1) AS Plant, 
        CASE 
            WHEN Bitacora_Inventario.TercerConteo IS NOT NULL THEN Bitacora_Inventario.TercerConteo 
            WHEN Bitacora_Inventario.SegundoConteo IS NOT NULL THEN Bitacora_Inventario.SegundoConteo 
            ELSE Bitacora_Inventario.PrimerConteo 
        END AS Cantidad 
    FROM 
        Bitacora_Inventario 
    LEFT JOIN 
        InvSap ON Bitacora_Inventario.NumeroParte = InvSap.Material AND Bitacora_Inventario.StorageBin = InvSap.StorageBin AND Bitacora_Inventario.StorageType = InvSap.StorageType 
    WHERE 
        InvSap.Material IS NULL ) AS T 
LEFT JOIN 
    InvSap ON T.StorageBin = InvSap.StorageBin AND T.StorageType = InvSap.StorageType
WHERE 
    T.InventoryItem IS NOT NULL AND T.InventoryItem != '');";

    $datos = mysqli_query($conex, $query);
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>