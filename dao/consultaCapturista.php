<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT B.FolioMarbete, B.Comentario, M.Numero_Parte, B.Fecha, B.Usuario, CONCAT('<button class=\"btn btn-primary\" onclick=\"detallesRegistro(\'', B.FolioMarbete, '\', \'', B.Comentario, '\', \'', B.Usuario, '\', \'', B.Fecha, '\')\">Ver</button>') as BOTON FROM Bitacora_Inventario B JOIN Marbete_Inventario M ON B.FolioMarbete = M.Id_Marbete WHERE B.`Estatus`= 1 GROUP BY B.FolioMarbete;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>