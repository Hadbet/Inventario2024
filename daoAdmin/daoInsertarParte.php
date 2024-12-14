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
            $Nomina = isset($registroParte['Nomina']) ? trim($registroParte['Nomina']) : null;
            $Nombre = isset($registroParte['Nombre']) ? trim($registroParte['Nombre']) : null;
            $Usuario = isset($registroParte['Usuario']) ? trim($registroParte['Usuario']) : null;
            $Password = isset($registroParte['Password']) ? trim($registroParte['Password']) : null;
            $Rol = isset($registroParte['Rol']) ? trim($registroParte['Rol']) : null;
            $Area = isset($registroParte['Area']) ? trim($registroParte['Area']) : null;
            $Estatus = 1; // Estatus siempre será 1

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($Nomina === null || $Nombre === null || $Usuario === null || $Password === null || $Rol === null || $Area === null) {
                $errores[] = "Faltan datos para el registro Nomina: $Nomina, Nombre: $Nombre, Usuario: $Usuario, Password: $Password, Rol: $Rol, Area: $Area ";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosUsuario($Nomina, $Nombre, $Usuario, $Password, $Rol, $Estatus, $Area);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro ID: $Nomina. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Usuarios fueron insertados correctamente.");
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


function insertarRegistrosUsuario($Nomina, $Nombre, $Usuario, $Password, $Rol, $Estatus, $Area) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Encriptar la contraseña
        $PasswordEncriptada = password_hash($Password, PASSWORD_DEFAULT);

        // Insertar el nuevo registro
        $insertUsuario = $conex->prepare("INSERT INTO `Usuarios` (`Nomina`, `Nombre`, `User`, `Password`, `Rol`, `Estatus`, `Area`) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertUsuario->bind_param("sssssis", $Nomina, $Nombre, $Usuario, $PasswordEncriptada, $Rol, $Estatus, $Area);

        $resultado = $insertUsuario->execute();

        if (!$resultado) {
            $conex->rollback();
            $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con Nomina: ' . $Nomina);
        } else {
            $conex->commit();
            $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
        }

        $insertUsuario->close();

    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}
?>