<?php

session_start();
$rol = $_SESSION['rol'];
$area = $_SESSION['area'];
$areaNombre = $_SESSION['AreaNombre'];
$bin = $_SESSION['StBin'];
$nomina = $_SESSION['nomina'];
$nombre = $_SESSION['nombre'];

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
    <link rel="icon" href="favicon.ico">
    <title>GRAMMER INVENTARIO</title>
    <?php include 'estaticos/stylesEstandar.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="vertical  light  ">
<div class="wrapper">
    <?php
    require_once('estaticos/navegador.php');
    ?>
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">

                    <h2 class="mb-2 page-title">Importar marbetes o Rellenar txt</h2>
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h2 class="text-center">Tabla Bitacora / Carga txt sin storage unit</h2>
                                    <button class="btn btn-success text-right btnExcel" id="btnExcelBitacora"> Cargar
                                        Excel Bitacora
                                    </button>
                                    <input type="file" id="fileInputBitacora" accept=".xlsx, .xls"
                                           style="display: none;"/>
                                    <button class="btn btn-secondary text-right btnExcel" id="tooltipBitacora"><i
                                                class="far fa-question-circle position-absolute"></i>? Ejemplo excel
                                    </button>

                                    <button class="btn btn-primary text-right btnExcel" id="btnTxtBitacora"> Actualizar
                                        txt
                                    </button>
                                    <input type="file" id="fileInputTxt" accept=".txt" style="display: none;"/>
                                    <br><br>
                                    <!-- table -->
                                    <table class="table datatables" id="tablaBitacora">
                                        <thead>
                                        <tr>
                                            <th>Id_Bitacora</th>
                                            <th>NúmeroParte</th>
                                            <th>FolioMarbete</th>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Estatus</th>
                                            <th>PrimerConteo</th>
                                            <th>SegundoConteo</th>
                                            <th>TercerConteo</th>
                                            <th>Comentario</th>
                                            <th>StorageBin</th>
                                            <th>StorageType</th>
                                            <th>Área</th>
                                        </tr>
                                        </thead>
                                        <tbody id="bodyBitacora"></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> <!-- simple table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->


                <div class="col-12">
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h2 class="text-center">Tabla Storage / Carga txt</h2>
                                    <button class="btn btn-success text-right btnExcel" id="btnExcelStorage"> Cargar
                                        Excel Storage
                                    </button>
                                    <input type="file" id="fileInputStorage" accept=".xlsx, .xls"
                                           style="display: none;"/>
                                    <button class="btn btn-secondary text-right btnExcel" id="tooltipStorage"><i
                                                class="far fa-question-circle position-absolute"></i>? Ejemplo excel
                                    </button>

                                    <button class="btn btn-primary text-right btnExcel" id="btnTxtStorage"> Actualizar
                                        txt
                                    </button>
                                    <input type="file" id="fileInputTxtS" accept=".txt" style="display: none;"/>
                                    <br><br>
                                    <!-- table -->
                                    <table class="table table-striped table-bordered mt-3" id="tablaStorage">
                                        <thead>
                                        <tr>
                                            <th>id_StorageUnit</th>
                                            <th>Numero_Parte</th>
                                            <th>Cantidad</th>
                                            <th>Storage_Bin</th>
                                            <th>Storage_Type</th>
                                        </tr>
                                        </thead>
                                        <tbody id="bodyStorage"></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> <!-- simple table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->


                <div class="col-12">
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h2 class="text-center">Carga y descarga de reporte Inventario</h2>
                                    <button class="btn btn-success text-right btnExcel" id="btnExcelExcelQty"> Cargar
                                        Excel Inventario
                                    </button>
                                    <input type="file" id="fileInputExcelQty" accept=".xlsx, .xls"
                                           style="display: none;"/>
                                </div>
                            </div>
                        </div> <!-- simple table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->

            </div> <!-- .row -->
        </div> <!-- .container-fluid -->

    </main> <!-- main -->
</div> <!-- .wrapper -->

<?php include 'estaticos/scriptEstandar.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="js/apps.js"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');


</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        //cargarDatosParte();
        //cargarDatosBin();
        //cargarDatosStorage();

    });

    document.getElementById("tooltipBitacora").addEventListener("click", function () {
        mostrarImagenTooltip(
            "tooltipBitacora",
            "https://grammermx.com/excelInventario/imgs/bitacora.png",
            320,
            140
        );
    });

    document.getElementById("tooltipStorage").addEventListener("click", function () {
        mostrarImagenTooltip(
            "tooltipStorage",
            "https://grammermx.com/excelInventario/imgs/storage.png",
            320,
            100
        );
    });

</script>

<!-- -Archivos de jQuery-->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- JAVASCRIPT FILES -->
<script src="js/excel.js"></script>
<script src="js/archivoTexto.js"></script>

<!-- BOOSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>


<!-- DataTable -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</body>
</html>