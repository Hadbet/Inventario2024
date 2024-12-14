<?php

include_once('db/db_Inventario.php');



ContadorApu();


function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    `StBin`, 
    `StType`,
    CONCAT('<button class=\"btn btn-info text-white\" onclick=\"llenarBin(\'', `StBin`, '\', \'', `StType`, '\')\">Modificar</button>') AS `Boton`
FROM `Bin` WHERE 1");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>