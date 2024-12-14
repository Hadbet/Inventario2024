<?php

include_once('db/db_Inventario.php');


$area = $_GET['area'];

ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    if ($area=="all"){
        $areaUno="1=1";
        $areaDos="1=1";
        $areaTres="1=1";
    }else{
        $areaUno="Area = $area";
        $areaDos="AreaCve = $area";
        $areaTres="A.IdArea = $area";
    }

    $datos = mysqli_query($conex, "SELECT A.AreaNombre, SUM(T.TotalContado) AS TotalContado, SUM(T.TotalEsperado) AS TotalEsperado 
FROM ( 
    SELECT Area, 
    COALESCE( 
        SUM( 
            COALESCE( 
                CASE WHEN TercerConteo != 0 THEN TercerConteo END, 
                CASE WHEN SegundoConteo != 0 THEN SegundoConteo END, 
                PrimerConteo 
            ) * (P.Costo / P.Por)
        ) , 
        0 
    ) AS TotalContado, 
    0 AS TotalEsperado 
    FROM Bitacora_Inventario B 
    LEFT JOIN Parte P ON B.NumeroParte = P.GrammerNo 
    WHERE Estatus = 1 AND $areaUno
    GROUP BY Area 
    
    UNION ALL
    
    SELECT AreaCve AS Area, 
    0 AS TotalContado, 
    COALESCE( SUM( Cantidad * (P.Costo / P.Por) ), 0 ) AS TotalEsperado 
    FROM InventarioSap I 
    LEFT JOIN Parte P ON I.GrammerNo = P.GrammerNo 
    WHERE $areaDos
    GROUP BY AreaCve 
) AS T 
JOIN Area A ON T.Area = A.IdArea 
WHERE $areaTres
GROUP BY A.AreaNombre;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>