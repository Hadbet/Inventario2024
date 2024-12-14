<?php
include_once('db/db_Inventario.php');

$user = $_POST['user'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptamos la contraseña
$id = $_POST['id'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("UPDATE `Usuarios` SET `User`=?,`Password`=? WHERE `Id_Usuario` = ?");
    $stmt->bind_param("ssi", $user, $password, $id);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Actualización exitosa"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo actualizar el registro"]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>