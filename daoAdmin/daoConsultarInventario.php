<?php
include_once('connection.php');
consultarInventario();
function consultarInventario(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT STLocation,StBin,StType,GrammerNo,Cantidad,AreaCve FROM InventarioSap";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>