<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['binDatos']) && is_array($inputData['binDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['binDatos'] as $registroBin) {
            // Validar y asignar valores
            $StBin = isset($registroBin['StBin']) ? trim($registroBin['StBin']) : null;
            $StType = isset($registroBin['StType']) ? trim($registroBin['StType']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($StBin === null || $StType === null) {
                $errores[] = "Faltan datos para el registro StBin: $StBin, StType: $StType";
                $todosExitosos = false;
                break;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosBin($StBin, $StType);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro StBin: $StBin. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Bin fueron actualizados correctamente.");
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
function insertarRegistrosBin($StBin, $StType) {
    $con = new LocalConector();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Bin` WHERE `StBin` = ?");
        $consultaExistente->bind_param("s", $StBin);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateBin = $conex->prepare("UPDATE `Bin` SET `StType` = ? WHERE `StBin` = ?");
            $updateBin->bind_param("ss", $StType, $StBin);
            $resultado = $updateBin->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con StBin: ' . $StBin);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateBin->close();
        } else {
            // Si no existe, se inserta un nuevo registro
            $insertBin = $conex->prepare("INSERT INTO `Bin` (`StBin`, `StType`) VALUES (?, ?)");
            $insertBin->bind_param("ss", $StBin, $StType);

            $resultado = $insertBin->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con StBin: ' . $StBin);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
            }

            $insertBin->close();
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