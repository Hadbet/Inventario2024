
<?php
session_start();
/*
if ($_SESSION["nominaCurso"] == "" && $_SESSION["nominaCurso"]== null && $_SESSION["rol"]== "" && $_SESSION["rol"]== null) {
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=login.html'>";
    session_destroy();
}else{
    session_start();
    $rol =$_SESSION['rol'];
    $area =$_SESSION['area'];
}*/

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

        <div class="container-fluid" id="pasoUno">
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
                                            <label for="txtFolio">Escanea el marbete</label>
                                            <div id="reader" width="600px"></div>
                                            <input type="text" class="form-control"
                                                   id="scanner_input" autocomplete="off">
                                            <br>
                                       </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <br>
                                            <button class="btn btn-success text-white mt-2" onclick="escaneo()">Escanear</button>
                                        </div>
                                    </div>
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
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Paso 2 : Escaneo de Storage Unit</h2>
                    <div class="card shadow mb-4">
                            <div class="card-header">
                                <strong id="Ubicacion" class="card-title h4"></strong>
                                <button
                                        class="btn btn-info float-right text-white" data-toggle="modal" data-target=".modal-right">Caja Abierta<span
                                            class="fe fe-chevron-right fe-16 ml-2"></span></button>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <br>
                                            <button class="btn btn-success text-white mt-2" onclick="agregarFila()">Activar Escaner</button>
                                        </div>
                                    </div>
                                </div>
                                <table id="data-table" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Storage Unit</th>
                                        <th>Numero Parte</th>
                                        <th>Cantidad</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="submit"
                                        class="btn mb-2 btn-success float-right text-white" onclick="enviarDatos()">Finalizar Captura<span
                                            class="fe fe-chevron-right fe-16 ml-2"></span></button>
                            </div>
                    </div> <!-- / .card -->
                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->







            <div class="container-fluid" id="pasoTres" style="display: none">
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="mb-3"><strong>Verificacion</strong></p>

                            <label for="basic-url">Cantidad</label>
                            <div class="input-group mb-3">
                                <input type="text" id="txtCantidad" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" autocomplete="off">
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
                                    <span class="card-title">Numero de parte</span>
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
                            <button id="btnFin" class="btn mb-2 btn-success float-right text-white" onclick="enviarDatosPro()">Finalizar Captura<span
                                        class="fe fe-chevron-right fe-16 ml-2" ></span></button>
                        </div> <!-- .card-body -->
                    </div> <!-- .card -->
                </div> <!-- .col -->

            </div> <!-- .row -->
        </div> <!-- .container-fluid -->







    </main> <!-- main -->

</div> <!-- .wrapper -->

<div class="modal fade modal-right modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">Gracias por confirma llena los siguientes datos.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div id="readerAbierto" width="600px"></div>
                    <button onclick="escaneoUnitAbierto()" id="" class="btn btn-primary">Activar escaner</button>
                <br><br>
                    <label for="txtFolio">Storage Unit</label>
                    <input type="text" class="form-control"
                           id="txtStorageUnitA" name="txtStorageUnitA" value="">
                    <br>
                    <label for="txtFolio">Ingresar NP</label>
                    <input type="text" class="form-control"
                           id="txtNumeroParteA" name="txtNumeroParteA" value="">
                    <br>
                    <label for="txtFolio">Ingresar la cantidad</label>
                    <input type="text" class="form-control"
                           id="txtCantidadA" name="txtCantidadA" value="">
                    <br>
                    <button onclick="cargaCajaAbierta()" id="btnEnviarNuevos" class="btn btn-primary">Enviar</button>
            </div>
            <div class="modal-footer">
                <button id="btnCerrarModal" type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php include 'estaticos/scriptEstandar.php'; ?>

<script src="js/apps.js"></script>
<script src="assets/scanapp.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>

