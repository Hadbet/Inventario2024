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
        <div class="container-fluid" id="tituloP">
          <div class="row justify-content-center">
            <div class="col-12">

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Registro</strong>
                        <button type="button" onclick="limpiar();" class="btn mb-2 btn-info float-right text-white">Refresh<span
                                    class="fe fe-refresh-ccw fe-16 ml-2"></span></button>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="txtGrammerNo">Grammer No</label>
                                    <input type="text" class="form-control drgpicker" id="txtGrammerNo"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="txtDescripcion">Descripción</label>
                                    <input type="text" class="form-control drgpicker" id="txtDescripcion"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>


                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="txtUM">UM</label>
                                    <input type="text" class="form-control drgpicker" id="txtUM"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <!-- /.col -->
                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="txtProfitCtr">ProfitCtr</label>
                                    <input type="text" class="form-control drgpicker" id="txtProfitCtr"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="txtCosto">Costo</label>
                                    <input type="number" class="form-control drgpicker" id="txtCosto"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="txtPor">Por</label>
                                    <input type="text" class="form-control drgpicker" id="txtPor"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">

                        <button disabled type="button" id="btnActualizar" onclick="enviarDatos(2);" class="btn mb-2 mr-2 btn-info float-right text-white">Actualizar<span
                                    class="fe fe-upload-cloud fe-16 ml-2"></span></button>

                        <button type="button" id="btnInsertar" onclick="enviarDatos(1);" class="btn mb-2 mr-2 btn-success float-right text-white">Registrar<span
                                    class="fe fe-send fe-16 ml-2"></span></button>

                    </div>
                </div> <!-- / .card -->

              <h2 class="mb-2 page-title">Listas de Parte</h2>
              <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" id="dataTable-1">
                        <thead>
                          <tr>
                              <th>Grammer No</th>
                              <th>Descripcion</th>
                              <th>UM</th>
                              <th>ProFitCtr</th>
                              <th>Costo</th>
                              <th>Por</th>
                              <th>Modificar</th>
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
                url: 'https://grammermx.com/Logistica/Inventario/dao/consultaParteAdmin.php', // Reemplaza esto con la URL de tus datos
                dataType: 'json',
                success: function(data) {
                  $('#dataTable-1').DataTable({
                    data: data.data,
                    columns: [
                      { data: 'GrammerNo' },
                      { data: 'Descripcion' },
                      { data: 'UM' },
                        { data: 'ProfitCtr' },
                        { data: 'Costo' },
                        { data: 'Por' },
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
              url: 'https://grammermx.com/Logistica/Inventario/dao/consultaParteAdmin.php', // Reemplaza esto con la URL de tus datos
              dataType: 'json',
              success: function(data) {
                  var table = $('#dataTable-1').DataTable();
                  table.clear();
                  table.rows.add(data.data);
                  table.draw();
              }
          });
      }

      function enviarDatos(tipo) {
          var grammerNo = document.getElementById("txtGrammerNo").value;
          var descripcion = document.getElementById("txtDescripcion").value;
          var um = document.getElementById("txtUM").value;
          var profit = document.getElementById("txtProfitCtr").value;
          var costo = document.getElementById("txtCosto").value;
          var por = document.getElementById("txtPor").value;

          var formData = new FormData();
          formData.append('grammerNo', grammerNo);
          formData.append('descripcion', descripcion);
          formData.append('um', um);
          formData.append('profit', profit);
          formData.append('costo', costo);
          formData.append('por', por);
          formData.append('tipo', tipo);

          fetch('https://grammermx.com/Logistica/Inventario/dao/guardarParte.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {
                  console.log(data);
                  actualizarTabla();
                  document.getElementById("txtGrammerNo").value = "";
                  document.getElementById("txtDescripcion").value = "";
                  document.getElementById("txtUM").value = "";
                  document.getElementById("txtProfitCtr").value = "";
                  document.getElementById("txtCosto").value = "";
                  document.getElementById("txtPor").value = "";
                  document.getElementById('txtGrammerNo').disabled = false;
                  Swal.fire({
                      title: "Listo modifico la parte",
                      text: data.message,
                      icon: "success"
                  });
              });
      }

      function llenarDatos(grammerNo,descripcion,um,proFit,costo,por,por) {
          document.getElementById("txtGrammerNo").value = grammerNo;
          document.getElementById("txtDescripcion").value = descripcion;
          document.getElementById("txtUM").value = um;
          document.getElementById("txtProfitCtr").value = proFit;
          document.getElementById("txtCosto").value = costo;
          document.getElementById("txtPor").value = por;
          document.getElementById('txtGrammerNo').disabled = true;
          document.getElementById('btnActualizar').disabled = false;
          document.getElementById('btnInsertar').disabled = true;
          document.getElementById("tituloP").scrollIntoView({behavior: "smooth"});
      }

      function limpiar() {
          document.getElementById("txtGrammerNo").value = "";
          document.getElementById("txtDescripcion").value = "";
          document.getElementById("txtUM").value = "";
          document.getElementById("txtProfitCtr").value = "";
          document.getElementById("txtCosto").value = "";
          document.getElementById("txtPor").value = "";
          document.getElementById('txtGrammerNo').disabled = false;
          document.getElementById('btnActualizar').disabled = true;
          document.getElementById('btnInsertar').disabled = false;
      }

    </script>
  </body>
</html>