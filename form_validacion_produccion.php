
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
}

?>

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


    <link rel="stylesheet" href="css/generales.css">

</head>
<body class="vertical  light  ">
<div class="wrapper">

    <?php
            require_once('estaticos/navegador.php');
    ?>

    <main role="main" class="main-content">
        <center><img src="images/tituloInventario.png" style="width: 50%"></center>
        <br><br>
        <h2 style="display: none" class="page-title" id="conteoAviso"></h2>
        <div class="container-fluid" id="pasoUno">
            <br>
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Paso 1 : Escaneo de marbete</h2>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Captura</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                               <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="number" class="form-control"
                                                   id="scanner_input" autocomplete="off">
                                            <br>
                                       </div>
                                    </div> <!-- /.col -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn mb-2 btn-success float-right text-white" onclick="manualMarbete()">Siguiente<span
                                    class="fe fe-chevron-right fe-16 ml-2"></span></button>
                        </div>
                    </div> <!-- / .card -->
                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->

        <div class="container-fluid" id="pasoDos" style="display: none">
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="mb-3"><strong>Verificación</strong></p>

                            <label for="basic-url">Cantidad</label>
                            <div class="input-group mb-3">
                                <input type="number" id="txtCantidad" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2"  min="0" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="txtUnidadMedida" style=""></span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="profile-posts.html" class="avatar avatar-md">
                                        <img src="https://grammermx.com/Fotos/<?php echo $nomina?>.png" alt="..." class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="col ml-n2">
                                    <strong class="mb-1" id="lblNombre"><?php echo $nombre?></strong><span class="dot dot-lg bg-success ml-1"></span>
                                    <p class="small text-muted mb-1" id="lblRol">Verificador</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> <!-- /.col -->

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
                            <div class="d-flex" style="display: none !important">
                                <div class="flex-fill">
                                    <span class="card-title">Cantidad</span>
                                    <h4 class="mb-0" id="lblCantidad"> <span id="lblUm"></span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="flex-fill">
                                    <span class="card-title">Descripción</span>
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
                                    <span class="card-title">Valor monetario conteo</span>
                                    <h4 class="mb-0" id="lblMontoTotal"></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar avatar-md">
                                        <img id="imagenCapturador" alt="..." class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="col ml-n2">
                                    <strong class="mb-1" id="lblNombreCapturador"></strong><span class="dot dot-lg bg-success ml-1"></span>
                                    <p class="small text-muted mb-1" id="lblRol">Capturista</p>
                                </div>
                            </div>
                            <hr>
                            <button disabled id="btnFin" class="btn mb-2 btn-success float-right text-white" onclick="enviarDatos()">Finalizar Captura<span
                                        class="fe fe-chevron-right fe-16 ml-2" ></span></button>
                        </div> <!-- .card-body -->
                    </div> <!-- .card -->
                </div> <!-- .col -->

            </div> <!-- .row -->
        </div> <!-- .container-fluid -->


    </main> <!-- main -->

</div> <!-- .wrapper -->

<?php include 'estaticos/scriptEstandar.php'; ?>

<script src="js/apps.js"></script>
<script src="assets/scanapp.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>

