<?php
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
            $NumeroParte = isset($registroBitacora['NumeroParte']) ? trim($registroBitacora['NumeroParte']) : null;
            $FolioMarbete = isset($registroBitacora['FolioMarbete']) ? trim($registroBitacora['FolioMarbete']) : null;
            $StorageBin = isset($registroBitacora['StorageBin']) ? trim($registroBitacora['StorageBin']) : null;
            $StorageType = isset($registroBitacora['StorageType']) ? trim($registroBitacora['StorageType']) : null;
            $Area = isset($registroBitacora['Area']) ? trim($registroBitacora['Area']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($FolioMarbete === null || $Area === null ) {
                $errores[] = "Faltan datos para el registro FolioMarbete: $FolioMarbete, NumeroParte: $NumeroParte, Area: $Area, StorageType: $StorageType, StorageBin: $StorageBin ";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosBitacora($NumeroParte, $FolioMarbete, $StorageBin, $StorageType, $Area);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro FolioMarbete: $FolioMarbete. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }else{
                    $detalles[] = $respuestaInsert['message'];
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Bitacora fueron actualizados correctamente.", "detalles" => $detalles);
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


function insertarRegistrosBitacora($NumeroParte, $FolioMarbete, $StorageBin, $StorageType, $Area) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    $fechaHoy = date('Y-m-d H:i:s');

    if($NumeroParte === null){
        $NumeroParte = '';
    }
    if($StorageBin === null){
        $StorageBin = '';
    }
    if($StorageType === null){
        $StorageType = '';
    }

    if (empty($FolioMarbete)) {
        return array('status' => 'error', 'message' => 'El FolioMarbete es obligatorio.');
    }

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Bitacora_Inventario` WHERE `FolioMarbete` = ?");
        $consultaExistente->bind_param("s", $FolioMarbete);
        $consultaExistente->execute();
        $consultaExistente->store_result();
        error_log("Número de filas encontradas: " . $consultaExistente->num_rows);


        if ($consultaExistente->num_rows > 0) {
            error_log("Actualizando registro con FolioMarbete: $FolioMarbete");
            // Si ya existe, se actualiza el registro
            $updateBitacora = $conex->prepare("UPDATE `Bitacora_Inventario` SET `NumeroParte` = ?, `StorageBin` = ?, `StorageType` = ?, `Area` = ?, `Fecha` = ? WHERE `FolioMarbete` = ?");
            $updateBitacora->bind_param("ssssss", $NumeroParte, $StorageBin, $StorageType, $Area, $fechaHoy,$FolioMarbete );
            $resultado = $updateBitacora->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con FolioMarbete: ' . $FolioMarbete);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateBitacora->close();

        } else {
            error_log("Insertando registro con FolioMarbete: $FolioMarbete");
            // Si no existe, insertar el nuevo registro
            $insertBitacora = $conex->prepare("INSERT INTO `Bitacora_Inventario` (`NumeroParte`, `StorageBin`, `StorageType`, `Area`, `Fecha`, `FolioMarbete`) 
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $insertBitacora->bind_param("ssssss", $NumeroParte, $StorageBin, $StorageType, $Area, $fechaHoy, $FolioMarbete);

            $resultado = $insertBitacora->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con FolioMarbete: ' . $FolioMarbete);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
            }

            $insertBitacora->close();
        }

        $consultaExistente->close();

    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}
?>