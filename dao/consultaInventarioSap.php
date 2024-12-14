<?php

include_once('db/db_Inventario.php');


ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT InventarioSap.STLocation, InventarioSap.STBin, InventarioSap.STType, InventarioSap.GrammerNo, InventarioSap.Cantidad, InventarioSap.AreaCve, Area.AreaNombre FROM InventarioSap INNER JOIN Area ON InventarioSap.AreaCve = Area.IdArea;");
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>