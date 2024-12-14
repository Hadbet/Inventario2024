<?php
include_once('db/db_Inventario.php');

$pvb = $_POST['pvb'];
$grammerNo = $_POST['grammerNo'];
$tipo = $_POST['tipo'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    if ($tipo==1){
        $stmt = $conex->prepare("INSERT INTO `Ubicaciones`(`GrammerNo`, `PVB`) VALUES (?,?)");
        $stmt->bind_param("ss", $grammerNo, $pvb);
    }else{
        $stmt = $conex->prepare("UPDATE `Ubicaciones` SET `PVB`=? WHERE `GrammerNo`=?");
        $stmt->bind_param("ss", $pvb, $grammerNo);
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