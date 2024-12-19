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
    InvSap.Plant,
    InvSap.Material,
    Bitacora_Inventario.ConteoFinal
FROM 
    InvSap
INNER JOIN 
    (SELECT 
        CASE 
            WHEN (SegundoConteo IS NULL OR SegundoConteo = 0) AND (TercerConteo IS NULL OR TercerConteo = 0) THEN PrimerConteo 
            WHEN (TercerConteo IS NULL OR TercerConteo = 0) AND (SegundoConteo IS NOT NULL AND SegundoConteo != 0) THEN SegundoConteo 
            WHEN (TercerConteo IS NOT NULL AND TercerConteo != 0) THEN TercerConteo 
        END AS ConteoFinal, 
        NumeroParte, 
        StorageBin 
     FROM 
        Bitacora_Inventario 
     WHERE 
        StorageBin = 'PVB_002' AND 
        NumeroParte = '10720743' AND 
        Estatus = 1) AS Bitacora_Inventario
ON 
    InvSap.Material = Bitacora_Inventario.NumeroParte AND 
    InvSap.StorageBin = Bitacora_Inventario.StorageBin;";
    $rsconsPro = mysqli_query($conexion, $consP);

    if ($rsconsPro) {
        if ($row = mysqli_fetch_assoc($rsconsPro)) {
            $updatedData[] = [
                'inventoryItem' => $row['InventoryItem'],
                'invRecount' => $row['InvRecount'],
                'storageType' => $row['StorageType'],
                'plant' => $row['Plant'],
                'conteoFinal' => $row['ConteoFinal'],
                'material' => $row['Material']
            ];
        } else {
            // Si no hay resultados, asignar valores predeterminados
            $updatedData[] = [
                'inventoryItem' => '0',
                'invRecount' => '0',
                'storageType' => '0',
                'plant' => '0',
                'conteoFinal' => '0',
                'material' => '0'
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