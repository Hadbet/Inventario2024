<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT InvSap.`GrammerNo`, Parte.`Descripcion`
FROM `InventarioSap` AS InvSap
LEFT JOIN `Bitacora_Inventario` AS BI ON InvSap.`GrammerNo` = BI.`NumeroParte` AND InvSap.`AreaCve` = BI.`Area`
LEFT JOIN `Parte` ON InvSap.`GrammerNo` = Parte.`GrammerNo`
WHERE BI.`NumeroParte` IS NULL AND InvSap.`AreaCve` = $area;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>