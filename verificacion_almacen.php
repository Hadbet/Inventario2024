
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

    <link rel="stylesheet" href="css/generales.css">

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
                        <p class="lead text-muted mb-4">Si lo vas a ingresar manual recuerda que es marbete.conteo.</p>
                            <input class="form-control form-control-lg bg-white rounded-pill pl-5" type="number" id="txtBuscar">
                            <p class="help-text mt-2 text-muted">Ejemplo 185.1</p>
                            <br>
                            <button class="btn btn-success text-white" onclick="verificacionRegistro()">Buscar</button>
                    </div>
                    <!-- .row -->
                    <div class="my-5 p-5" id="divMarbete" style="display: none">
                        <div class="text-center">
                            <h2 class="mb-0">Marbete : <span id="txtFolioMarbete"></span></h2>
                            <p class="lead text-muted mb-5">Conteo : <span id="txtConteo"></span></p>
                            <p class="lead text-muted mb-5">Responsable : <span id="txtResponsable"></span></p>
                        </div>
                        <br>
                        <table id="data-table" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Storage Unit</th>
                                <th>Número Parte</th>
                                <th>Cantidad</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <br>
                        <div class="text-center">
                            <h2 class="mb-0">Cantidad Total : <span id="txtCantidadTotal"></span></h2>
                        </div>
                    </div>
                </div> <!-- .col-12 -->
            </div> <!-- .row -->

            <div class="row">

                <div id="marbeteCompleto" class="col-md-6 col-xl-6 mb-4" style="display: none">
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
                            <hr>
                        </div> <!-- .card-body -->
                    </div> <!-- .card -->
                </div> <!-- .col -->

                <div class="col-md-6 col-xl-6 mb-4" id="marbeteValidador" style="display: none">
                    <div class="card shadow">
                        <div class="card-header">
                            <a class="float-right small text-muted" href="#!"><i class="fe fe-more-vertical fe-12"></i></a>
                        </div>
                        <div class="card-body my-n2">
                            <div class="row align-items-center">
                                <label for="basic-url" id="divCantidadT">Cantidad</label>
                                <div class="input-group mb-3" id="divCantidad">
                                    <input type="number" id="txtCantidad" class="form-control" aria-label="Recipient's username" autocomplete="off" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="txtUnidadMedida" style=""></span>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <a href="profile-posts.html" class="avatar avatar-md">
                                        <img id="imagenVerificador" alt="..." class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="col ml-n2">
                                    <strong class="mb-1" id="lblNombreVerificador"></strong><span class="dot dot-lg bg-success ml-1"></span>
                                    <p class="small text-muted mb-1" id="lblRol">Verificador</p>
                                </div>
                                <button id="btnFin" class="btn mb-2 btn-success float-right text-white" onclick="enviarDatos()">Finalizar Captura<span
                                            class="fe fe-chevron-right fe-16 ml-2" ></span></button>

                                <button id="btnSegundos" class="btn mb-2 btn-warning float-right text-white" onclick="enviarSegundos()">Mandar a segundos conteos<span
                                            class="fe fe-chevron-right fe-16 ml-2" ></span></button>
                            </div>
                            <hr>
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

    var marbete;
    var auxConteo=0;
    function verificacionRegistro() {

        var table = document.getElementById("data-table");
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }

        $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaVerificacion.php?marbete='+document.getElementById("txtBuscar").value, function (data) {
            if (data && data.data && data.data.length > 0) {
                for (var i = 0; i < data.data.length; i++) {
                    var row = table.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);

                    if (data.data[i].Id_StorageUnit!==null || data.data[i].Id_StorageUnit!==""){
                        cell1.innerHTML = "";
                        cell2.innerHTML = data.data[i].NumeroParte;
                        cell3.innerHTML = data.data[i].PrimerConteo;
                    }

                    if (i==0){
                        marbete = data.data[i].FolioMarbete;
                        document.getElementById("txtFolioMarbete").innerText = data.data[i].FolioMarbete;
                        document.getElementById("txtConteo").innerText = parseFloat(data.data[i].Conteo).toFixed(2);
                        document.getElementById("txtResponsable").innerText = data.data[i].Usuario;
                        if (data.data[i].Estatus==='2'){
                            document.getElementById("marbeteValidador").style.display='block';
                            document.getElementById("divCantidad").style.display='flex';
                            document.getElementById("divCantidadT").style.display='flex';
                            document.getElementById("btnFin").style.display='block';
                            document.getElementById("btnSegundos").style.display='block';
                        }else{
                            document.getElementById("marbeteValidador").style.display='block';
                            document.getElementById("divCantidad").style.display='none';
                            document.getElementById("divCantidadT").style.display='none';
                            document.getElementById("btnFin").style.display='none';
                            document.getElementById("btnSegundos").style.display='none';
                        }
                    }

                }
                document.getElementById("txtCantidadTotal").innerText = data.data[0].PrimerConteo;
                document.getElementById("divMarbete").style.display='flex';
                document.getElementById("divMarbete").style.flexDirection='column';
                document.getElementById("marbeteCompleto").style.display='block';

                document.getElementById("divMarbete").scrollIntoView({behavior: "smooth"});
                verificacionRegistroTotal();
            }else{
                Swal.fire({
                    title: "El marbete no se encontro en su area o no existe.",
                    text: "Verificalo con nuevamente con tu equipo",
                    icon: "error"
                });
            }



        });
    }

    var cantidad;

    function verificacionRegistroTotal() {

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

                        document.getElementById("imagenVerificador").src = 'https://grammermx.com/Fotos/<?php echo $nomina;?>.png';
                        document.getElementById("lblNombreVerificador").innerText = '<?php echo $nombre;?>';

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
                    document.getElementById('lblMontoTotal').innerText = parseFloat(costoUnitario*cantidad).toFixed(2);
                    document.getElementById('txtUnidadMedida').innerText = data.data[i].UM;
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

    async function enviarDatos() {
        var cantidad = document.getElementById("txtCantidad").value;
        var cantidadAnterior = document.getElementById("lblCantidad").innerText;

        if (cantidad === cantidadAnterior || await confirmarCambio()) {
            enviarSolicitud('<?php echo $nomina;?>-<?php echo $nombre;?>', marbete+'.1', cantidad,auxConteo);
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

        fetch('https://grammermx.com/Logistica/Inventario2024/dao/actualizarMarbeteProduccion.php', {
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

    function enviarSegundos() {
        var formData = new FormData();
        formData.append('nombre', '<?php echo $nomina;?>-<?php echo $nombre;?>');
        formData.append('folioMarbete', marbete);

        fetch('https://grammermx.com/Logistica/Inventario2024/dao/mandarSegundos.php', {
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

</script>
</body>
</html>