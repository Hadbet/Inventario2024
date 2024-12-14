<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT * FROM (
    SELECT 
        (SELECT SUM(Cantidad) FROM InventarioSap WHERE AreaCve = $area) AS TotalInventarioSap, 
        (SELECT SUM(PrimerConteo) FROM Bitacora_Inventario WHERE Area = $area AND Estatus = 1) AS TotalPrimerConteoBitacora, 
        (SELECT SUM(InventarioSap.Cantidad * (Parte.Costo / Parte.Por)) 
         FROM InventarioSap 
         INNER JOIN Parte ON InventarioSap.GrammerNo = Parte.GrammerNo 
         WHERE InventarioSap.AreaCve = $area) AS CostoTotalInventarioSap, 
        (SELECT SUM(Bitacora_Inventario.PrimerConteo * (Parte.Costo / Parte.Por)) 
         FROM Bitacora_Inventario 
         INNER JOIN Parte ON Bitacora_Inventario.NumeroParte = Parte.GrammerNo 
         WHERE Bitacora_Inventario.Area = $area AND Bitacora_Inventario.Estatus = 1) AS CostoTotalPrimerConteoBitacora,
        (SELECT MAX(SegFolio) FROM Bitacora_Inventario WHERE Area = $area AND Estatus = 1) AS SegFolio
) AS Subquery
WHERE ABS(Subquery.CostoTotalInventarioSap - Subquery.CostoTotalPrimerConteoBitacora) >= 3000 OR Subquery.SegFolio = 2;");
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>