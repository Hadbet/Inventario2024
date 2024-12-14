<?php

include_once('db/db_Inventario.php');


$parte = $_GET['parte'];

ContadorApu($parte);

function ContadorApu($parte)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM `Parte` WHERE `GrammerNo` = '$parte'");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>