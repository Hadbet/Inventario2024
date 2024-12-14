<?php

include_once('db/db_Inventario.php');

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT 
    ISap.GrammerNo, 
    ISap.STBin, 
    ISap.Cantidad AS 'Total_InventarioSap', 
    BInv.NumeroParte, 
    BInv.StorageBin, 
    SUM(
        COALESCE( 
            CASE WHEN BInv.TercerConteo != 0 THEN BInv.TercerConteo END, 
            CASE WHEN BInv.SegundoConteo != 0 THEN BInv.SegundoConteo END, 
            BInv.PrimerConteo 
        )
    ) AS 'Total_Bitacora_Inventario', 
    BInv.FolioMarbete,
    Part.Descripcion,
    Part.UM,
    (Part.Por / Part.Costo) AS 'CostoUnitario',
    CASE 
        WHEN BInv.TercerConteo != 0 THEN 'Con tercer conteo'
        WHEN BInv.SegundoConteo != 0 THEN 'Con segundo conteo'
        WHEN BInv.PrimerConteo != 0 THEN 'Con primer conteo'
        ELSE ''
    END AS 'Comentario'
FROM 
    InventarioSap ISap
LEFT JOIN 
    Bitacora_Inventario BInv ON ISap.GrammerNo = BInv.NumeroParte AND ISap.STBin = BInv.StorageBin AND ISap.STType = BInv.StorageType AND BInv.Estatus = 1
LEFT JOIN
    Parte Part ON ISap.GrammerNo = Part.GrammerNo
GROUP BY 
    ISap.GrammerNo, 
    ISap.STBin, 
    BInv.NumeroParte, 
    BInv.StorageBin, 
    BInv.FolioMarbete,
    Part.Descripcion,
    Part.UM,
    Part.Por,
    Part.Costo");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>