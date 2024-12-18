<?php

include_once('db/db_Inventario.php');

$area = $_GET['area'];
ContadorApu($area);

function ContadorApu($area)
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $consulta = "SELECT 
    BInv.NumeroParte AS 'GrammerNo', 
    P.Descripcion, 
    P.UM, 
    BInv.StorageBin, 
    BInv.FolioMarbete, 
    SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Total_Conteo', 
    CASE 
        WHEN BInv.TercerConteo != 0 THEN 'Con tercer conteo' 
        WHEN BInv.SegundoConteo != 0 THEN 'Con segundo conteo' 
        ELSE 'Con primer conteo' 
    END AS 'Comentario'
FROM 
    Bitacora_Inventario BInv
JOIN 
    Parte P ON BInv.NumeroParte = P.GrammerNo
WHERE 
    BInv.Estatus = 1 AND BInv.Area = 34
GROUP BY 
    BInv.NumeroParte, 
    BInv.StorageBin,
    BInv.FolioMarbete;";

    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $area);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $resultado = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}

?>