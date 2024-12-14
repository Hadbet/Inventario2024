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
              <h2 class="mb-2 page-title">Lista de marbetes capturados</h2>
              <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" id="dataTable-1">
                        <thead>
                          <tr>
                              <th>Marbete</th>
                            <th>Número de parte</th>
                          <th>Fecha de captura</th>
                            <th>Usuario</th>
                            <th>Ver detalles</th>
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
                                        <h5 class="modal-title" id="verticalModalTitle">Detalles</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label id="lblFecha" class="col-form-label"></label>
                                        <h4>Marbete : <span id="txtFolioMarbete">185</span></h4>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Responsable:</label>
                                            <input type="text" class="form-control" id="txtResponsable" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Comentarios:</label>
                                            <input type="text" class="form-control" id="txtComentario" readonly>
                                        </div>
                                        <hr>
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
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