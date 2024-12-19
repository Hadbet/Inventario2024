<?php
include_once('connection.php');

header('Content-Type: application/json');

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

foreach ($data['storageUnits'] as $record) {
    $storageUnit = mysqli_real_escape_string($conexion, $record['storUnit']);

    $consP = "SELECT Storage_Unit.Numero_Parte,Storage_Unit.Storage_Bin,Storage_Unit.Storage_Type,Storage_Unit.Cantidad, InvSap.InventoryItem, InvSap.Plant,InvSap.InvRecount
    FROM Storage_Unit JOIN InvSap ON Storage_Unit.Id_StorageUnit = InvSap.storUnitType WHERE Storage_Unit.Id_StorageUnit = '$storageUnit' AND Storage_Unit.Estatus = 1";
    $rsconsPro = mysqli_query($conexion, $consP);

    if ($rsconsPro) {
        if ($row = mysqli_fetch_assoc($rsconsPro)) {
            $updatedData[] = [
                'storageUnit' => $storageUnit,
                'cantidad' => $row['Cantidad'],
                'inventoryItem' => $row['InventoryItem'],
                'plan' => $row['Plant'],
                'storage_Type' => $row['Storage_Type'],
                'storage_Bin' => $row['Storage_Bin'],
                'invRecount' => $row['InvRecount'],
                'numero_Parte' => $row['Numero_Parte']
            ];
        } else {
            // If no results, assign default values
            $updatedData[] = [
                'storageUnit' => $storageUnit,
                'cantidad' => '0',
                'inventoryItem' => '',
                'plan' => '',
                'storage_Type' => '',
                'storage_Bin' => '',
                'invRecount' => '',
                'numero_Parte' => ''
            ];
        }
    } else {
        // Si ocurre un error en la consulta, registrar el error
        $updatedData[] = [
            'storageUnit' => $storageUnit,
            'error' => mysqli_error($conexion)
        ];
    }
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>