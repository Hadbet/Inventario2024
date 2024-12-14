<?php
include_once('db/db_Inventario.php');

$user = $_POST['user'];
$area = $_POST['area'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptamos la contraseña
$rol = $_POST['rol'];
$estatus = $_POST['estatus'];
$nombre = $_POST['nombre'];
$nomina = $_POST['nomina'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("INSERT INTO `Usuarios`( `User`, `Password`, `Rol`, `Estatus`, `Area`, `Nomina`, `Nombre`) VALUES (?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("ssssiis", $user, $password, $rol, $estatus,$area,$nomina,$nombre);

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