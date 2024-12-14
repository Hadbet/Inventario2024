<?php
include_once('db/db_Inventario.php');

$stType = $_POST['stType'];
$stBin = $_POST['stBin'];
$tipo = $_POST['tipo'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    if ($tipo==1){
        $stmt = $conex->prepare("INSERT INTO `Bin`(`StBin`, `StType`) VALUES (?,?)");
        $stmt->bind_param("ss", $stBin, $stType);
    }else{
        $stmt = $conex->prepare("UPDATE `Bin` SET `StType`=? WHERE `StBin`=?");
        $stmt->bind_param("ss", $stType, $stBin);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Transaccion exitosa"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo insertar/actualizar el registro"]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>