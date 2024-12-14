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
                        <strong class="card-title">Registro</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <h2 class="text-center">Actualizar Counted Qty en excel</h2>
                            <button class="btn btn-success text-right btnExcel" id="btnExcelExcelQty"> Subir Excel</button>
                            <input type="file" id="fileInputExcelQty" accept=".xlsx, .xls" style="display: none;" />

                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="cbCurso">Nomina</label>
                                    <input type="text" class="form-control drgpicker" id="txtNomina"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-2">
                                    <label for="cbCurso">Nombre</label>
                                    <input type="text" class="form-control drgpicker" id="txtNombre"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="cbCurso">Usuario</label>
                                    <input type="text" class="form-control drgpicker" id="txtUsuario"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <!-- /.col -->
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="cbCurso">Contraseña</label>
                                    <input type="password" class="form-control drgpicker" id="txtContra"
                                           value="" aria-describedby="button-addon2" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-group mb-3">
                                    <label for="cbInstructor">Rol</label>
                                    <select class="custom-select" id="cbRol">
                                        <option selected>Abrir menu</option>
                                        <option value="1">Capturista</option>
                                        <option value="2">Auditor</option>
                                        <option value="3">Lider Conteo</option>
                                        <option value="4">Super User</option>
                                        <option value="5">Validador</option>
                                        <option value="6">Administrador</option>
                                        <option value="7">Coordinador Almacen</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1" >
                                <div class="form-group mb-3">
                                    <label for="cbInstructor">Estatus</label>
                                    <select class="custom-select" id="cbEstatus">
                                        <option selected>Abrir menu</option>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2" >
                                <div class="form-group mb-3">
                                    <label for="cbArea">Area</label>
                                    <select class="custom-select" id="cbArea">
                                        <option selected>Abrir Area</option>
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

                <button type="button" onclick="masivo(1);" class="btn mb-2 btn-success float-right text-white ml-4">Habilitar todos<span
                            class="fe fe-send fe-16 ml-2"></span></button>
                <button type="button" onclick="masivo(0);" class="btn mb-2 btn-danger float-right text-white">Deshabilitar todos<span
                            class="fe fe-x-circle fe-16 ml-2"></span></button>

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
                              <th>ID</th>
                              <th>Usuario</th>
                              <th>Rol</th>
                              <th>Area</th>
                              <th>Estatus</th>
                              <th>Eliminar</th>
                              <th>Actualizar</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

    <script>


        document.getElementById('btnExcelExcelQty').addEventListener('click', () => {
            document.getElementById('fileInputExcelQty').click();
        });

        document.getElementById('fileInputExcelQty').addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                insertarExcelParte(file);
            }
        });
        async function insertarExcelParte(file) {
            try {
                // Leer el archivo Excel
                const data = await file.arrayBuffer();
                const workbook = XLSX.read(data, { type: 'array' });
                const worksheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                // Mapear los datos, asegurándonos de convertir las fechas correctamente
                const parteData = jsonData.slice(1).map((row) => {
                    return {
                        Nomina: row[0],
                        Nombre: row[1],
                        Usuario: row[2],
                        Password: row[3],
                        Rol: row[4],
                        Area: row[5]
                    };
                });

                // Enviar los datos al backend
                const response = await fetch('daoAdmin/daoInsertarParte.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ parteDatos: parteData })
                });

                // Obtener la respuesta del backend
                const result = await response.json();

                if (result.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualización exitosa',
                        text: result.message
                    });
                } else {
                    // Mostrar el mensaje de error que viene del backend
                    throw new Error(result.message );
                }

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
                });
            }
        }



              $.ajax({
                url: 'https://grammermx.com/Logistica/Inventario2024/dao/consultaUser.php', // Reemplaza esto con la URL de tus datos
                dataType: 'json',
                success: function(data) {
                  $('#dataTable-1').DataTable({
                    data: data.data,
                    columns: [
                      { data: 'Id_Usuario' },
                      { data: 'User' },
                      { data: 'Rol' },
                        { data: 'AreaNombre' },
                        { data: 'Estatus' },
                        { data: 'Boton1' },
                        { data: 'Boton2' }
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

      var input = document.getElementById('txtNomina');
      var nominaReal;

      // Agrega el evento 'keypress'
      input.addEventListener('keypress', function(e){
          // Verifica si la tecla presionada fue Enter
          if(e.key === 'Enter' || e.keyCode === 13){
              nominaReal = generarNomina(document.getElementById("txtNomina").value);
              $.getJSON('https://grammermx.com/RH/Cursos/dao/consultaEmpleadoGeneral.php?nomina=' + nominaReal, function (data) {
                  for (var i = 0; i < data.data.length; i++) {
                      var nombreCompleto = data.data[i].NomUser;
                      nombreCompleto = nombreCompleto.trim(); // Elimina los espacios en blanco al principio y al final
                      var nombres = nombreCompleto.split(" "); // Divide la cadena en un array
                      var primerosDosNombres = nombres[0] + " " + nombres[1]; // Toma los dos primeros elementos

                      document.getElementById("txtNombre").value = primerosDosNombres;
                  }
              });
          }
      });

      function detallesRegistro(folio,comentarios,usuario,fecha) {

          document.getElementById("txtFolioMarbete").innerText = folio;
          document.getElementById("txtComentario").value = comentarios;
          document.getElementById("txtResponsable").value = usuario;
          document.getElementById("lblFecha").innerText = fecha;

          var table = document.getElementById("data-table");

          while (table.rows.length > 1) {
              table.deleteRow(1);
          }

          $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaDetalles.php?marbete='+folio, function (data) {
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

      function enviarDatos() {
          var user = document.getElementById("txtUsuario").value;
          var nombre = document.getElementById("txtNombre").value;
          var nomina = document.getElementById("txtNomina").value;
          var password = document.getElementById("txtContra").value;
          var rol = document.getElementById("cbRol").value;
          var estatus = document.getElementById("cbEstatus").value;
          var area = document.getElementById("cbArea").value;

          var formData = new FormData();
          formData.append('user', user);
          formData.append('password', password);
          formData.append('rol', rol);
          formData.append('estatus', estatus);
          formData.append('area', area);
          formData.append('nombre', nombre);
          formData.append('nomina', nomina);

          fetch('https://grammermx.com/Logistica/Inventario2024/dao/guardarUsuario.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {
                  console.log(data);
                  actualizarTabla();
                  document.getElementById("txtNombre").value="";
                  document.getElementById("txtUsuario").value="";
                  document.getElementById("txtNomina").value="";
                  document.getElementById("txtContra").value="";
                  document.getElementById("cbRol").value="";
                  document.getElementById("cbEstatus").value="";
                  Swal.fire({
                      title: data.message,
                      text: "Se agrego de manera correcta",
                      icon: "success"
                  });
              });
      }

      function actualizarTabla() {
          $.ajax({
              url: 'https://grammermx.com/Logistica/Inventario2024/dao/consultaUser.php', // Reemplaza esto con la URL de tus datos
              dataType: 'json',
              success: function(data) {
                  var table = $('#dataTable-1').DataTable();
                  table.clear();
                  table.rows.add(data.data);
                  table.draw();
              }
          });
      }

      function estatus(id,estado) {

          var formData = new FormData();
          formData.append('id', id);
          formData.append('estado', estado);

          fetch('https://grammermx.com/Logistica/Inventario2024/dao/guardarEstatus.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {
                  console.log(data);
                  actualizarTabla();
              });
      }

      llenarAreas();
      function llenarAreas() {
          $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaArea.php', function (data) {
              for (var i = 0; i < data.data.length; i++) {
                  var option = $('<option/>');
                  option.attr({ 'value': data.data[i].IdArea }).text(data.data[i].AreaNombre);
                  $('#cbArea').append(option);
              }
          });
      }

      function configuracion(user,id,rol,estatus) {
          document.getElementById("btnModal").click();
          document.getElementById("txtUsuarioM").value = user;
          document.getElementById("txtIdM").value = id;

      }

      function actualizarDatos() {
          var user = document.getElementById("txtUsuarioM").value;
          var password = document.getElementById("txtPasswordM").value;
          var id = document.getElementById("txtIdM").value;

          var formData = new FormData();
          formData.append('user', user);
          formData.append('password', password);
          formData.append('id', id);

          fetch('https://grammermx.com/Logistica/Inventario2024/dao/actualizarUsuario.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {

                  if (data.success) {
                      document.getElementById("txtUsuarioM").value="";
                      document.getElementById("txtPasswordM").value="";
                      document.getElementById("txtIdM").value="";
                      Swal.fire({
                          title: data.message,
                          text: "Actualización correcta",
                          icon: "success"
                      });
                      document.getElementById("btnCloseM").click();
                      actualizarTabla();
                  } else {
                      Swal.fire({
                          title: data.message,
                          text: "Verificalo con la mesa central",
                          icon: "error"
                      });
                  }
              });

      }

      function generarNomina(nomina) {

          if (nomina.length === 1){return nomina = "0000000"+nomina;}
          if (nomina.length === 2){return nomina = "000000"+nomina;}
          if (nomina.length === 3){return nomina = "00000"+nomina;}
          if (nomina.length === 4){return nomina = "0000"+nomina;}
          if (nomina.length === 5){return nomina = "000"+nomina;}
          if (nomina.length === 6){return nomina = "00"+nomina;}
          if (nomina.length === 7){return nomina = "0"+nomina;}
          if (nomina.length === 8){return nomina = nomina;}


      }

      document.getElementById('txtNomina').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('txtNombre').focus()
          }
      });

      document.getElementById('txtNombre').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('txtUsuario').focus()
          }
      });

      document.getElementById('txtUsuario').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('txtContra').focus()
          }
      });

      document.getElementById('txtContra').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('cbRol').focus()
          }
      });

      document.getElementById('cbRol').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('cbEstatus').focus()
          }
      });

      document.getElementById('cbEstatus').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              document.getElementById('cbArea').focus()
          }
      });

      document.getElementById('cbArea').addEventListener('keyup', function(event) {
          if (event.key === 'Enter' || event.keyCode === 13) {
              enviarDatos();
          }
      });

      function masivo(estado) {

          var formData = new FormData();
          formData.append('estado', estado);

          fetch('https://grammermx.com/Logistica/Inventario2024/dao/masivoUsuarios.php', {
              method: 'POST',
              body: formData
          })
              .then(response => response.json())
              .then(data => {

                  if (data.success) {
                      Swal.fire({
                          title: data.message,
                          text: "Actualización correcta",
                          icon: "success"
                      });
                      actualizarTabla();
                  } else {
                      Swal.fire({
                          title: data.message,
                          text: "Verificalo con la mesa central",
                          icon: "error"
                      });
                  }
              });

      }
    </script>
  </body>
</html>