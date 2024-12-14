<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM `Bitacora_Inventario` WHERE (`Estatus` = 0 or `Estatus` = 2) and `Area` = '$area';");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>