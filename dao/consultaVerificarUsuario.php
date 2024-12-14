<?php
include_once('db/db_Inventario.php');
function consultarUsuarioVerificacion($usuario,$contra){
    try {
        $con = new LocalConector();
        $conex=$con->conectar();

        // Buscamos al usuario en la base de datos
        $stmt = $conex->prepare("SELECT `Password`, `Rol`, `Area`, `Nombre`, `Nomina` FROM `Usuarios` WHERE `User` = ? and `Estatus` = 1");
        $stmt->bind_param("s", $usuario);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si el usuario existe, verificamos la contraseña
            $row = $result->fetch_assoc();
            if (password_verify($contra, $row['Password'])) {
                // Devolvemos un array con el rol y el área si la contraseña es correcta
                return ['status' => 1, 'rol' => $row['Rol'], 'area' => $row['Area'], 'nombre' => $row['Nombre'], 'nomina' => $row['Nomina']];
            } else {
                return ['status' => 0];
            }
        } else {
            return ['status' => 2];
        }

        $stmt->close();
        $conex->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

?>