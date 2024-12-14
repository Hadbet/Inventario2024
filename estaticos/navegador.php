
<?php
session_start();

if ($_SESSION["nominaCurso"] == "" && $_SESSION["nominaCurso"]== null && $_SESSION["rol"]== "" && $_SESSION["rol"]== null) {
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.html'>";
    session_destroy();
}else{
    session_start();
    $rol =$_SESSION['rol'];
    $area =$_SESSION['area'];
    $areaNombre =$_SESSION['AreaNombre'];
    $tipoArea =$_SESSION['tipoArea'];
    $bin =$_SESSION['StBin'];
}
$auditor='<a class="nav-link pl-3" href="verificacion.php"><span class="ml-1">Validacion</span></a>';

if ($tipoArea==2){
    $captura = '<ul class="collapse list-unstyled pl-4 w-100 collapse show" id="contact">
                    <a class="nav-link pl-3" href="form_registro.php"><span class="ml-1">Captura SUM</span></a>
                </ul>';

    $auditor = '<a class="nav-link pl-3" href="verificacion_almacen.php"><span class="ml-1">Validacion</span></a>';
}

if ($tipoArea==1){
    $captura = '<ul class="collapse list-unstyled pl-4 w-100 collapse show" id="contact">
                    <a class="nav-link pl-3" href="form_registro_produccion.php"><span class="ml-1">Captura Produccion</span></a>
                </ul>';
    $auditor='<a class="nav-link pl-3" href="form_validacion_produccion.php"><span class="ml-1">Validacion</span></a>';
}

if ($tipoArea==0){
    $captura = '<ul class="collapse list-unstyled pl-4 w-100 collapse show" id="contact">
                    <a class="nav-link pl-3" href="form_registro_almacen.php"><span class="ml-1">Captura Almacen</span></a>
                </ul>';
    $auditor='<a class="nav-link pl-3" href="form_validacion_produccion.php"><span class="ml-1">Validacion</span></a>';
}

?>

<nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>
</nav>
<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">
        <!-- nav bar -->
        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="inicio.php">
                <img src="assets/images/Grammer_Logo.ico" style="width: 30%">
            </a>
        </div>

        <?php
        if ($rol==4){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Administración</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#forms" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                <i class="fe fe-feather fe-16"></i>
                    <span class="ml-3 item-text">Inicio</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="forms">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="inicio.php"><span class="ml-1 item-text">DashBoard</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="crear_user.php"><span class="ml-1 item-text">Usuarios</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="form_registro_coordinador.php"><span class="ml-1 item-text">Terceros conteos</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="equipo_conteo.php"><span class="ml-1 item-text">Tu equipo de trabajo</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#tables" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Historicos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="tables">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="listas_base.php"><span class="ml-1 item-text">Marbetes</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#bases" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                    <i class="fe fe-codepen fe-16"></i>
                    <span class="ml-3 item-text">Bases de datos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="bases">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="control_areas_admin.php"><span class="ml-1 item-text">Areas</span></a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="control_ubicaciones_admin.php"><span class="ml-1 item-text">Ubicaciones y bines</span></a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="control_parte_admin.php"><span class="ml-1 item-text">Parte</span></a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="inventario_sap.php"><span class="ml-1 item-text">Inventario Sap</span></a>
                    </li>
                    
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#reportes" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                    <i class="fe fe-book fe-16"></i>
                    <span class="ml-3 item-text">Reportes</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="reportes">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="carga_descarga.php"><span class="ml-1 item-text">Reportes en txt</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="reporte_diferencias.php"><span class="ml-1 item-text">Reportes de diferencias</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="reporte_final.php"><span class="ml-1 item-text">Reportes Final</span></a>
                    </li>
                </ul>
            </li>
        </ul>';
        }
        ?>

        <?php
        if ($rol==7){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Coordinador de almacén </span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#forms" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                <i class="fe fe-feather fe-16"></i>
                    <span class="ml-3 item-text">Inicio</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="forms">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="inicio.php"><span class="ml-1 item-text">DashBoard</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="form_registro_coordinador.php"><span class="ml-1 item-text">Terceros conteos</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="equipo_conteo.php"><span class="ml-1 item-text">Tu equipo de trabajo</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#tables" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Historicos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="tables">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="listas_base.php"><span class="ml-1 item-text">Marbetes</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#reportes" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle nav-link">
                    <i class="fe fe-book fe-16"></i>
                    <span class="ml-3 item-text">Reportes</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="reportes">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="carga_descarga.php"><span class="ml-1 item-text">Reportes en txt</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="reporte_diferencias.php"><span class="ml-1 item-text">Reportes de diferencias</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="reporte_final.php"><span class="ml-1 item-text">Reportes Final</span></a>
                    </li>
                </ul>
            </li>
        </ul>';
        }
        ?>

        <?php
        if($rol==1){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Capturistas</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#contact" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Inicio</span>
                </a>
                
                '.$captura.'
            </li>
            <li class="nav-item dropdown">
                <a href="#support" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Historicos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="support">
                    <a class="nav-link pl-3" href="listas_marbetes_produccion.php"><span class="ml-1">Reporte</span></a>
                </ul>
            </li>
        </ul>';
        }
        ?>

        <?php
        if($rol==5){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Validador</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#contact" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Inicio</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="contact">
                    <a class="nav-link pl-3" href="form_validacion_produccion.php"><span class="ml-1">Verificar</span></a>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#support" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Historicos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="support">
                    <a class="nav-link pl-3" href="listas_marbetes_produccion.php"><span class="ml-1">Reporte</span></a>
                </ul>
            </li>
        </ul>';
        }
        ?>


        <?php
        if($rol==2){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Auditores</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#Auditor" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
    <i class="fe fe-award fe-16"></i>
                    <span class="ml-3 item-text">Verificacion</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="Auditor">
                    '.$auditor.'
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#Historico" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Historicos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="Historico">
                    <a class="nav-link pl-3" href="listas_marbetes_produccion.php"><span class="ml-1">Reporte</span></a>
                </ul>
            </li>
            <a href="#LiderTestD" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-trello fe-16"></i>
                    <span class="ml-3 item-text">Control captura</span>
                </a>
                '.$captura.'
        </ul>';
        }
        ?>


        <?php
        if($rol==3){
            echo '<p class="text-muted nav-heading mt-4 mb-1">
            <span>Lider de conteo</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#Lider" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-trello fe-16"></i>
                    <span class="ml-3 item-text">Control de conteos</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 collapse show" id="Lider">
                    <a class="nav-link pl-3" href="control_conteo.php"><span class="ml-1">Segundos Conteos</span></a>
                    <a class="nav-link pl-3" href="cancelacion_marbete.php"><span class="ml-1">Cancelacion</span></a>
                    <a class="nav-link pl-3" href="listas_base_produccion.php"><span class="ml-1">Lista de marbetes</span></a>
                    <a class="nav-link pl-3" href="listas_marbetes_produccion.php"><span class="ml-1">Lista de captura</span></a>
                    <a class="nav-link pl-3" href="equipo_conteo.php"><span class="ml-1">Tu equipo de trabajo</span></a>
                </ul>
                
                <a href="#LiderTest" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-trello fe-16"></i>
                    <span class="ml-3 item-text">Control captura</span>
                </a>
                '.$captura.'
                '.$auditor.'
            </li>
        </ul>';
        }
        ?>


        

        <div class="btn-box w-100 mt-4 mb-1">
            <a href="dao/logout.php" target="_blank" class="btn mb-2 btn-danger btn-lg btn-block">
                <i class="fe fe-log-out fe-12 mx-2 text-white"></i><span class="small text-white">Salir</span>
            </a>
        </div>
    </nav>
</aside>

