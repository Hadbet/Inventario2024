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

    <link rel="stylesheet" href="css/generales.css">
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
                    <h2 class="mb-2 page-title">Lista de marbetes general</h2>
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <!-- table -->
                                    <table class="table datatables" id="dataTable-1">
                                        <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Número de Parte</th>
                                            <th>Primer Conteo</th>
                                            <th>Segundo Conteo</th>
                                            <th>Tercer Conteo</th>
                                            <th>Comentario</th>
                                            <th>StorageBin</th>
                                            <th>StorageType</th>
                                            <th>Usuario</th>
                                            <th>Estatus</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Número de Parte</th>
                                            <th>Primer Conteo</th>
                                            <th>Segundo Conteo</th>
                                            <th>Tercer Conteo</th>
                                            <th>Comentario</th>
                                            <th>StorageBin</th>
                                            <th>StorageType</th>
                                            <th>Usuario</th>
                                            <th>Estatus</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        </tbody>
                                    </table>
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

<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<script>
    $.ajax({
        url: 'https://grammermx.com/Logistica/Inventario/dao/consultaAuditor.php', // Reemplaza esto con la URL de tus datos
        dataType: 'json',
        success: function(data) {
            var table = $('#dataTable-1').DataTable({
                data: data.data,
                columns: [
                    { data: 'FolioMarbete' },
                    { data: 'NumeroParte' },
                    { data: 'PrimerConteo' },
                    { data: 'SegundoConteo' },
                    { data: 'TercerConteo' },
                    { data: 'Comentario' },
                    { data: 'StorageBin' },
                    { data: 'StorageType' },
                    { data: 'Usuario' },
                    { data: 'Estatus' }
                ],
                autoWidth: true,
                "lengthMenu": [
                    [16, 32, 64, -1],
                    [16, 32, 64, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-sm copyButton'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-sm csvButton'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm excelButton'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm pdfButton'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm printButton'
                    }
                ],
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var input = document.createElement("input");
                        input.className = 'form-control form-control-sm';
                        $(input).appendTo($(column.footer()).empty())
                            .on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });
                }
            });
        }
    });
</script>
<script src="js/apps.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag()
    {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');

    function verFormato(curso,horario,fecha) {
        var link = "https://grammermx.com/RH/Cursos/pruebaPDF.php?curso="+curso+"&horario="+horario+"&fecha="+fecha;
        window.open(link, '_blank');
    }
</script>
</body>
</html>