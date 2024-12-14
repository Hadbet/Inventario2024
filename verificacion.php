<?php
session_start();
$rol =$_SESSION['rol'];
$area =$_SESSION['area'];
$areaNombre =$_SESSION['AreaNombre'];
$bin =$_SESSION['StBin'];
$nomina =$_SESSION['nomina'];
$nombre =$_SESSION['nombre'];

if (strlen($nomina) == 1) {
    $nomina = "0000000" . $nomina;
}
if (strlen($nomina) == 2) {
    $nomina = "000000" . $nomina;
}
if (strlen($nomina) == 3) {
    $nomina = "00000" . $nomina;
}
if (strlen($nomina) == 4) {
    $nomina = "0000" . $nomina;
}
if (strlen($nomina) == 5) {
    $nomina = "000" . $nomina;
}
if (strlen($nomina) == 6) {
    $nomina = "00" . $nomina;
}
if (strlen($nomina) == 7) {
    $nomina = "0" . $nomina;
}?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>GRAMMER INVENTARIO</title>

    <?php include 'estaticos/stylesEstandar.php'; ?>
    <!-- JavaScript -->
    <script src="lib/sweetalert2.all.min.js"></script>

    <style>
        .image-container {
            display: flex;
            justify-content: space-around;
        }

        .image-container img {
            transition: all 0.3s ease;
            width: 100px;
            height: 100px;
        }

        .image-container img:hover {
            transform: translateY(-10px);
        }

        .image-container img.active {
            transform: scale(1.2);
        }

        .image-container img.inactive {
            transform: scale(0.8);
        }

        #divCurso {
            opacity: 0;
            transition: opacity 2s;
        }

        .image-zoom {
            transform: scale(1.1);
            transition: transform .2s; /* Animación */
        }
    </style>

</head>
<body class="vertical  light  ">
<div class="wrapper">

    <?php
            require_once('estaticos/navegador.php');
    ?>

    <main role="main" class="main-content">
        <center><img src="images/tituloInventario.png" style="width: 50%"></center>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="w-50 mx-auto text-center justify-content-center py-5 my-5">
                        <h2 class="page-title mb-0">Ingresa o escanea el marbete</h2>
                        <p class="lead text-muted mb-4">Si lo vas a ingresar manual recuerda que es marbete conteo.</p>
                            <input class="form-control form-control-lg bg-white rounded-pill pl-5" type="search" id="txtBuscar" autocomplete="off">
                            <p class="help-text mt-2 text-muted">Ejemplo 185.1 o 185.2 o 185.3.</p>
                            <br>
                            <button class="btn btn-success text-white" onclick="verificacionRegistro()" >Buscar</button>
                    </div>
                </div> <!-- .col-12 -->
            </div> <!-- .row -->

            <div class="row" id="divMarbete" style="display: none">

                <div class="col-md-6 col-xl-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <span class="card-title">Marbete : <span id="lblFolio"></span></span>
                            <a class="float-right small text-muted" href="#!"><i class="fe fe-more-vertical fe-12"></i></a>
                        </div>
                        <div class="card-body my-n2">
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Número de parte</span>
                                    <h4 class="mb-0" id="lblNumeroParte"></h4>
                                </div>
                                <div class="flex-fill text-right">
                                    <p class="mb-0 small" id="lblCosto"></p>
                                    <p class="text-muted mb-0 small">Pesos</p>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Cantidad</span>
                                    <h4 class="mb-0" id="lblCantidad"> <span id="lblUm"></span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Descripcion</span>
                                    <h4 class="mb-0" id="lblDescripcion"></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Storage Bin</span>
                                    <h4 class="mb-0" id="lblStorageBin"></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Monto Total</span>
                                    <h4 class="mb-0" id="lblMontoTotal"></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="profile-posts.html" class="avatar avatar-md">
                                        <img id="imagenCapturador" alt="..." class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="col ml-n2">
                                    <strong class="mb-1" id="lblNombreCapturador"></strong><span class="dot dot-lg bg-success ml-1"></span>
                                    <p class="small text-muted mb-1" id="lblRol">Capturista</p>
                                </div>
                            </div>
                        </div> <!-- .card-body -->
                    </div> <!-- .card -->
                </div> <!-- .col -->

                <div class="col-md-6 col-xl-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <span class="card-title">Validador</span>
                            <a class="float-right small text-muted" href="#!"><i class="fe fe-more-vertical fe-12"></i></a>
                        </div>
                        <div class="card-body my-n2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="profile-posts.html" class="avatar avatar-md">
                                        <img id="imagenVerificador" alt="..." class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="col ml-n2">
                                    <strong class="mb-1" id="lblNombreVerificador"></strong><span class="dot dot-lg bg-success ml-1"></span>
                                    <p class="small text-muted mb-1" id="lblRol">Verificador</p>
                                </div>
                            </div>
                        </div> <!-- .card-body -->
                    </div> <!-- .card -->
                </div> <!-- .col -->

            </div>


        </div> <!-- .container-fluid -->
    </main> <!-- main -->
