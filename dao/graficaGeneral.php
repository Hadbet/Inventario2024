<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    A.`AreaNombre`,
    COUNT(CASE WHEN BI.`Estatus` = 1 THEN 1 END) AS 'PrimerConteoLiberado',
    COUNT(CASE WHEN BI.`Estatus` = 0 THEN 1 END) AS 'PrimerConteoNoLiberado',
    COUNT(CASE WHEN BI.`SegFolio` = 1 THEN 1 END) AS 'SegundoConteoLiberado',
    COUNT(CASE WHEN BI.`SegFolio` = 2 THEN 1 END) AS 'SegundoConteoNoLiberado',
    COUNT(CASE WHEN BI.`Estatus` = 1 THEN 1 END) AS 'TercerConteoLiberado',
    COUNT(CASE WHEN BI.`Estatus` = 0 THEN 1 END) AS 'TercerConteoNoLiberado',
    COUNT(CASE WHEN BI.`Estatus` IN (0, 1,2) THEN 1 END) AS 'TotalPrimerConteo',
    COUNT(CASE WHEN BI.`SegFolio` IS NOT NULL THEN 1 END) AS 'TotalSegundoConteo',
    COUNT(CASE WHEN BI.`TercerConteo` IS NOT NULL THEN 1 END) AS 'TotalTercerConteo',
    (COUNT(CASE WHEN BI.`Estatus` = 1 THEN 1 END) / COUNT(CASE WHEN BI.`Estatus` IN (0, 1,2) THEN 1 END)) * 100 AS 'PorcentajePrimerConteo',
    (COUNT(CASE WHEN BI.`SegFolio` = 1 THEN 1 END) / (COUNT(CASE WHEN BI.`SegFolio` = 1 THEN 1 END) + COUNT(CASE WHEN BI.`SegFolio` = 2 THEN 1 END))) * 100 AS 'PorcentajeSegundoConteo',
    (COUNT(CASE WHEN BI.`Estatus` = 1 THEN 1 END) / COUNT(CASE WHEN BI.`TercerConteo` IS NOT NULL THEN 1 END)) * 100 AS 'PorcentajeTercerConteo'
FROM 
    `Area` AS A
LEFT JOIN 
    `Bitacora_Inventario` AS BI ON A.`IdArea` = BI.`Area`
WHERE 
    A.`AreaNombre` <> 'Mesa Central'
GROUP BY 
    A.`AreaNombre`;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>