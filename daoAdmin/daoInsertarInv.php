<?php
set_time_limit(0); // Establece el límite de tiempo de ejecución del script en segundos (0 significa sin límite)
ini_set('memory_limit', '-1');
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['bitacoraDatos']) && is_array($inputData['bitacoraDatos'])) {
        $todosExitosos = true;
        $errores = [];
        $detalles = [];

        foreach ($inputData['bitacoraDatos'] as $registroBitacora) {
            // Validar y asignar valores
            $Client = isset($registroBitacora['Client']) ? trim($registroBitacora['Client']) : '';
            $WarehouseNo = isset($registroBitacora['WarehouseNo']) ? trim($registroBitacora['WarehouseNo']) : '';
            $InventoryItem = isset($registroBitacora['InventoryItem']) ? trim($registroBitacora['InventoryItem']) : '';
            $Quant = isset($registroBitacora['Quant']) ? trim($registroBitacora['Quant']) : '';
            $InvRecount = isset($registroBitacora['InvRecount']) ? trim($registroBitacora['InvRecount']) : '';
            $InventStatus = isset($registroBitacora['InventStatus']) ? trim($registroBitacora['InventStatus']) : '';
            $InventoryPage = isset($registroBitacora['InventoryPage']) ? trim($registroBitacora['InventoryPage']) : '';
            $StorageType = isset($registroBitacora['StorageType']) ? trim($registroBitacora['StorageType']) : '';
            $StorageBin = isset($registroBitacora['StorageBin']) ? trim($registroBitacora['StorageBin']) : '';
            $BinPosition = isset($registroBitacora['BinPosition']) ? trim($registroBitacora['BinPosition']) : '';
            $Material = isset($registroBitacora['Material']) ? trim($registroBitacora['Material']) : '';
            $Plant = isset($registroBitacora['Plant']) ? trim($registroBitacora['Plant']) : '';
            $Batch = isset($registroBitacora['Batch']) ? trim($registroBitacora['Batch']) : '';
            $StorUnitType = isset($registroBitacora['StorUnitType']) ? trim($registroBitacora['StorUnitType']) : '';
            $TotalStock = isset($registroBitacora['TotalStock']) ? trim($registroBitacora['TotalStock']) : '';
            $Invent = isset($registroBitacora['Invent']) ? trim($registroBitacora['Invent']) : '';
            $TransferOrder = isset($registroBitacora['TransferOrder']) ? trim($registroBitacora['TransferOrder']) : '';
            $TransferItem = isset($registroBitacora['TransferItem']) ? trim($registroBitacora['TransferItem']) : '';
            $StorageLocation = isset($registroBitacora['StorageLocation']) ? trim($registroBitacora['StorageLocation']) : '';
            $NameCounter = isset($registroBitacora['NameCounter']) ? trim($registroBitacora['NameCounter']) : '';

            // Llamar a la función de inserción
            $respuestaInsert = insertarRegistrosBitacora($Client, $WarehouseNo, $InventoryItem, $Quant, $InvRecount, $InventStatus, $InventoryPage, $StorageType, $StorageBin, $BinPosition, $Material, $Plant, $Batch, $StorUnitType, $TotalStock, $Invent, $TransferOrder, $TransferItem, $StorageLocation, $NameCounter);
            if ($respuestaInsert['status'] !== 'success') {
                $errores[] = "Error al insertar el registro Client: $Client. " . $respuestaInsert['message'];
                $todosExitosos = false;
                break;  // Salir del ciclo si ocurre un error
            }else{
                $detalles[] = $respuestaInsert['message'];
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla InvSap fueron insertados correctamente.", "detalles" => $detalles);
        } else {
            $respuesta = array("status" => 'error', "message" => "Se encontraron errores al insertar los registros.", "detalles" => $errores);
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);

function insertarRegistrosBitacora($Client, $WarehouseNo, $InventoryItem, $Quant, $InvRecount, $InventStatus, $InventoryPage, $StorageType, $StorageBin, $BinPosition, $Material, $Plant, $Batch, $StorUnitType, $TotalStock, $Invent, $TransferOrder, $TransferItem, $StorageLocation, $NameCounter) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Insertar el nuevo registro
        $insertBitacora = $conex->prepare("INSERT INTO `InvSap` (`Client`, `WarehouseNo`, `InventoryItem`, `Quant`, `InvRecount`, `InventStatus`, `InventoryPage`, `StorageType`, `StorageBin`, `BinPosition`, `Material`, `Plant`, `Batch`, `StorUnitType`, `TotalStock`, `Invent`, `TransferOrder`, `TransferItem`, `StorageLocation`, `NameCounter`) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertBitacora->bind_param("ssssssssssssssssssss", $Client, $WarehouseNo, $InventoryItem, $Quant, $InvRecount, $InventStatus, $InventoryPage, $StorageType, $StorageBin, $BinPosition, $Material, $Plant, $Batch, $StorUnitType, $TotalStock, $Invent, $TransferOrder, $TransferItem, $StorageLocation, $NameCounter);

        $resultado = $insertBitacora->execute();

        if (!$resultado) {
            $conex->rollback();
            $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con Client: ' . $Client);
        } else {
            $conex->commit();
            $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
        }

        $insertBitacora->close();

    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}
?>