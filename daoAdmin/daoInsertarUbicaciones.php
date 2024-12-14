<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['ubicacionesDatos']) && is_array($inputData['ubicacionesDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['ubicacionesDatos'] as $registroUbicaciones) {
            // Validar y asignar valores
            $GrammerNo = isset($registroUbicaciones['GrammerNo']) ? trim($registroUbicaciones['GrammerNo']) : null;
            $PVB = isset($registroUbicaciones['PVB']) ? trim($registroUbicaciones['PVB']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($GrammerNo === null || $PVB === null  ) {
                $errores[] = "Faltan datos para el registro GrammerNo: $GrammerNo, PVB: $PVB";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosUbicaciones($GrammerNo, $PVB);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro GrammerNo: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Ubicaciones fueron actualizados correctamente.");
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


function insertarRegistrosUbicaciones($GrammerNo, $PVB) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Ubicaciones` WHERE `GrammerNo` = ? ");
        $consultaExistente->bind_param("s", $GrammerNo);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateInventario = $conex->prepare("UPDATE `Ubicaciones` SET `PVB` = ? WHERE `GrammerNo` = ? ");
            $updateInventario->bind_param("ss",  $PVB, $GrammerNo);
            $resultado = $updateInventario->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con GrammerNo: ' . $GrammerNo . ', PVB: '. $PVB );
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateInventario->close();

        } else {
            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO  `Ubicaciones` (`GrammerNo`, `PVB`) VALUES (?, ?)");
            $insertParte->bind_param("ss",  $GrammerNo, $PVB);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con GrammerNo: ' . $GrammerNo . ', PVB: '. $PVB );
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