</div> <!-- .wrapper -->

<?php include 'estaticos/scriptEstandar.php'; ?>

<script src="js/apps.js"></script>
<script src="assets/scanapp.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>

<script>

    var cantidad;

    function verificacionRegistro() {

        $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaVerificacionProduccion.php?marbete='+document.getElementById("txtBuscar").value+'&area='+<?php echo $area;?>, function (data) {

            if (data && data.data && data.data.length > 0){

            for (var i = 0; i < data.data.length; i++) {
                if (i==0){
                        var usuario = data.data[i].Usuario;
                        var separado = usuario.split("-");
                        var numeroNomina = separado[0];
                        var nombre = separado[1];

                        var usuarioVerificador = data.data[i].UsuarioVerificacion;
                        var separadoVerificador = usuarioVerificador.split("-");
                        var numeroNominaVerificador = separadoVerificador[0];
                        var nombreVerificador = separadoVerificador[1];

                        document.getElementById("imagenCapturador").src = 'https://grammermx.com/Fotos/'+numeroNomina+'.png';
                        document.getElementById("lblNombreCapturador").innerText = nombre;

                        document.getElementById("imagenVerificador").src = 'https://grammermx.com/Fotos/'+numeroNominaVerificador+'.png';
                        document.getElementById("lblNombreVerificador").innerText = nombreVerificador;

                        document.getElementById("lblFolio").innerText = data.data[i].FolioMarbete;
                        document.getElementById("lblNumeroParte").innerText = data.data[i].NumeroParte;
                        document.getElementById("lblCantidad").innerText = data.data[i].PrimerConteo;
                        document.getElementById("lblStorageBin").innerText = data.data[i].StorageBin;
                        cantidad = data.data[i].PrimerConteo;
                        cargaPrimer(data.data[i].NumeroParte);


                }
            }
            }else{
                Swal.fire({
                    title: "El marbete no esta capturado aun o no pertenece a tu area",
                    text: "Verificalo con la mesa de control",
                    icon: "error"
                });
            }

        });
    }

    function cargaPrimer(numeroParte) {
        $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaParte.php?parte='+numeroParte, function (data) {
            for (var i = 0; i < data.data.length; i++) {
                if (data.data[i].GrammerNo) {
                    document.getElementById('lblDescripcion').innerText = data.data[i].Descripcion;
                    costoUnitario = data.data[i].Costo / data.data[i].Por;
                    document.getElementById('lblCosto').innerText = costoUnitario;
                    document.getElementById('lblMontoTotal').innerText = costoUnitario*cantidad;
                    bandera=1;

                    document.getElementById("divMarbete").style.display='flex';
                    document.getElementById("divMarbete").scrollIntoView({behavior: "smooth"});
                } else {
                    bandera=0;
                    Swal.fire({
                        title: "El numero de parte no existe",
                        text: "Verificalo con la mesa de control",
                        icon: "error"
                    });
                }
            }
        });
    }

    document.getElementById('txtBuscar').addEventListener('keyup', function(event) {
        if (event.key === 'Enter' || event.keyCode === 13) {
            verificacionRegistro();
        }
    });

</script>
</body>
</html>