<?php
include_once('connection.php');
consultarBin();
function consultarBin(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT StBin,StType FROM Bin";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>