<script>
    document.getElementById('scanner_input').focus()

    var auxConteo=0;
    estatusConteo();
    function estatusConteo() {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaAreaDetalle.php?area=<?php echo $area;?>', function (data) {
            for (var i = 0; i < data.data.length; i++) {
                auxConteo = data.data[i].Conteo;

                if (auxConteo == 1){
                    document.getElementById("conteoAviso").style.display="block";
                    document.getElementById("conteoAviso").innerText="Primer Conteo";
                }

                if (auxConteo == 2){
                    document.getElementById("conteoAviso").style.display="block";
                    document.getElementById("conteoAviso").innerText="Segundo Conteo";
                }
            }
        });
    }

    var costoUnitario=0;
    var bandera=0;

    var numeroParte;
    var storageBin;
    var cantidad=0;

    var numeroParteUnit;

    function manualMarbete() {

        var marbete = parseInt(document.getElementById("scanner_input").value.split('.')[0], 10);

        if (document.getElementById("scanner_input").value.split('.')[1] === auxConteo){
            $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaMarbeteValidacion.php?marbete='+marbete+'&conteo='+auxConteo, function (data) {
                if (data && data.data && data.data.length > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        if (data.data[i].FolioMarbete) {
                            if (data.data[i].Estatus === '2'){
                                numeroParte=data.data[i].NumeroParte;
                                storageBin=data.data[i].StorageBin;
                                cantidad=data.data[i].PrimerConteo;
                                var usuario = data.data[i].Usuario;
                                var separado = usuario.split("-"); // Esto dividirá la cadena en dos partes en el lugar donde se encuentra el guión.
                                var numeroNomina = separado[0]; // Esto te dará la primera parte, que es el número de nómina.
                                var nombre = separado[1]; // Esto te dará la segunda parte, que es el nombre.
                                document.getElementById("lblNombreCapturador").innerText = nombre;
                                document.getElementById("imagenCapturador").src = 'https://grammermx.com/Fotos/'+numeroNomina+'.png';

                                document.getElementById("lblFolio").innerHTML = marbete;
                                document.getElementById("pasoDos").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                document.getElementById("lblStorageBin").innerText = storageBin;
                                document.getElementById("lblNumeroParte").innerText = numeroParte;
                                document.getElementById("lblCantidad").innerText = data.data[i].PrimerConteo;
                                cargaPrimer(numeroParte);
                            }else if(data.data[i].SegFolio === '2'){
                                numeroParte=data.data[i].NumeroParte;
                                storageBin=data.data[i].StorageBin;
                                cantidad=data.data[i].PrimerConteo;
                                var usuario = data.data[i].Usuario;
                                var separado = usuario.split("-"); // Esto dividirá la cadena en dos partes en el lugar donde se encuentra el guión.
                                var numeroNomina = separado[0]; // Esto te dará la primera parte, que es el número de nómina.
                                var nombre = separado[1]; // Esto te dará la segunda parte, que es el nombre.
                                document.getElementById("lblNombreCapturador").innerText = nombre;
                                document.getElementById("imagenCapturador").src = 'https://grammermx.com/Fotos/'+numeroNomina+'.png';

                                document.getElementById("lblFolio").innerHTML = marbete;
                                document.getElementById("pasoDos").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                document.getElementById("lblStorageBin").innerText = storageBin;
                                document.getElementById("lblNumeroParte").innerText = numeroParte;
                                document.getElementById("lblCantidad").innerText = data.data[i].PrimerConteo;
                                cargaPrimer(numeroParte);
                            }else{
                                Swal.fire({
                                    title: "El marbete ya fue capturado/validado",
                                    text: "Escanea otro marbete",
                                    icon: "error"
                                });
                            }
                        } else {
                            Swal.fire({
                                title: "El marbete no esta capturado",
                                text: "Verificalo con la mesa central",
                                icon: "error"
                            });
                        }
                    }
                }else{
                    Swal.fire({
                        title: "El marbete no esta cargado para su area o conteo / o ya fue validado",
                        text: "Ve a la mesa de control para mas informacion",
                        icon: "error"
                    });
                }
            });
        }else{
            Swal.fire({
                title: "El marbete no pertenece al conteo: "+auxConteo,
                text: "Escanea el correcto",
                icon: "error"
            });
        }


    }


    function cargaPrimer(numeroParte) {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaParte.php?parte='+numeroParte, function (data) {
            if (data && data.data && data.data.length > 0) {
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data[i].GrammerNo) {
                        document.getElementById('lblDescripcion').innerText = data.data[i].Descripcion;
                        document.getElementById('txtUnidadMedida').innerText = data.data[i].UM;

                        var txtCantidad = document.getElementById('txtCantidad');

                        if (data.data[i].UM === 'PC') {
                            txtCantidad.addEventListener('keypress', function(e) {
                                var charCode = (e.which) ? e.which : e.keyCode;
                                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                                    e.preventDefault();
                                }
                            });
                        } else {
                            // Permite números decimales
                            txtCantidad.removeEventListener('keypress');
                        }

                        costoUnitario = data.data[i].Costo / data.data[i].Por;
                        document.getElementById('lblCosto').innerText = costoUnitario;
                        var resultado = costoUnitario*cantidad;
                        document.getElementById('lblMontoTotal').innerText = resultado.toFixed(2);
                        document.getElementById('txtCantidad').focus();
                        bandera=1;
                    } else {
                        bandera=0;
                        Swal.fire({
                            title: "El numero de parte no existe",
                            text: "Verificalo con la mesa de control",
                            icon: "error"
                        });
                    }
                }
            }else{
                bandera=0;
                Swal.fire({
                    title: "El numero de parte no existe",
                    text: "Verificalo con la mesa de control",
                    icon: "error"
                });
            }
        });
    }

    async function enviarDatos() {
        var marbete = document.getElementById("scanner_input").value;
        var nombre = document.getElementById("lblNombre").innerText;
        var cantidad = document.getElementById("txtCantidad").value;
        var cantidadAnterior = document.getElementById("lblCantidad").innerText;

        if (cantidad === cantidadAnterior || await confirmarCambio()) {
            enviarSolicitud('<?php echo $nomina;?>-'+nombre, marbete, cantidad,auxConteo);
        }
    }

    function confirmarCambio() {
        return new Promise(resolve => {
            Swal.fire({
                title: "La cantidad ingresada es diferente a la capturada ¿Quieres guardar los cambios?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Guardar",
                denyButtonText: "No guardar"
            }).then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                } else if (result.isDenied) {
                    Swal.fire("Los cambios no se guardaron", "", "info");
                    resolve(false);
                }
            });
        });
    }

    function enviarSolicitud(nombre, marbete, cantidad,conteo) {
        var formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('folioMarbete', marbete);
        formData.append('cantidad', cantidad);

        fetch('https://grammermx.com/Logistica/Inventario/dao/actualizarMarbeteProduccion.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarExito();
                } else {
                    console.log("Hubo un error en la operación");
                    console.log("Las unidades de almacenamiento que fallaron son: ", data.message);
                }
            });
    }

    function mostrarExito() {
        let timerInterval;
        Swal.fire({
            title: "¡Gracias!.Se finalizo la captura de tu marbete",
            html: "Te regresaremos a la pagina <b></b> milliseconds.",
            timer: 1500,
            timerProgressBar: true,
            icon: "success",
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                location.reload();
            }
        });
    }


    document.getElementById('scanner_input').addEventListener('keyup', function(event) {
        if (event.key === 'Enter' || event.keyCode === 13) {
            manualMarbete();
            document.getElementById('txtCantidad').focus();
        }
    });


    document.getElementById('txtCantidad').addEventListener('keyup', function(event) {
        if (event.key === '-') {
            document.getElementById('txtCantidad').value = '';
        }
        if (event.key === 'Enter' || event.keyCode === 13) {
            if (document.getElementById('txtCantidad').value === document.getElementById('lblNumeroParte').innerText){
                Swal.fire({
                    title: "En cantidad estas ingresando el numero de parte",
                    text: "Verificalo antes de capturar",
                    icon: "error"
                });
            }else if(document.getElementById('txtCantidad').value === document.getElementById('lblMontoTotal').innerText){
                Swal.fire({
                    title: "En cantidad estas ingresando el monto",
                    text: "Verificalo antes de capturar",
                    icon: "error"
                });
            }else{
                document.getElementById('btnFin').disabled = false;
                document.getElementById("btnFin").scrollIntoView({behavior: "smooth"});

                Swal.fire({
                    title: "¿Deseas guardar la verificación? Si es así, presiona 'Enter'.",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Guardar",
                    denyButtonText: "Verificar datos"
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarDatos();
                        resolve(true);
                    } else if (result.isDenied) {
                        Swal.fire("Por favor, tómate tu tiempo para verificar los datos. Recuerda, cuando hayas terminado, haz clic en el botón verde 'Finalizar'.", "", "info");
                        resolve(false);
                    }
                });

            }

        }
    });
</script>
</body>
</html>