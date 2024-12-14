<?php
include_once('connection.php');
consultarStorage();
function consultarStorage(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT id_StorageUnit,Numero_Parte,Cantidad, Storage_Bin, Storage_Type FROM Storage_Unit";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>