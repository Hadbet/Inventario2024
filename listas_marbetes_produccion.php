
<?php


include_once('dao/db/db_Inventario.php');

session_start();
$rol =$_SESSION['rol'];
$area =$_SESSION['area'];
$areaNombre =$_SESSION['AreaNombre'];
$bin =$_SESSION['StBin'];
$nombre =$_SESSION['nombre'];




$con = new LocalConector();
$conex = $con->conectar();

$stmt = $conex->prepare("SELECT 
    B.Id_Bitacora, 
    B.NumeroParte, 
    P.Descripcion,
    P.UM,
    B.FolioMarbete, 
    B.Fecha, 
    B.Usuario, 
    B.UsuarioVerificacion, 
    B.Estatus, 
    B.PrimerConteo, 
    B.SegundoConteo, 
    B.TercerConteo, 
    B.Comentario, 
    B.StorageBin, 
    B.StorageType, 
    B.Area,
    CASE
        WHEN B.TercerConteo > 0 THEN (P.Costo / P.Por) * B.TercerConteo
        WHEN B.SegundoConteo > 0 THEN (P.Costo / P.Por) * B.SegundoConteo
        ELSE (P.Costo / P.Por) * B.PrimerConteo
    END AS Monto
FROM 
    Bitacora_Inventario B
LEFT JOIN 
    Parte P ON B.NumeroParte = P.GrammerNo
WHERE B.Area = $area");
$stmt->execute();
$result = $stmt->get_result();

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
                    <div class="row">
                        <!-- Small table -->
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Detalles de captura</h2>
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="toolbar">
                                        <form class="form">
                                            <div class="form-row">

                                            </div>
                                        </form>
                                    </div>
                                    <!-- table -->
                                    <table class="table table-borderless table-hover">
                                        <thead>
                                        <tr>
                                            <th>Capturista</th>
                                            <th>Marbete</th>
                                            <th>Número Parte</th>
                                            <th>Primer Conteo</th>
                                            <th>Segundo Conteo</th>
                                            <th>Storage bin</th>
                                            <th class="w-25">Valor monetario</th>
                                            <th>Validador</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        while ($row = $result->fetch_assoc()) {

                                            $usuarioV = $row['UsuarioVerificacion'];
                                            $usuarioC = $row['Usuario'];
                                            $numeroParte = $row['NumeroParte'];
                                            $descripcion = $row['Descripcion'];
                                            $um = $row['UM'];
                                            $folioMarbete = $row['FolioMarbete'];
                                            $primerConteo = $row['PrimerConteo'];
                                            $segundoConteo = $row['SegundoConteo'];
                                            $storageBin = $row['StorageBin'];
                                            $monto = $row['Monto'];
                                            $monto = number_format($monto, 2, '.', '');
                                            $estatus = $row['Estatus'];

                                            if($usuarioV != null && $usuarioV != '') {
                                                $parts = explode('-', $usuarioV);
                                                $nominaV = $parts[0];
                                                $nombreV = isset($parts[1]) ? $parts[1] : 'Vacio';
                                                $fotoV = '<div class="col-auto">
                                                    <a href="#" class="avatar avatar-md">
                                                        <img src="https://grammermx.com/Fotos/'.$nominaV.'.png" alt="..." class="avatar-img rounded-circle">
                                                    </a>
                                                </div>
                                                <div class="col ml-n2">
                                                    <strong class="mb-1" id="lblNombre">'.$nombreV.'</strong>
                                                </div>';
                                            } else {
                                                $nominaV = 'Vacio';
                                                $nombreV = 'Vacio';
                                                if ($estatus == 5){
                                                    $fotoV = '<span class=\"badge badge-pill badge-danger text-white\">Cancelado</span>';
                                                }else{
                                                    $fotoV = '<strong class="mb-1" id="">Vacio</strong>';
                                                }
                                            }

                                            // Para Usuario
                                            if($usuarioC != null && $usuarioC != '') {
                                                $parts = explode('-', $usuarioC);
                                                $nominaC = $parts[0];
                                                $nombreC = isset($parts[1]) ? $parts[1] : 'Vacio';
                                                $fotoC = '<div class="col-auto">
                                                    <a href="#" class="avatar avatar-md">
                                                        <img src="https://grammermx.com/Fotos/'.$nominaC.'.png" alt="..." class="avatar-img rounded-circle">
                                                    </a>
                                                </div>
                                                <div class="col ml-n2">
                                                    <strong class="mb-1" id="lblNombre">'.$nombreC.'</strong>
                                                </div>';
                                            } else {
                                                $nominaC = 'Vacio';
                                                $nombreC = 'Vacio';
                                                $fotoC = '<strong class="mb-1" id="">Vacio</strong>';
                                            }


                                            $estatusNombre = $estatus == 1 ? 'Activo' : 'Inactivo';
                                            $estatusColor = $estatus == 1 ? 'success' : 'danger';

                                            echo '<tr>
                                            <td style="text-align: -webkit-center;">
                                                '.$fotoC.'
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>'.$folioMarbete.'</strong></p>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>'.$numeroParte.'</strong></p>
                                                <small class="mb-0 text-muted">'.$descripcion.'</small>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>'.$primerConteo.'</strong></p>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>'.$segundoConteo.'</strong></p>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>'.$storageBin.'</strong></p>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted"><strong>$ '.$monto.'</strong></p>
                                                <small class="mb-0 text-muted">Pesos</small>
                                            </td>
                                            <td style="text-align: -webkit-center;">
                                                '.$fotoV.'
                                            </td>
                                        </tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- customized table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->
            </div> <!-- .row -->


        </div> <!-- .container-fluid -->

      </main> <!-- main -->
    </div> <!-- .wrapper -->

    <?php include 'estaticos/scriptEstandar.php'; ?>

    <script>
              $.ajax({
                url: 'https://grammermx.com/Logistica/Inventario/dao/consultaCapturista.php', // Reemplaza esto con la URL de tus datos
                dataType: 'json',
                success: function(data) {
                  $('#dataTable-1').DataTable({
                    data: data.data,
                    columns: [
                      { data: 'FolioMarbete' },
                      { data: 'Numero_Parte' },
                        { data: 'Fecha' },
                      { data: 'Usuario' },
                        { data: 'BOTON' }
                    ],
                    autoWidth: true,
                    "lengthMenu": [
                      [16, 32, 64, -1],
                      [16, 32, 64, "All"]
                    ]
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

      function detallesRegistro(folio,comentarios,usuario,fecha) {

          document.getElementById("txtFolioMarbete").innerText = folio;
          document.getElementById("txtComentario").value = comentarios;
          document.getElementById("txtResponsable").value = usuario;
          document.getElementById("lblFecha").innerText = fecha;

          var table = document.getElementById("data-table");

          while (table.rows.length > 1) {
              table.deleteRow(1);
          }

          $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaDetalles.php?marbete='+folio, function (data) {
              for (var i = 0; i < data.data.length; i++) {
                  var row = table.insertRow(-1);
                  var cell1 = row.insertCell(0);
                  var cell2 = row.insertCell(1);
                  var cell3 = row.insertCell(2);
                  cell1.innerHTML = data.data[i].Id_StorageUnit;
                  cell2.innerHTML = data.data[i].Numero_Parte;
                  cell3.innerHTML = data.data[i].Cantidad;
              }
              document.getElementById("btnModal").click();
          });
      }

    </script>
  </body>
</html>