<?php

include_once('db/db_Inventario.php');


//$marbete = $_GET['marbete'];

ContadorApu();

function ContadorApu()
{
    $con = new LocalConector();
    $conex = $con->conectar();

    $datos = mysqli_query($conex, "SELECT U.`Id_Usuario`, U.`User`, U.`Password`,
    CASE 
        WHEN U.`Rol` = 1 THEN 'Capturista'
        WHEN U.`Rol` = 2 THEN 'Auditor'
        WHEN U.`Rol` = 3 THEN 'Lider de conteo'
        WHEN U.`Rol` = 4 THEN 'Super'
        WHEN U.`Rol` = 5 THEN 'Verificador'
        WHEN U.`Rol` = 6 THEN 'Administrador'
        ELSE 'Rol desconocido'
    END AS `Rol`,
    IF(U.`Estatus` = 1, 
        '<span class=\"badge badge-pill badge-success\">Activo</span>', 
        '<span class=\"badge badge-pill badge-info\">Inactivo</span>'
    ) AS `Estatus`,
    IF(U.`Estatus` = 1, 
        CONCAT('<button class=\"btn btn-danger text-white\" onclick=\"estatus(', U.`Id_Usuario`, ',0)\">Desactivar</button>'), 
        CONCAT('<button class=\"btn btn-success text-white\" onclick=\"estatus(', U.`Id_Usuario`, ',1)\">Activar</button>')
    ) AS `Boton1`,
    CONCAT('<button class=\"btn btn-warning text-white\" onclick=\"configuracion(\'', U.`User`, '\', \'', U.`Id_Usuario`, '\', ', U.`Rol`, ', ', U.`Estatus`, ')\">Actualizar</button>') AS `Boton2`,
    A.`AreaNombre`
FROM `Usuarios` U
LEFT JOIN `Area` A ON U.`Area` = A.`IdArea`
WHERE 1;");

    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}


?>