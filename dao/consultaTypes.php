<?php

include_once('db/db_Inventario.php');


$bin = $_GET['bin'];

ContadorApu($bin);

function ContadorApu($bin)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT `StType`,`StBin` FROM `Bin` WHERE `StBin` = '$bin'");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>