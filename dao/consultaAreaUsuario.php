<?php
include_once('db/db_Inventario.php');
function consultarAreaDetails($area){
    try {
        $con = new LocalConector();
        $conex=$con->conectar();

        // Buscamos al usuario en la base de datos
        $stmt = $conex->prepare("SELECT `IdArea`, `AreaNombre`, `AreaProduccion`, `StLocation`, `StBin`, `Conteo` FROM `Area` WHERE `IdArea` = ?");
        $stmt->bind_param("s", $area);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ['status' => 1, 'area' => $row['AreaNombre'], 'bin' => $row['StBin'], 'tipoArea' => $row['AreaProduccion']];
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