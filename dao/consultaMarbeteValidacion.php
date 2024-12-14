<?php

include_once('db/db_Inventario.php');


$marbete = $_GET['marbete'];
$conteo = $_GET['conteo'];

ContadorApu($marbete,$conteo);

function ContadorApu($marbete,$conteo)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    if ($conteo==1){
        $datos = mysqli_query($conex, "SELECT * FROM `Bitacora_Inventario` WHERE `FolioMarbete` = '$marbete'");
    }

    if ($conteo==2){
        $datos = mysqli_query($conex, "SELECT * FROM `Bitacora_Inventario` WHERE `FolioMarbete` = '$marbete' and `SegFolio` = 2");
    }

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>