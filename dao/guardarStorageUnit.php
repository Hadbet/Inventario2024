<?php
include_once('db/db_Inventario.php');

$storageUnit = $_POST['storageUnit'];
$numeroParte = $_POST['numeroParte'];
$cantidad = $_POST['cantidad'];
$storageBin = $_POST['storageBin'];
$storageType = $_POST['storageType'];
$conteo = $_POST['conteo'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("INSERT INTO `Storage_Unit`(`Id_StorageUnit`, `Numero_Parte`, `Cantidad`, `Storage_Bin`, `Storage_Type`, `Estatus`, `FolioMarbete`, `Conteo`) VALUES (?,?,?,?,?,0,'',?)");
    $stmt->bind_param("sssssi", $storageUnit, $numeroParte,$cantidad,$storageBin,$storageType,$conteo);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Insert exitoso"]);
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