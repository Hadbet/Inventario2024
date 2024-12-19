<?php
include_once('connection.php');

// Configurar el encabezado para una respuesta JSON
header('Content-Type: application/json');

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data)) {
    echo json_encode(['error' => 'No se recibieron datos']);
    exit();
}

$con = new LocalConector();
$conexion = $con->conectar();

if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}

$updatedData = [];

foreach ($data as $record) {
    $stor_bin = mysqli_real_escape_string($conexion, $record['storBin']);
    $materialParte = mysqli_real_escape_string($conexion, $record['materialNo']);
    $consP = "SELECT 
    InvSap.InventoryItem, 
    InvSap.InvRecount, 
    InvSap.StorageType,
    InvSap.StorageBin,
    InvSap.Plant,
    InvSap.Material,
    CASE 
        WHEN (Bitacora_Inventario.SegundoConteo IS NULL OR Bitacora_Inventario.SegundoConteo = 0) AND (Bitacora_Inventario.TercerConteo IS NULL OR Bitacora_Inventario.TercerConteo = 0) THEN Bitacora_Inventario.PrimerConteo 
        WHEN (Bitacora_Inventario.TercerConteo IS NULL OR Bitacora_Inventario.TercerConteo = 0) AND (Bitacora_Inventario.SegundoConteo IS NOT NULL AND Bitacora_Inventario.SegundoConteo != 0) THEN Bitacora_Inventario.SegundoConteo 
        WHEN (Bitacora_Inventario.TercerConteo IS NOT NULL AND Bitacora_Inventario.TercerConteo != 0) THEN Bitacora_Inventario.TercerConteo 
    END AS ConteoFinal
FROM 
    InvSap 
JOIN 
    Bitacora_Inventario 
ON 
    InvSap.Material = Bitacora_Inventario.NumeroParte AND 
    InvSap.StorageBin = Bitacora_Inventario.StorageBin
WHERE 
    InvSap.Material = '$materialParte' AND 
    InvSap.StorageBin = '$stor_bin' AND 
    Bitacora_Inventario.NumeroParte = '$materialParte' AND 
    Bitacora_Inventario.StorageBin = '$stor_bin';
    Bitacora_Inventario.Estatus = '1';";

    $rsconsPro = mysqli_query($conexion, $consP); // Ejecutar la consulta

    if ($rsconsPro) {
        if ($row = mysqli_fetch_assoc($rsconsPro)) {
            $updatedData[] = [
                'inventoryItem' => $row['InventoryItem'],
                'invRecount' => $row['InvRecount'],
                'storageType' => $row['StorageType'],
                'plant' => $row['Plant'],
                'conteoFinal' => $row['ConteoFinal'],
                'material' => $row['Material'],
                'storageBin' => $row['StorageBin']
            ];
        } else {
            // Si no hay resultados, asignar valores predeterminados
            $updatedData[] = [
                'inventoryItem' => '0',
                'invRecount' => '0',
                'storageType' => '0',
                'plant' => '0',
                'conteoFinal' => '0',
                'material' => '0',
                'storageBin' => '0'
            ];
        }
    } else {
        // Si ocurre un error en la consulta, registrar el error
        $updatedData[] = [
            'storBin' => $stor_bin,
            'materialNo' => $materialParte,
            'error' => mysqli_error($conexion)
        ];
    }
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>