<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>GRAMMER INVENTARIO</title>
    <link rel="stylesheet" href="css/select2.css">
    <link rel="stylesheet" href="css/dropzone.css">
    <link rel="stylesheet" href="css/uppy.min.css">
    <link rel="stylesheet" href="css/jquery.steps.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/quill.snow.css">
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

                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h2 class="h5 page-title">Bienvenido al sistema de inventario</h2>
                        </div>
                        <div class="col-auto">
                            <form class="form-inline">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm"><span
                                            class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mb-2 align-items-center">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row mt-1 align-items-center">
                                    <div class="col-12 col-lg-4 text-left pl-4">
                                        <span class="h3">Proceso del inventario</span>
                                    </div>
                                </div>
                                <div class="map-box">
                                    <div id="areaChartTres"></div>
                                </div>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div>

                    <div class="mb-2 align-items-center">
                        <div class="card shadow mb-4">
                            <div class="card-body">

                                <div class="col-md-2" >
                                    <div class="form-group mb-3">
                                        <label for="cbArea">Área</label>
                                        <select class="custom-select" id="cbArea" onchange="graficaCostoCarga()">
                                            <option selected value="all">Todas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-1 align-items-center">
                                    <div class="col-12 col-lg-4 text-left pl-4">
                                        <span class="h3">Proceso del Inventario en dinero</span>
                                    </div>
                                </div>
                                <div class="map-box">
                                    <div id="areaChartCuatro"></div>
                                </div>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div>

                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->


    </main> <!-- main -->
</div> <!-- .wrapper -->
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/simplebar.min.js"></script>
<script src='js/daterangepicker.js'></script>
<script src='js/jquery.stickOnScroll.js'></script>
<script src="js/tinycolor-min.js"></script>
<script src="js/config.js"></script>
<script src="js/d3.min.js"></script>
<script src="js/topojson.min.js"></script>
<script src="js/datamaps.all.min.js"></script>
<script src="js/datamaps-zoomto.js"></script>
<script src="js/datamaps.custom.js"></script>
<script src="js/Chart.min.js"></script>
<script>
    /* defind global options */
    Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
    Chart.defaults.global.defaultFontColor = colors.mutedColor;
</script>
<script src="js/gauge.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/apexcharts.min.js"></script>
<script src="js/apexcharts.custom.js"></script>
<script src='js/jquery.mask.min.js'></script>
<script src='js/select2.min.js'></script>
<script src='js/jquery.steps.min.js'></script>
<script src='js/jquery.validate.min.js'></script>
<script src='js/jquery.timepicker.js'></script>
<script src='js/dropzone.min.js'></script>
<script src='js/uppy.min.js'></script>
<script src='js/quill.min.js'></script>
<script src="js/apps.js"></script>

<script>

    document.getElementById("cbArea").addEventListener("change", function(event){
        event.preventDefault();
        graficaCostoCarga();
    });

    llenarAreas();
    function llenarAreas() {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/consultaArea.php', function (data) {
            for (var i = 0; i < data.data.length; i++) {
                var option = $('<option/>');
                option.attr({ 'value': data.data[i].IdArea }).text(data.data[i].AreaNombre);
                $('#cbArea').append(option);
            }
        });
    }

    var chartDos;
    graficaCostoCarga();
    function graficaCostoCarga() {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/graficaCosto.php?area='+document.getElementById("cbArea").value, function (data) {
            var AreaNombreCosto = [];
            var PrimerConteoCosto = [];
            var SegundoConteoCosto = [];

            for (var i = 0; i < data.data.length; i++) {
                var totalContado = data.data[i].TotalContado ? parseFloat(data.data[i].TotalContado).toFixed(2) : '0.00';
                var totalEsperado = data.data[i].TotalEsperado ? parseFloat(data.data[i].TotalEsperado).toFixed(2) : '0.00';
                AreaNombreCosto.push((data.data[i].AreaNombre ? data.data[i].AreaNombre : '') + "(" + totalContado + "/" + totalEsperado + ")");
                PrimerConteoCosto.push(totalContado);
                SegundoConteoCosto.push(totalEsperado);
            }
            graficaCosto(AreaNombreCosto,PrimerConteoCosto,SegundoConteoCosto);
        });
    }

    function graficaCosto(AreaNombreCosto,PrimerConteoCosto,SegundoConteoCosto) {
        var options = {
            series: [{
                name: 'Monto actual',
                type: 'column',
                data: PrimerConteoCosto
            }, {
                name: 'Monto Estimado',
                type: 'column',
                data: SegundoConteoCosto
            }],
            chart: {
                height: 700,
                stacked: false,
            },
            stroke: {
                width: [0, 2, 5],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            fill: {
                opacity: [0.85, 0.85, 1],
                gradient: {
                    inverseColors: false,
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100]
                }
            },
            labels: AreaNombreCosto,
            markers: {
                size: 0
            },
            xaxis: {
                type: 'category'
            },
            yaxis: {
                title: {
                    text: 'Proceso',
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return  "$ "+y.toFixed(2);
                        }
                        return y;
                    }
                }
            }
        };

        if(chartDos) {
            chartDos.destroy(); // Destruye el gráfico anterior si existe
        }

        chartDos = new ApexCharts(document.querySelector("#areaChartCuatro"), options);
        chartDos.render();
    }




    var chart;

    Apu();
    setInterval(Apu, 60000); // Actualiza cada minuto
    function Apu() {
        $.getJSON('https://grammermx.com/Logistica/Inventario/dao/graficaGeneral.php', function (data) {
            var AreaNombre = [];
            var PrimerConteo = [];
            var SegundoConteo = [];
            var Estandar = [];

            for (var i = 0; i < data.data.length; i++) {
                AreaNombre.push((data.data[i].AreaNombre ? data.data[i].AreaNombre : '')+"("+data.data[i].PrimerConteoLiberado+"/"+data.data[i].TotalPrimerConteo+")");
                PrimerConteo.push(data.data[i].PorcentajePrimerConteo ? parseFloat(data.data[i].PorcentajePrimerConteo) : 0);
                SegundoConteo.push(data.data[i].PorcentajeSegundoConteo ? parseFloat(data.data[i].PorcentajeSegundoConteo) : 0);
                Estandar.push(100.00);
            }

            console.log(AreaNombre);
            console.log(PrimerConteo);
            console.log(SegundoConteo);
            graficaAusentismosApu(AreaNombre,PrimerConteo,SegundoConteo,Estandar);
        });
    }

    function graficaAusentismosApu(AreaNombre,PrimerConteo,SegundoConteo,Estandar) {
        var options = {
            series: [{
                name: 'Primer Conteo',
                type: 'column',
                data: PrimerConteo
            }, {
                name: 'Segundo Conteo',
                type: 'column',
                data: SegundoConteo
            }, {
                name: 'Estandar',
                type: 'line',
                data: Estandar
            }],
            chart: {
                height: 750,
                type: 'line',
                stacked: false,
            },
            stroke: {
                width: [0, 2, 5],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            fill: {
                opacity: [0.85, 0.25, 1],
                gradient: {
                    inverseColors: false,
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100]
                }
            },
            labels: AreaNombre,
            markers: {
                size: 0
            },
            xaxis: {
                type: 'category'
            },
            yaxis: {
                title: {
                    text: 'Proceso',
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0) + " %";
                        }
                        return y;
                    }
                }
            }
        };

        if(chart) {
            chart.destroy(); // Destruye el gráfico anterior si existe
        }

        chart = new ApexCharts(document.querySelector("#areaChartTres"), options);
        chart.render();
    }


</script>

</body>
</html>