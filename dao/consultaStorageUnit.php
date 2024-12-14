<?php

include_once('db/db_Inventario.php');

$storageUnit = $_GET['storageUnit'];
$bin = $_GET['bin'];
$conteo = $_GET['conteo'];

ContadorApu($storageUnit,$bin,$conteo);

function ContadorApu($storageUnit,$bin,$conteo)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    // Consulta para verificar si el storageUnit existe
    $check = mysqli_query($conex, "SELECT * FROM `Storage_Unit` WHERE `Id_StorageUnit` = '$storageUnit'");

    // Si no hay resultados, el storageUnit no existe
    if (mysqli_num_rows($check) == 0) {
        echo json_encode(array("Estatus" => "No existe el storage unit"));
        return;
    }

    $datos = mysqli_query($conex, "SELECT * FROM `Storage_Unit` WHERE `Id_StorageUnit` = '$storageUnit' and `Storage_Bin`='$bin'");

    if (mysqli_num_rows($datos) == 0) {
        echo json_encode(array("Estatus" => "No coincide el storage unit con el storage bin "));
        return;
    }

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);

    if ($conteo=="1"){
        $datosBitacora = mysqli_query($conex, "SELECT * FROM `Storage_Unit` WHERE `Id_StorageUnit` = '$storageUnit' and `Estatus` = 1");

        if (mysqli_num_rows($datosBitacora) > 0) {
            echo json_encode(array("Estatus" => "Ya fue escaneado por otro usuario este storage unit"));
        } else {
            echo json_encode(array("data" => $resultado));
        }
    }else{
        echo json_encode(array("data" => $resultado));
    }
}

?>