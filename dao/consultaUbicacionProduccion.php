<?php

include_once('db/db_Inventario.php');


$numeroParte = $_GET['numeroParte'];
$bin = $_GET['bin'];

ContadorApu($numeroParte,$bin);


function ContadorApu($numeroParte,$bin)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM `Ubicaciones` WHERE `GrammerNo` = '$numeroParte' AND `PVB`='$bin';");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>