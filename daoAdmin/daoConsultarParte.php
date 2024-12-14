<?php
include_once('connection.php');
consultarParte();
function consultarParte(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT GrammerNo,Descripcion,UM, ProfitCtr, Costo,Por FROM Parte";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>