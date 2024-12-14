<?php
include_once('db/db_Inventario.php');

$comentarios = $_POST['comentarios'];
$id = $_POST['id'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("UPDATE `Bitacora_Inventario` SET `Estatus`='5',`Comentario`=? WHERE `Id_Bitacora` = ?");
    $stmt->bind_param("si", $comentarios, $id);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Update exitosa"]);
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