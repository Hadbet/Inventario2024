<?php
include_once('db/db_Inventario.php');

try {
    $nombre = $_POST['nombre'];
    $folioMarbete = $_POST['folioMarbete'];

    $parts = explode('.', $folioMarbete);

    $marbete = intval($parts[0]); // Esto es equivalente a parseInt() en JavaScript
    $conteo = isset($parts[1]) ? $parts[1] : null;

    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("UPDATE `Bitacora_Inventario` SET `UsuarioVerificacion` = ?,`SegFolio` = 2,`Estatus` = 1 WHERE `FolioMarbete` = ?");

    $stmt->bind_param("ss", $nombre, $marbete);


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