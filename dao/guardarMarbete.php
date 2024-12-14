<?php
include_once('db/db_Inventario.php');
try {
    $nombre = $_POST['nombre'];
    $comentarios = $_POST['comentarios'];
    $folioMarbete = $_POST['folioMarbete'];
    $storageUnits = json_decode($_POST['storageUnits'], true);

    $parts = explode('.', $folioMarbete);

    $marbete = intval($parts[0]); // Esto es equivalente a parseInt() en JavaScript
    $conteo = isset($parts[1]) ? $parts[1] : null;

    $con = new LocalConector();
    $conex=$con->conectar();
    $failedUnits = array();

    $Object = new DateTime();
    $Object->setTimezone(new DateTimeZone('America/Denver'));
    $DateAndTime = $Object->format("Y/m/d h:i:s");

    if ($conteo == 1) {
        $stmt = $conex->prepare("UPDATE `Bitacora_Inventario` SET  `Usuario`=?, `Estatus`='2', `PrimerConteo`=?,`Comentario`=? WHERE `FolioMarbete`=? AND `Estatus` = 0");
    } elseif ($conteo == 2) {
        $stmt = $conex->prepare("UPDATE `Bitacora_Inventario` SET  `UserSeg`=?, `SegundoConteo`=?, `SegFolio`=1 WHERE `FolioMarbete`=? AND `Estatus` = 1");
    } elseif ($conteo == 3) {
        $stmt = $conex->prepare("UPDATE `Bitacora_Inventario` SET  `TercerConteo`=? WHERE `FolioMarbete`=? AND `Estatus` = 1");
    }

    $totalCantidad = 0;

    foreach ($storageUnits as $storageUnit => $details) {
        $numeroParte = $details['numeroParte'];
        $cantidad = $details['cantidad'];
        $totalCantidad += $cantidad;
    }

    $primerConteo = $conteo == 1 ? $totalCantidad : 0;
    $segundoConteo = $conteo == 2 ? $totalCantidad : 0;
    $tercerConteo = $conteo == 3 ? $totalCantidad : 0;

    if ($conteo == 1) {
        $stmt->bind_param("ssss",  $nombre, $primerConteo, $comentarios, $marbete);
    } elseif ($conteo == 2) {
        $stmt->bind_param("sss",  $nombre, $segundoConteo, $marbete);
    } elseif ($conteo == 3) {
        $stmt->bind_param("ss",$tercerConteo, $marbete);
    }




    if (!$stmt->execute()) {
        echo json_encode(["success" => false]);
        throw new Exception('Error al ejecutar la consulta');
    } else {

        $stmt3 = $conex->prepare("UPDATE `Storage_Unit` SET `Estatus`='0' WHERE `FolioMarbete`=?");
        $stmt3->bind_param("s", $marbete);
        $stmt3->execute();
        $stmt3->close();


        $stmt2 = $conex->prepare("UPDATE `Storage_Unit` SET `Estatus`='1',`Conteo`=?,`FolioMarbete`=?,`Cantidad`=? WHERE `Id_StorageUnit` = ?");
        foreach ($storageUnits as $storageUnit => $details) {
            $cantidad = $details['cantidad'];
            $stmt2->bind_param("ssss", $conteo,$marbete,$cantidad,$storageUnit);
            $stmt2->execute();
        }
        $stmt2->close();
        echo json_encode(["success" => true]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage().$nombre.$storageUnits.$numeroParte. $marbete. $DateAndTime]);
}

?>