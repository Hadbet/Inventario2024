<?php

include_once('db/db_Inventario.php');


$marbete = $_GET['marbete'];
$area = $_GET['area'];
$parts = explode('.', $marbete);

$marbeteSolo = $parts[0];
$conteo = $parts[1];

ContadorApu($marbeteSolo,$conteo,$area);

function ContadorApu($marbeteSolo,$conteo,$area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM `Bitacora_Inventario` WHERE `FolioMarbete` = '$marbeteSolo' and `Area` = '$area'");
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>