<?php
include_once('db/db_Inventario.php');

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$location = $_POST['location'];
$bin = $_POST['bin'];
$conteo = $_POST['conteo'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("UPDATE `Area` SET `AreaNombre`=?,`AreaProduccion`=?,`StLocation`=?,`StBin`=?,`Conteo`=? WHERE `IdArea`=?");
    $stmt->bind_param("sissii", $nombre, $tipo, $location, $bin,$conteo,$id);

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