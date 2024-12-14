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

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title" id="tituloP">Registro</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="txtIdArea">Id</label>
                                    <input type="text" class="form-control drgpicker" id="txtIdArea"
                                           value="" aria-describedby="button-addon2" disabled>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-2">
                                    <label for="txtNombre">Nombre</label>
                                    <input type="text" class="form-control drgpicker" id="txtNombreArea"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-group mb-3">
                                    <label for="cbTipo">Tipo</label>
                                    <select class="custom-select" id="cbTipo">
                                        <option selected>Abrir menu</option>
                                        <option value="0">Almacen</option>
                                        <option value="1">Produccion</option>
                                        <option value="2">SUM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="txtStLocation">StLocation</label>
                                    <input type="text" class="form-control drgpicker" id="txtStLocation"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <!-- /.col -->
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="txtStBin">StBin</label>
                                    <input type="text" class="form-control drgpicker" id="txtStBin"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>



                            <div class="col-md-2" >
                                <div class="form-group mb-3">
                                    <label for="cbConteo">Conteo</label>
                                    <select class="custom-select" id="cbConteo">
                                        <option selected>Abrir menu</option>
                                        <option value="1">Primero</option>
                                        <option value="2">Segundo</option>
                                        <option value="3">Tercero</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="enviarDatos();" class="btn mb-2 btn-success float-right text-white">Registrar<span
                                    class="fe fe-send fe-16 ml-2"></span></button>
                    </div>
                </div> <!-- / .card -->

              <h2 class="mb-2 page-title">Listas de areas</h2>
              <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" id="dataTable-1">
                        <thead>
                          <tr>
                              <th>IdArea</th>
                              <th>Nombre</th>
                              <th>Tipo</th>
                              <th>StLocation</th>
                              <th>StBin</th>
                              <th>Conteo</th>
                              <th>Modificar</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>

                        <!-- Button trigger modal -->
                        <button style="display: none" type="button" class="btn mb-2 btn-outline-success" data-toggle="modal" data-target="#verticalModal" id="btnModal"> Launch demo modal </button>
                        <!-- Modal -->
                        <div class="modal fade" id="verticalModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="verticalModalTitle">Modificación de usuarios</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Id:</label>
                                            <input type="text" class="form-control" id="txtIdM" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Usuario:</label>
                                            <input type="text" class="form-control" id="txtUsuarioM">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Password:</label>
                                            <input type="password" class="form-control" id="txtPasswordM">
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn mb-2 btn-success text-white" onclick="actualizarDatos()">Actualizar</button>
                                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal" id="btnCloseM">Close</button>
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
    <script>
              $.ajax({
                url: 'https://grammermx.com/Logistica/Inventario/dao/consultaAreaAdmin.php', // Reemplaza esto con la URL de tus datos
                dataType: 'json',
                success: function(data) {
                  $('#dataTable-1').DataTable({
                    data: data.data,
                    columns: [
                      { data: 'IdArea' },
                      { data: 'AreaNombre' },
                      { data: 'AreaProduccion' },
                        { data: 'StLocation' },
                        { data: 'StBin' },
                        { data: 'Conteo' },
                        { data: 'Boton' }
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
              url: 'https://grammermx.com/Logistica/Inventario/dao/consultaAreaAdmin.php', // Reemplaza esto con la URL de tus datos
              dataType: 'json',
              success: function(data) {
                  var table = $('#dataTable-1').DataTable();
                  table.clear();
                  table.rows.add(data.data);
                  table.draw();
              }
          });
      }

      function enviarDatos() {
          var nombre = document.getElementById("txtNombreArea").value;
          var id = document.getElementById("txtIdArea").value;
          var tipo = document.getElementById("cbTipo").value;
          var location = document.getElementById("txtStLocation").value;
          var bin = document.getElementById("txtStBin").value;
          var conteo = document.getElementById("cbConteo").value;

          var formData = new FormData();
          formData.append('id', id);
          formData.append('nombre', nombre);
          formData.append('tipo', tipo);
          formData.append('location', location);
          formData.append('bin', bin);
          formData.append('conteo', conteo);

          fetch('https://grammermx.com/Logistica/Inventario/dao/guardarArea.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {
                  console.log(data);
                  actualizarTabla();
                  document.getElementById("txtNombreArea").value = "";
                  document.getElementById("txtIdArea").value = "";
                  document.getElementById("cbTipo").value = "";
                  document.getElementById("txtStLocation").value = "";
                  document.getElementById("txtStBin").value = "";
                  document.getElementById("cbConteo").value = "";
                  Swal.fire({
                      title: "Listo modifico el area",
                      text: data.message,
                      icon: "success"
                  });
              });
      }

      function llenarDatos(id,nombre,tipo,stLocation,stBin,conteo) {
          document.getElementById("txtNombreArea").value = nombre;
          document.getElementById("txtIdArea").value = id;
          document.getElementById("cbTipo").value = tipo;
          document.getElementById("txtStLocation").value = stLocation;
          document.getElementById("txtStBin").value = stBin;
          document.getElementById("cbConteo").value = conteo;
          document.getElementById("tituloP").scrollIntoView({behavior: "smooth"});
      }

    </script>
  </body>
</html>