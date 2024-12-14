<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['areaDatos']) && is_array($inputData['areaDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['areaDatos'] as $registroArea) {
            // Validar y asignar valores
            $AreaNombre = isset($registroArea['AreaNombre']) ? trim($registroArea['AreaNombre']) : null;
            $AreaProduccion = isset($registroArea['AreaProduccion']) ? trim($registroArea['AreaProduccion']) : null;
            $StLocation = isset($registroArea['StLocation']) ? trim($registroArea['StLocation']) : null;
            $StBin = isset($registroArea['StBin']) ? trim($registroArea['StBin']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ( $AreaNombre === null || $AreaProduccion === null) {
                $errores[] = "Faltan datos para el registro AreaNombre: $AreaNombre, AreaProduccion: $AreaProduccion, StLocation: $StLocation, StBin: $StBin";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosArea($AreaNombre, $AreaProduccion, $StLocation, $StBin);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro AreaNombre: $AreaNombre. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }
        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Area fueron actualizados correctamente.");
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

function insertarRegistrosArea($AreaNombre, $AreaProduccion, $StLocation, $StBin){
    $con = new LocalConector();
    $conex = $con->conectar();
    $conex->begin_transaction();

    if($StBin === null){
        $StBin = "";
    }

    if($StLocation === null){
        $StLocation = "";
    }

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Area` WHERE `AreaNombre` = ?");
        $consultaExistente->bind_param("s", $AreaNombre);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateParte = $conex->prepare("UPDATE `Area` SET `AreaProduccion` = ?, `StLocation` = ?, `StBin` = ? WHERE `AreaNombre` = ?");
            $updateParte->bind_param("isss",  $AreaProduccion, $StLocation, $StBin, $AreaNombre);
            $resultado = $updateParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con AreaNombre: ' . $AreaNombre);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }
            $updateParte->close();

        } else {

            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO `Area` ( `AreaNombre`, `AreaProduccion`, `StLocation`, `StBin`) 
                                                       VALUES ( ?, ?, ?, ?)");
            $insertParte->bind_param("siss", $AreaNombre, $AreaProduccion, $StLocation, $StBin);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con AreaNombre: ' . $AreaNombre);
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