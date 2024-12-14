<?php
include_once('connection.php');
consultarUbicaciones();
function consultarUbicaciones(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT GrammerNo,PVB FROM Ubicaciones";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>