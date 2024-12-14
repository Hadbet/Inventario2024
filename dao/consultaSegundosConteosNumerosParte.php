<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT COUNT(*) AS CantidadDiferencias FROM InventarioSap AS InvSap 
    INNER JOIN Bitacora_Inventario AS BI ON InvSap.GrammerNo = BI.NumeroParte 
    INNER JOIN Parte P ON InvSap.GrammerNo = P.GrammerNo 
    WHERE (ABS((P.Costo / P.Por) * InvSap.Cantidad - (P.Costo / P.Por) * BI.PrimerConteo) > 3000 OR ABS(InvSap.Cantidad - BI.PrimerConteo) > 10) AND InvSap.AreaCve = 299;");
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>