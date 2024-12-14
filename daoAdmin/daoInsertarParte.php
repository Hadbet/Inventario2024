<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['parteDatos']) && is_array($inputData['parteDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['parteDatos'] as $registroParte) {
            // Validar y asignar valores
            $GrammerNo = isset($registroParte['GrammerNo']) ? trim($registroParte['GrammerNo']) : null;
            $Descripcion = isset($registroParte['Descripcion']) ? trim($registroParte['Descripcion']) : null;
            $UM = isset($registroParte['UM']) ? trim($registroParte['UM']) : null;
            $ProfitCtr = isset($registroParte['ProfitCtr']) ? trim($registroParte['ProfitCtr']) : null;
            $Costo = isset($registroParte['Costo']) ? trim($registroParte['Costo']) : null;
            $Por = isset($registroParte['Por']) ? trim($registroParte['Por']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($GrammerNo === null || $Descripcion === null || $UM === null || $ProfitCtr === null || $Costo === null || $Por === null) {
                $errores[] = "Faltan datos para el registro GrammerNo: $GrammerNo, Descripcion: $Descripcion, UM: $UM, ProfitCtr: $ProfitCtr, Costo: $Costo, Por: $Por ";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro ID: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Parte fueron actualizados correctamente.");
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


function insertarRegistrosParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Parte` WHERE `GrammerNo` = ?");
        $consultaExistente->bind_param("s", $GrammerNo);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateParte = $conex->prepare("UPDATE `Parte` SET `Descripcion` = ?, `UM` = ?, `ProfitCtr` = ?, `Costo` = ?, `Por` = ? WHERE `GrammerNo` = ?");
            $updateParte->bind_param("ssssis", $Descripcion, $UM, $ProfitCtr, $Costo, $Por, $GrammerNo);
            $resultado = $updateParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con GrammerNo: ' . $GrammerNo);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateParte->close();

        } else {
            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO `Parte` (`GrammerNo`, `Descripcion`, `UM`, `ProfitCtr`, `Costo`, `Por`) 
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $insertParte->bind_param("sssssi", $GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con GrammerNo: ' . $GrammerNo);
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