<script>

    let html5QrcodeScanner;
    let html5QrcodeScannerUnit;
    let html5QrcodeScannerUnitA;

    var numeroParte;
    var storageBin;

    var numeroParteUnit;
    var cantidad;
    var addedStorageUnits = {};

    var auxConteo="3";

    function manualMarbete() {

        var marbete = parseInt(document.getElementById("scanner_input").value.split('.')[0], 10);
        var conteoM = document.getElementById("scanner_input").value.split('.')[1];

        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaMarbeteCordinadores.php?marbete='+marbete, function (data) {
            for (var i = 0; i < data.data.length; i++) {
                if (auxConteo===conteoM){
                    if (data.data[i].FolioMarbete) {
                        if (data.data[i].Estatus === '1'){
                            if (data.data[i].StorageUnit === 'NA'){

                                numeroParte=data.data[0].NumeroParte;
                                storageBin=data.data[0].StorageBin;
                                cantidad=data.data[0].SegundoConteo;
                                var usuario = data.data[0].Usuario;
                                var separado = usuario.split("-"); // Esto dividirá la cadena en dos partes en el lugar donde se encuentra el guión.
                                var numeroNomina = separado[0]; // Esto te dará la primera parte, que es el número de nómina.
                                var nombre = separado[1]; // Esto te dará la segunda parte, que es el nombre.
                                document.getElementById("lblNombreCapturador").innerText = nombre;
                                document.getElementById("imagenCapturador").src = 'https://grammermx.com/Fotos/'+numeroNomina+'.png';
                                document.getElementById("reader").style.display = 'none';
                                document.getElementById("lblFolio").innerHTML = marbete;
                                document.getElementById("pasoTres").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                document.getElementById("lblStorageBin").innerText = storageBin;
                                document.getElementById("lblNumeroParte").innerText = numeroParte;
                                document.getElementById("lblCantidad").innerText = data.data[0].SegundoConteo;
                                cargaPrimer(numeroParte);

                            }else{
                                numeroParte=data.data[i].NumeroParte;
                                storageBin=data.data[i].StorageBin;
                                document.getElementById("reader").style.display = 'none';
                                document.getElementById("Ubicacion").innerHTML = "Ubicación : "+storageBin;
                                document.getElementById("pasoDos").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                cantidad =  data.data[i].CantidadStorage;
                                var table = document.getElementById("data-table");
                                var row = table.insertRow(-1);
                                var cell1 = row.insertCell(0);
                                var cell2 = row.insertCell(1);
                                var cell3 = row.insertCell(2);
                                cell1.innerHTML = data.data[i].StorageUnit;
                                cell2.innerHTML = numeroParte;
                                cell3.innerHTML = cantidad;
                                cell1.contentEditable = "true";
                                cell2.contentEditable = "true";
                                cell3.contentEditable = "true";

                            }
                        }else{
                            Swal.fire({
                                title: "El marbete no pertenece al 3 conteo",
                                text: "Escanea otro marbete",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "El marbete no esta cargado",
                            text: "Verificalo con la mesa central",
                            icon: "error"
                        });
                    }
                }else{
                    Swal.fire({
                        title: "El marbete no pertenece al conteo "+auxConteo,
                        text: "Verificalo con tu lider",
                        icon: "error"
                    });
                }

            }

            html5QrcodeScanner.clear();
            html5QrcodeScanner.pause();
        });
    }

    function cargaPrimer(numeroParte) {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaParte.php?parte='+numeroParte, function (data) {
            for (var i = 0; i < data.data.length; i++) {
                if (data.data[i].GrammerNo) {
                    document.getElementById('lblDescripcion').innerText = data.data[i].Descripcion;
                    document.getElementById('txtUnidadMedida').innerText = data.data[i].UM;
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
        });
    }

    function agregarFila() {
        var table = document.getElementById("data-table");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);

        cell1.contentEditable = "true";
        cell2.contentEditable = "true";
        cell3.contentEditable = "true";
        cell1.innerHTML = "";
        cell2.innerHTML = "";
        cell3.innerHTML = "";

    }

    function lecturaCorrecta(decodedText, decodedResult) {

        var marbete = decodedText.split('.')[0]
        var conteoM = decodedText.split('.')[1];

        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaMarbete.php?marbete='+marbete, function (data) {
            for (var i = 0; i < data.data.length; i++) {
                if (auxConteo===conteoM && conteoM==="1"){
                    if (data.data[i].FolioMarbete) {
                        if (data.data[i].Estatus === '0'){
                            if (data.data[i].Area === '<?php echo $area;?>'){
                                numeroParte=data.data[i].NumeroParte;
                                storageBin=data.data[i].StorageBin;
                                console.log(`Code matched = ${decodedText}`, decodedResult);
                                document.getElementById("scanner_input").value = decodedText;
                                document.getElementById("reader").style.display = 'none';
                                document.getElementById("Ubicacion").innerHTML = "Ubicación : "+storageBin;
                                document.getElementById("pasoDos").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                html5QrcodeScanner.clear();
                                html5QrcodeScanner.pause();
                            }else{
                                Swal.fire({
                                    title: "El marbete no pertenece al area",
                                    text: "Escanea otro marbete",
                                    icon: "error"
                                });
                            }
                        }else{
                            Swal.fire({
                                title: "El marbete ya fue registrado",
                                text: "Escanea otro marbete",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "El marbete no esta cargado",
                            text: "Verificalo con la mesa central",
                            icon: "error"
                        });
                    }
                }else if(auxConteo===conteoM && conteoM==="2"){
                    if (data.data[i].FolioMarbete) {
                        if (data.data[i].SegFolio === '2'){
                            if (data.data[i].Area === '<?php echo $area;?>'){
                                numeroParte=data.data[i].NumeroParte;
                                storageBin=data.data[i].StorageBin;
                                console.log(`Code matched = ${decodedText}`, decodedResult);
                                document.getElementById("scanner_input").value = decodedText;
                                document.getElementById("reader").style.display = 'none';
                                document.getElementById("Ubicacion").innerHTML = "Ubicación : "+storageBin;
                                document.getElementById("pasoDos").style.display = 'block';
                                document.getElementById("pasoUno").style.display = 'none';
                                html5QrcodeScanner.clear();
                                html5QrcodeScanner.pause();
                            }else{
                                Swal.fire({
                                    title: "El marbete no pertenece al area",
                                    text: "Escanea otro marbete",
                                    icon: "error"
                                });
                            }
                        }else{
                            Swal.fire({
                                title: "El marbete no pertenece al segundo conteo o ya fue registrado",
                                text: "Escanea otro marbete",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "El marbete no esta cargado",
                            text: "Verificalo con la mesa central",
                            icon: "error"
                        });
                    }
                }else{
                    Swal.fire({
                        title: "El marbete no pertenece al conteo "+auxConteo,
                        text: "Verificalo con tu lider",
                        icon: "error"
                    });
                }
            }
        });

    }

    function errorLectura(error) {
        console.warn(`Code scan error = ${error}`);
    }

    function escaneo() {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        document.getElementById("reader").style.display = 'block';
        html5QrcodeScanner.render(lecturaCorrecta, errorLectura);
    }


    function storageUnitManual() {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaStorageUnit.php?storageUnit='+document.getElementById("txtStorageUnit").value+'&bin='+storageBin, function (data) {
            if (data.Estatus) {
                Swal.fire({
                    title: data.Estatus,
                    text: "Escanea otro storage unit",
                    icon: "error"
                });
            } else {
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data[i].Id_StorageUnit) {
                        numeroParteUnit = data.data[i].Numero_Parte;
                        if (numeroParteUnit === numeroParte) {

                            if (addedStorageUnits[data.data[i].Id_StorageUnit]) {
                                Swal.fire({
                                    title: "El Storage Unit ya fue escaneado",
                                    text: "Unit : " + data.data[i].Id_StorageUnit,
                                    icon: "error"
                                });
                                return;
                            }

                            addedStorageUnits[data.data[i].Id_StorageUnit] = {
                                numeroParte: data.data[i].Numero_Parte,
                                cantidad: data.data[i].Cantidad
                            };

                            cantidad = data.data[i].Cantidad;
                            var table = document.getElementById("data-table");
                            var row = table.insertRow(-1);
                            var cell1 = row.insertCell(0);
                            var cell2 = row.insertCell(1);
                            var cell3 = row.insertCell(2);
                            cell1.innerHTML = data.data[i].Id_StorageUnit;
                            cell2.innerHTML = numeroParteUnit;
                            cell3.innerHTML = cantidad;

                            Swal.fire({
                                title: "Storage unit escaneado",
                                text: "Unit : " + data.data[i].Id_StorageUnit,
                                icon: "success"
                            });
                            document.getElementById("txtStorageUnit").value = '';
                        } else {
                            Swal.fire({
                                title: "El número de parte no corresponde",
                                text: "Escanea el storage unit correcto",
                                icon: "error"
                            });
                            document.getElementById("txtStorageUnit").value = '';
                        }
                    } else {
                        Swal.fire({
                            title: "El storage unit no es correcto",
                            text: "Escanea el storage unit correcto",
                            icon: "error"
                        });
                        document.getElementById("txtStorageUnit").value = '';
                    }
                }
            }
        });
    }


    function lecturaCorrectaUnit(decodedText, decodedResult) {
        $.getJSON('https://grammermx.com/Inventario/dao/consultaStorageUnit.php?storageUnit='+decodedText, function (data) {

            if (data.Estatus) {
                Swal.fire({
                    title: "El storage unit ya existe",
                    text: "Escanea otro storage unit",
                    icon: "error"
                });
            } else {
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data[i].Id_StorageUnit) {
                        numeroParteUnit=data.data[i].Numero_Parte;
                        if (numeroParteUnit===numeroParte){
                            if (addedStorageUnits[data.data[i].Id_StorageUnit]) {
                                return;
                            }

                            addedStorageUnits[data.data[i].Id_StorageUnit] = {
                                numeroParte: data.data[i].Numero_Parte,
                                cantidad: data.data[i].Cantidad
                            };

                            cantidad=data.data[i].Cantidad;
                            console.log(`Code matched = ${decodedText}`, decodedResult);
                            document.getElementById("txtStorageUnit").value = decodedText;
                            //document.getElementById("readerDos").style.display = 'none';

                            var table = document.getElementById("data-table");
                            var row = table.insertRow(-1); // Crea una nueva fila al final de la tabla
                            var cell1 = row.insertCell(0); // Crea una nueva celda en la fila
                            var cell2 = row.insertCell(1); // Crea otra nueva celda en la fila
                            var cell3 = row.insertCell(2); // Crea otra nueva celda en la fila
                            cell1.innerHTML = data.data[i].Id_StorageUnit;
                            cell2.innerHTML = numeroParteUnit;
                            cell3.innerHTML = cantidad;
                            //html5QrcodeScannerUnit.clear();
                            //html5QrcodeScannerUnit.pause();
                            Swal.fire({
                                title: "Storage unit escaneado",
                                text: "Unit : "+data.data[i].Id_StorageUnit,
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                title: "El número de parte no corresponde",
                                text: "Escanea el storage unit correcto",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "El storage unit no es correcto",
                            text: "Escanea el storage unit correcto",
                            icon: "error"
                        });
                    }
                }
            }
        });
    }

    function errorLecturaUnit(error) {
        console.warn(`Code scan error = ${error}`);
    }

    function escaneoUnit() {
        html5QrcodeScannerUnit = new Html5QrcodeScanner(
            "readerDos",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        document.getElementById("readerDos").style.display = 'block';
        html5QrcodeScannerUnit.render(lecturaCorrectaUnit, errorLecturaUnit);
    }

    function enviarDatos() {
        var comentarios = "";
        var folioMarbete = document.getElementById("scanner_input").value;

        var table = document.getElementById("data-table");
        var rows = table.getElementsByTagName("tr");
        var addedStorageUnits = {};

        for (var i = 1; i < rows.length; i++) { // Empezamos en 1 para saltar el encabezado de la tabla
            var cells = rows[i].getElementsByTagName("td");
            var storageUnit = cells[0].innerText;
            var numeroParte = cells[1].innerText;
            var cantidad = cells[2].innerText;

            addedStorageUnits[storageUnit] = {
                numeroParte: numeroParte,
                cantidad: cantidad
            };
        }


        var storageUnits = addedStorageUnits;

        var formData = new FormData();
        formData.append('nombre', '<?php echo $nomina;?>-<?php echo $nombre;?>');
        formData.append('comentarios', comentarios);
        formData.append('storageUnits', JSON.stringify(storageUnits));
        formData.append('folioMarbete', folioMarbete);

        fetch('https://grammermx.com/Logistica/Inventario/dao/guardarMarbete.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                } else {
                    console.log("Hubo un error en la operación");
                    console.log("Las unidades de almacenamiento que fallaron son: ", data.failedUnits);
                }
            });
    }




    function lecturaCorrectaUnitAbierto(decodedText, decodedResult) {
        $.getJSON('https://grammermx.com/Inventario/dao/consultaStorageUnit.php?storageUnit='+decodedText, function (data) {

            if (data.Estatus) {
                Swal.fire({
                    title: "El storage unit ya existe",
                    text: "Escanea otro storage unit",
                    icon: "error"
                });
            } else {
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data[i].Id_StorageUnit) {
                        numeroParteUnit=data.data[i].Numero_Parte;
                        if (numeroParteUnit===numeroParte){
                            document.getElementById("txtStorageUnitA").value = decodedText;
                            document.getElementById("txtNumeroParteA").value = numeroParteUnit;
                            html5QrcodeScannerUnitA.clear();
                            html5QrcodeScannerUnitA.pause();
                            document.getElementById("readerAbierto").style.display = 'none';
                            Swal.fire({
                                title: "Storage unit escaneado",
                                text: "Unit : "+data.data[i].Id_StorageUnit,
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                title: "El número de parte no corresponde",
                                text: "Escanea el storage unit correcto",
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "El storage unit no es correcto",
                            text: "Escanea el storage unit correcto",
                            icon: "error"
                        });
                    }
                }
            }
        });
    }

    function errorLecturaAbierto(error) {
        console.warn(`Code scan error = ${error}`);
    }

    function escaneoUnitAbierto() {
        html5QrcodeScannerUnitA = new Html5QrcodeScanner(
            "readerAbierto",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        document.getElementById("readerAbierto").style.display = 'block';
        html5QrcodeScannerUnitA.render(lecturaCorrectaUnitAbierto, errorLecturaAbierto);
    }

    function cargaCajaAbierta() {

        var storageA = document.getElementById("txtStorageUnitA").value;
        var numeroParteA = document.getElementById("txtNumeroParteA").value;
        var cantidadA = document.getElementById("txtCantidadA").value;

        if (addedStorageUnits[storageA]) {
            Swal.fire({
                title: "Storage unit ya esta ingresado en la tabla",
                text: "Verifique",
                icon: "error"
            });
            return;
        }

        addedStorageUnits[storageA] = {
            numeroParte: numeroParteA,
            cantidad: cantidadA
        };

            var table = document.getElementById("data-table");
            var row = table.insertRow(-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.innerHTML = storageA;
            cell2.innerHTML = numeroParteA;
            cell3.innerHTML = cantidadA;

            document.getElementById("btnCerrarModal").click();

            document.getElementById("txtStorageUnitA").value = "";
            document.getElementById("txtNumeroParteA").value = "";
            document.getElementById("txtCantidadA").value = "";

            Swal.fire({
                title: "Storage unit escaneado",
                text: "Unit : " + storageA,
                icon: "success"
            });
            document.getElementById("txtStorageUnit").value = '';
    }

    async function enviarDatosPro() {
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
                title: "¿Quieres guardar los cambios?",
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

</script>
</body>
</html>