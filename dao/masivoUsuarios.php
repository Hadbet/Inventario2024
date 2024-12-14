<?php
include_once('db/db_Inventario.php');

$estado = $_POST['estado'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("UPDATE `Usuarios` SET `Estatus`=? WHERE `Rol` <> 4;");
    $stmt->bind_param("i", $estado);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Inserción exitosa"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo insertar el registro"]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>