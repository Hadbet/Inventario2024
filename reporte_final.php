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
    <style>
    </style>
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

                    <h2 class="mb-2 page-title">Reporte Final Diferencias</h2>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <strong class="card-title">Filtros detallados</strong>
                                </div>
                                <div class="card-body">
                                    <form class="form-inline">

                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Diferencia en pesos mayor a:</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">$</div>
                                            </div>
                                            <input type="text" class="form-control" id="inlineFormInputGroupUsername2" >
                                        </div>

                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Diferencias en cantidad mayor a:</label>
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2">

                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Número Parte</label>
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2">

                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Folio</label>
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2">

                                        <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow bg-danger text-white border-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-danger-light">
                            <i class="fe fe-16 fe-trending-down text-white mb-0"></i>
                          </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small mb-0 text-white">Partes con negativos</p>
                                            <span class="h3 mb-0 text-white" id="partesNegativo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow bg-success text-white border-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-success-light">
                            <i class="fe fe-16 fe-trending-up text-white mb-0"></i>
                          </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small mb-0 text-white">Partes con positivos</p>
                                            <span class="h3 mb-0 text-white" id="partesPositivo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow bg-danger text-white border-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-danger-light">
                            <i class="fe fe-16 fe-alert-circle text-white mb-0"></i>
                          </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small mb-0 text-white" >Costo Negativo</p>
                                            <span class="h3 mb-0 text-white" id="costoNegativo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow bg-success text-white border-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-success-light">
                            <i class="fe fe-16 fe-dollar-sign text-white mb-0"></i>
                          </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small mb-0 text-white">Costo Positivas</p>
                                            <span class="h3 mb-0 text-white" id="costoPositivo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <button id="export-button" class="btn btn-success text-white">Exportar a Excel</button>
                                    <button id="copy-button" class="btn btn-info">Copiar al portapapeles</button>
                                    <br><br>
                                    <!-- table -->
                                    <table class="table datatables table-fixed" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>P</th>
                                            <th>L</th>
                                            <th>M</th>
                                            <th>GrammerNo</th>
                                            <th>Descripción</th>
                                            <th>UM</th>
                                            <th>Costo/Und</th>
                                            <th>StLocation</th>
                                            <th>StBin</th>
                                            <th>Folio</th>
                                            <th>Sap</th>
                                            <th>Conteo</th>
                                            <th>Dif</th>
                                            <th>Costo</th>
                                            <th>Comentario</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Los datos se insertarán aquí desde el script JavaScript -->
                                        </tbody>
                                    </table>

                                    <!-- Button trigger modal -->
                                    <button style="display: none" type="button" class="btn mb-2 btn-outline-success"
                                            data-toggle="modal" data-target="#verticalModal" id="btnModal"> Launch demo
                                        modal
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="verticalModal" tabindex="-1" role="dialog"
                                         aria-labelledby="verticalModalTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="verticalModalTitle">Modificación de
                                                        usuarios</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Id:</label>
                                                        <input type="text" class="form-control" id="txtIdM" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label">Usuario:</label>
                                                        <input type="text" class="form-control" id="txtUsuarioM">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label">Password:</label>
                                                        <input type="password" class="form-control" id="txtPasswordM">
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn mb-2 btn-success text-white"
                                                            onclick="actualizarDatos()">Actualizar
                                                    </button>
                                                    <button type="button" class="btn mb-2 btn-secondary"
                                                            data-dismiss="modal" id="btnCloseM">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/FileSaver/FileSaver.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/js-xlsx/xlsx.core.min.js"></script>
<script>

    estatusConteo();
    function estatusConteo() {
        $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaReporteFinalDetalles.php', function (data) {
            for (var i = 0; i < data.data.length; i++) {

                document.getElementById("costoNegativo").innerText = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.data[i].Costo_Total_Negativo);
                document.getElementById("costoPositivo").innerText = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.data[i].Costo_Total_Positivo);
                document.getElementById("partesNegativo").innerText=data.data[i].Cantidad_Total_Negativa;
                document.getElementById("partesPositivo").innerText=data.data[i].Cantidad_Total_Positiva;

            }
        });
    }

    $('#data-table').find('td').each(function(){
        var text = $(this).text();
        if (!isNaN(text)) {
            $(this).text(text.toString());
        }
    });

    $('#copy-button').click(function() {
        var range = document.createRange();
        range.selectNode(document.getElementById('data-table'));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
    });

    $('#export-button').click(function() {
        $('#data-table').tableExport({
            type:'xlsx',
            fileName: 'reporte_final_inventario',
            displayTableName: true,
            exportHiddenCells: false,
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'reporte_final_inventario',
            sheetname: 'reporte_final_inventario',
            bootstrap: false,
            exportButtons: true,
            position: 'bottom',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: false,
            RTL: false,
            sheetnames: false,
            onMsoNumberFormat: function(cell, row, col) {
                if (col === 3) return '\\@';
            }
        });
    });

    function inicioTabla() {
        $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaReporteFinal.php', function (data) {
            var table = document.getElementById("data-table");
            var totalSap = 0;
            var totalConteo = 0;
            for (var i = 0; i < data.data.length; i++) {
                totalSap += parseFloat(data.data[i].Total_InventarioSap);
                totalConteo += parseFloat(data.data[i].Total_Bitacora_Inventario);
                var row = table.insertRow(-1); // Crea una nueva fila al final de la tabla
                var cell1 = row.insertCell(0); // Crea una nueva celda en la fila
                var cell2 = row.insertCell(1); // Crea otra nueva celda en la fila
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                var cell5 = row.insertCell(4);
                var cell6 = row.insertCell(5);
                var cell7 = row.insertCell(6);
                var cell8 = row.insertCell(7);
                var cell9 = row.insertCell(8);
                var cell10 = row.insertCell(9);
                var cell11 = row.insertCell(10);
                var cell12 = row.insertCell(11);
                var cell13 = row.insertCell(12);
                var cell14 = row.insertCell(13);
                var cell15 = row.insertCell(14);
                cell1.innerHTML = i === 0 ? "*" : ""; // P
                cell2.innerHTML = i === 0 ? "" : "*"; // L
                cell3.innerHTML = i === 0 ? "" : ""; // M
                cell4.innerHTML = data.data[i].GrammerNo; // GrammerNo
                cell5.innerHTML = data.data[i].Descripcion; // Descripcion
                cell6.innerHTML = data.data[i].UM; // UM
                cell7.innerHTML = data.data[i].CostoUnitario; // Costo/Und
                cell8.innerHTML = ""; // StLocation
                cell9.innerHTML = data.data[i].STBin; // StBin
                cell10.innerHTML = data.data[i].FolioMarbete; // Folio
                cell11.innerHTML = data.data[i].Total_InventarioSap; // Sap
                cell12.innerHTML = data.data[i].Total_Bitacora_Inventario; // Conteo
                cell13.innerHTML = ""; // Dif
                cell14.innerHTML = ""; // Costo
                cell15.innerHTML = data.data[i].Comentario; // Comentario
            }
        });
    }
