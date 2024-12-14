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

$stmt = $conex->prepare("SELECT Usuarios.Id_Usuario, Usuarios.Nomina, Usuarios.Nombre, Usuarios.User, Usuarios.Password, Usuarios.Rol, Usuarios.Estatus, Usuarios.Area, Area.AreaNombre FROM Usuarios JOIN Area ON Usuarios.Area = Area.IdArea WHERE Area = $area");
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

                      <div class="row align-items-center my-4">
                          <div class="col">
                              <h2 class="h3 mb-0 page-title">Tu equipo</h2>
                          </div>
                      </div>

                      <div class="row">

                          <?php
                          while ($row = $result->fetch_assoc()) {
                              $nombre = $row['Nombre'];
                              $nomina = $row['Nomina'];
                              $usuario = $row['User'];
                              $estatus = $row['Estatus'];
                              $rol = $row['Rol'];

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

                              $rolNombre = '';
                              switch ($rol) {
                                  case 1:
                                      $rolNombre = 'Capturista';
                                      break;
                                  case 2:
                                      $rolNombre = 'Auditor';
                                      break;
                                  case 3:
                                      $rolNombre = 'Lider';
                                      break;
                                  case 4:
                                      $rolNombre = 'Super user';
                                      break;
                                  case 5:
                                      $rolNombre = 'Verificador';
                                      break;
                                  case 6:
                                      $rolNombre = 'Admin';
                                      break;
                              }

                              $estatusNombre = $estatus == 1 ? 'Activo' : 'Inactivo';
                              $estatusColor = $estatus == 1 ? 'success' : 'danger';

                              echo '<div class="col-md-3">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mt-4">
                    <a href="">
                        <img src="https://grammermx.com/Fotos/'.$nomina.'.png" alt="..." class="avatar-img rounded-circle">
                    </a>
                </div>
                <div class="card-text my-2">
                    <strong class="card-title my-0">' . $nombre . '</strong>
                    <p class="small text-muted mb-0">' . $usuario . '</p>
                    <p class="small"><span class="badge badge-light text-muted">' . $rolNombre . '</span></p>
                </div>
            </div>
            <div class="card-footer">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <small>
                            <span class="dot dot-lg bg-' . $estatusColor . ' mr-1"></span> ' . $estatusNombre . ' </small>
                    </div>
                </div>
            </div>
        </div>
    </div>';
                          }
                          ?>
                          <div class="col-md-9">
                          </div> <!-- .col -->
                      </div> <!-- .row -->
                  </div> <!-- .col-12 -->
              </div> <!-- .row -->
          </div> <!-- .container-fluid -->
      </main> <!-- main -->
    </div> <!-- .wrapper -->

    <?php include 'estaticos/scriptEstandar.php'; ?>

    <script>
              $.ajax({
                url: 'https://grammermx.com/Logistica/Inventario2024/dao/consultaMarbeteLider.php?area=<?php echo $area; ?>', // Reemplaza esto con la URL de tus datos
                dataType: 'json',
                success: function(data) {
                  $('#dataTable-1').DataTable({
                    data: data.data,
                    columns: [
                      { data: 'FolioMarbete' },
                      { data: 'NumeroParte' },
                      { data: 'Usuario' },
                        { data: 'Estatus' },
                        { data: 'PrimerConteo' },
                        { data: 'StorageBin' },
                        { data: 'Cancelar' }
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

      function actualizarTabla() {
          $.ajax({
              url: 'https://grammermx.com/Logistica/Inventario2024/dao/consultaMarbeteLider.php?area=<?php echo $area; ?>', // Reemplaza esto con la URL de tus datos
              dataType: 'json',
              success: function(data) {
                  var table = $('#dataTable-1').DataTable();
                  table.clear();
                  table.rows.add(data.data);
                  table.draw();
              }
          });
      }

      async function cancelar(id) {
          const { value: comentario } = await Swal.fire({
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

              fetch('https://grammermx.com/Logistica/Inventario2024/dao/guardarCancelacion.php', {
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

    </script>
  </body>
</html>