<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();


function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    `IdArea`, 
    `AreaNombre`, 
    CASE `AreaProduccion`
        WHEN 1 THEN '<span class=\"badge badge-pill badge-success text-white\">Produccion</span>'
        WHEN 2 THEN '<span class=\"badge badge-pill badge-info\">SUM</span>'
        ELSE '<span class=\"badge badge-pill badge-default\" style=\"background: #e9550e; color: white;\">Almacen</span>'
    END AS `AreaProduccion`, 
    `StLocation`, 
    `StBin`, 
    `Conteo`,
    CONCAT('<button class=\"btn btn-info text-white\"  onclick=\"llenarDatos(\'', `IdArea`, '\', \'', `AreaNombre`, '\', \'', `AreaProduccion`, '\', \'', `StLocation`, '\', \'', `StBin`, '\', \'', `Conteo`, '\')\">Modificar</button>') AS `Boton`
FROM `Area` WHERE 1");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>