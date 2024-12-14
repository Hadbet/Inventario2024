<?php

include_once('db/db_Inventario.php');


$marbete = $_GET['marbete'];

ContadorApu($marbete);

function ContadorApu($marbete)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM `Storage_Unit` WHERE `FolioMarbete` = '$marbete' and `Conteo` = 1 and Estatus = 1");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>