</script>
<script src="js/apps.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');

    async function fetchData(url) {
        return new Promise((resolve, reject) => {
            $.getJSON(url, function (data) {
                resolve(data);
            }).fail(function (jqxhr, textStatus, error) {
                reject(error);
            });
        });
    }

    async function processAndAppendData(data, type) {
        let formattedData = [];
        for (let i = 0; i < data.data.length; i++) {
            let item = data.data[i];
            let formattedItem = {
                P: type === 'uno' ? '*' : '',
                L: type === 'dos' ? '*' : '',
                M: type === 'tres' ? '*' : '',
                GrammerNo: item.GrammerNo,
                Descripcion: item.Descripcion,
                UM: item.UM,
                Costo_Unitario: item.Costo_Unitario,
                StLocation: '',
                StBin: type === 'uno' ? '' : item.STBin || item.StorageBin,
                Folio: type === 'tres' ? item.FolioMarbete : '',
                Sap: type === 'tres' ? '' : item.Total_InventarioSap,
                Conteo: type === 'tres' ? item.Total_Conteo : item.Total_Bitacora_Inventario,
                Dif: type === 'tres' ? '' : item.Diferencia,
                Costo: '', // Aquí debes calcular el costo
                Comentario: type === 'tres' ? item.Comentario : ''
            };
            formattedData.push(formattedItem);
        }
        return formattedData;
    }

    async function loadData() {
        try {
            const dataUno = await fetchData('https://grammermx.com/Logistica/Inventario2024/dao/consultaReporteFinalUno.php');
            const dataDos = await fetchData('https://grammermx.com/Logistica/Inventario2024/dao/consultaReporteFinalDos.php');
            const dataTres = await fetchData('https://grammermx.com/Logistica/Inventario2024/dao/consultaReporteFinalTres.php');

            let formattedData = [];
            formattedData = formattedData.concat(await processAndAppendData(dataUno, 'uno'));
            formattedData = formattedData.concat(await processAndAppendData(dataDos, 'dos'));
            formattedData = formattedData.concat(await processAndAppendData(dataTres, 'tres'));

            // Ordenar datos por GrammerNo
            formattedData.sort(function(a, b) {
                var grammerNoCompare = a.GrammerNo.localeCompare(b.GrammerNo);
                if (grammerNoCompare != 0) {
                    // Si GrammerNo no es igual, ordena por GrammerNo
                    return grammerNoCompare;
                } else {
                    // Si GrammerNo es igual, ordena por StBin
                    return a.StBin.localeCompare(b.StBin);
                }
            });

            for (let i = 0; i < formattedData.length; i++) {
                let item = formattedData[i];
                $('#data-table tbody').append(
                    '<tr>' +
                    '<td>' + (item.P || '') + '</td>' +
                    '<td>' + (item.L || '') + '</td>' +
                    '<td>' + (item.M || '') + '</td>' +
                    '<td>' + (item.GrammerNo || '') + '</td>' +
                    '<td>' + (item.Descripcion || '') + '</td>' +
                    '<td>' + (item.UM || '') + '</td>' +
                    '<td>' + (parseFloat(item.Costo_Unitario || 0).toFixed(4)) + '</td>' +
                    '<td>' + (item.StLocation || '') + '</td>' +
                    '<td>' + (item.StBin || '') + '</td>' +
                    '<td>' + (item.Folio || '') + '</td>' +
                    '<td>' + (parseFloat(item.Sap || 0).toFixed(2)) + '</td>' +
                    '<td>' + (parseFloat(item.Conteo || 0).toFixed(2)) + '</td>' +
                    '<td>' + (parseFloat(item.Dif || 0).toFixed(2)) + '</td>' +
                    '<td>' + (parseFloat(item.Costo || 0).toFixed(4)) + '</td>' +
                    '<td>' + (item.Comentario || '') + '</td>' +
                    '</tr>'
                );
            }
        } catch (error) {
            console.error("Error loading data: ", error);
        }
    }

    loadData();

</script>
</body>
</html>