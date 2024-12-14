<?php
require 'consultaVerificarUsuario.php';
require 'consultaAreaUsuario.php';

session_start();
$Nomina = $_POST['nomina'];
$Password = $_POST['password'];

$statusLogin = consultarUsuarioVerificacion($Nomina, $Password);

if ($statusLogin['status'] == 1) {
    $_SESSION['nominaCurso'] = $Nomina;
    $_SESSION['passwordCurso'] = $Password;
    $_SESSION['rol'] = $statusLogin['rol'];
    $_SESSION['area'] = $statusLogin['area'];
    $_SESSION['nombre'] = $statusLogin['nombre'];
    $_SESSION['nomina'] = $statusLogin['nomina'];

    // Realizamos la segunda consulta para obtener los detalles del área
    $areaDetails = consultarAreaDetails($statusLogin['area']);
    $_SESSION['AreaNombre'] = $areaDetails['area'];
    $_SESSION['StBin'] = $areaDetails['bin'];
    $_SESSION['tipoArea'] = $areaDetails['tipoArea'];

    if ($statusLogin['rol'] == 1){

        if ($areaDetails['tipoArea'] == "2"){
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../form_registro.php'>";
        }

        if ($areaDetails['tipoArea'] == "1"){
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../form_registro_produccion.php'>";
        }

        if ($areaDetails['tipoArea'] == "0"){
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../form_registro_almacen.php'>";
        }

    }
    if ($statusLogin['rol'] == 2){
        if ($areaDetails['tipoArea'] == "2"){
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../verificacion_almacen.php'>";
        }else{
            echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../verificacion.php'>";
        }
    }
    if ($statusLogin['rol'] == 3){
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../control_conteo.php'>";
    }
    if ($statusLogin['rol'] == 4){
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../inicio.php'>";
    }
    if ($statusLogin['rol'] == 7){
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../inicio.php'>";
    }
    if ($statusLogin['rol'] == 5){
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../form_validacion_produccion.php'>";
    }
} else if ($statusLogin['status'] == 0) {
    echo "<script>alert('Contraseña incorrecta')</script>";
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../index.html'>";
}else if ($statusLogin['status'] == 2) {
    echo "<script>alert('Usuario no encontrado')</script>";
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../index.html'>";
}

?>