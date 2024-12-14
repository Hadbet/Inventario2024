<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['inventarioDatos']) && is_array($inputData['inventarioDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['inventarioDatos'] as $registroInventario) {
            // Validar y asignar valores
            $STLocation = isset($registroInventario['STLocation']) ? trim($registroInventario['STLocation']) : null;
            $StBin = isset($registroInventario['StBin']) ? trim($registroInventario['StBin']) : null;
            $StType = isset($registroInventario['StType']) ? trim($registroInventario['StType']) : null;
            $GrammerNo = isset($registroInventario['GrammerNo']) ? trim($registroInventario['GrammerNo']) : null;
            $Cantidad = isset($registroInventario['Cantidad']) ? trim($registroInventario['Cantidad']) : null;
            $AreaCve = isset($registroInventario['AreaCve']) ? trim($registroInventario['AreaCve']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($GrammerNo === null || $STLocation === null || $Cantidad === null || $AreaCve === null) {
                $errores[] = "Faltan datos para el registro GrammerNo: $GrammerNo, STLocation: $STLocation, StBin: $StBin, StType: $StType, Cantidad: $Cantidad, AreaCve: $AreaCve ";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro ID: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla InventarioSAP fueron actualizados correctamente.");
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


function insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    if($StBin === null){
        $StBin = "";
    }

    if($StType === null){
        $StType = "";
    }

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `InventarioSap` WHERE `GrammerNo` = ? AND StBin = ? AND StType = ? AND STLocation = ?");
        $consultaExistente->bind_param("sssi", $GrammerNo,$StBin, $StType, $STLocation);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateInventario = $conex->prepare("UPDATE `InventarioSap` SET `Cantidad` = ?, `AreaCve` = ? WHERE `GrammerNo` = ? AND StBin = ? AND StType = ? AND STLocation = ?");
            $updateInventario->bind_param("sssssi",  $Cantidad, $AreaCve, $GrammerNo, $StBin, $StType, $STLocation);
            $resultado = $updateInventario->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con GrammerNo: ' . $GrammerNo . ', StBin: '. $StBin . ', StType:'. $StType .', STLocation: '.$STLocation);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateInventario->close();

        } else {

            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO  `InventarioSap` (`STLocation`, `STBin`, `STType`, `GrammerNo`, `Cantidad`, `AreaCve`)
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $insertParte->bind_param("isssss", $STLocation, $StBin, $StType, $GrammerNo, $Cantidad, $AreaCve);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con GrammerNo: ' . $GrammerNo. ', StBin: '. $StBin . ', StType:'. $StType .', STLocation: '.$STLocation);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
            }

            $insertParte->close();
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