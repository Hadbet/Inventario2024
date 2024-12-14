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
                    <button type="button" id="cancelButton" class="btn mb-2 btn-danger float-right text-white">Cancelar seleccionados<span
                                class="fe fe-x-circle fe-16 ml-2"></span></button>
                    <h2 class="mb-2 page-title">Lista de marbetes</h2>
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <!-- table -->
                                    <table class="table datatables" id="dataTable-1">
                                        <thead>
                                        <tr>
                                            <th>Seleccionar</th>
                                            <th>Marbete</th>
                                            <th>Número de parte</th>
                                            <th>Usuario</th>
                                            <th>Estatus</th>
                                            <th>Conteo</th>
                                            <th>Storage Bin</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
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

<script>
    $.ajax({
        url: 'https://grammermx.com/Logistica/Inventario/dao/consultaMarbeteLider.php?area=<?php echo $area; ?>', // Reemplaza esto con la URL de tus datos
        dataType: 'json',
        success: function (data) {
            $('#dataTable-1').DataTable({
                data: data.data,
                columns: [
                    {data: 'FolioMarbete', render: function(data, type, row) {
                            return '<input type="checkbox" class="cancelCheckbox" data-foliomarbete="' + data + '">';
                        }},
                    {data: 'FolioMarbete'},
                    {data: 'NumeroParte'},
                    {data: 'Usuario'},
                    {data: 'Estatus'},
                    {data: 'PrimerConteo'},
                    {data: 'StorageBin'},
                    {data: 'Cancelar'}
                ],
                autoWidth: true,
                "lengthMenu": [ [16, 32, 64, -1], [16, 32, 64, "All"] ]
            });
        }
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        var checkboxes = document.getElementsByClassName('cancelCheckbox');
        var selectedFolioMarbetes = [];
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                selectedFolioMarbetes.push(checkboxes[i].getAttribute('data-foliomarbete'));
            }
        }
        console.log(selectedFolioMarbetes);
        cancelar(selectedFolioMarbetes);
    });

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

    function actualizarTabla() {
        $.ajax({
            url: 'https://grammermx.com/Logistica/Inventario/dao/consultaMarbeteLider.php?area=<?php echo $area; ?>', // Reemplaza esto con la URL de tus datos
            dataType: 'json',
            success: function (data) {
                var table = $('#dataTable-1').DataTable();
                table.clear();
                table.rows.add(data.data);
                table.draw();
            }
        });
    }

    async function cancelar(id) {
        const {value: comentario} = await Swal.fire({
            title: "Ingresa tus comentarios",
            input: "text",
            inputLabel: "¿Por qué lo cancelas?",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return "¡Se requiere que se explique la razón!";
                }
            }
        });

        if (comentario) {
            var formData = new FormData();
            formData.append('id', id);
            formData.append('comentario', comentario);

            fetch('https://grammermx.com/Logistica/Inventario/dao/guardarCancelacion.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    Swal.fire({
                        title: "El marbete fue cancelado",
                        text: "Gracias",
                        icon: "success"
                    });
                    actualizarTabla();
                });
        }
    }

    async function cancelar(ids) {
        const {value: comentario} = await Swal.fire({
            title: "Ingresa tus comentarios",
            input: "text",
            inputLabel: "¿Por qué lo cancelas?",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return "¡Se requiere que se explique la razón!";
                }
            }
        });

        if (comentario) {
            var formData = new FormData();
            formData.append('ids', JSON.stringify(ids));
            formData.append('comentario', comentario);

            fetch('https://grammermx.com/Logistica/Inventario/dao/masivoCancelacion.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    Swal.fire({
                        title: "Los marbetes fueron cancelados",
                        text: "Gracias",
                        icon: "success"
                    });
                    actualizarTabla();
                });
        }
    }

</script>
</body>
</html>