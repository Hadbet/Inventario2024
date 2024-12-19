<?php
include_once('connection.php');

// Configurar el encabezado para una respuesta JSON
header('Content-Type: application/json');

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data)) {
    echo json_encode(['error' => 'No se recibieron datos']);
    exit();
}

$con = new LocalConector();
$conexion = $con->conectar();

if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}

$updatedData = [];

foreach ($data as $record) {
    $stor_bin = mysqli_real_escape_string($conexion, $record['storBin']);
    $materialParte = mysqli_real_escape_string($conexion, $record['materialNo']);

    $consP = "SELECT 
                    CASE
                        WHEN (SegundoConteo IS NULL OR SegundoConteo = 0) 
                             AND (TercerConteo IS NULL OR TercerConteo = 0) 
                        THEN PrimerConteo
                        WHEN (TercerConteo IS NULL OR TercerConteo = 0) 
                             AND (SegundoConteo IS NOT NULL AND SegundoConteo != 0) 
                        THEN SegundoConteo
                        WHEN (TercerConteo IS NOT NULL AND TercerConteo != 0)
                        THEN TercerConteo
                    END AS ConteoFinal
                FROM Bitacora_Inventario
                WHERE StorageBin = '$stor_bin' 
                  AND NumeroParte = '$materialParte'
                  AND Estatus = 1";
    $rsconsPro = mysqli_query($conexion, $consP);

    if ($rsconsPro) {
        if ($row = mysqli_fetch_assoc($rsconsPro)) {
            $updatedData[] = [
                'storBin' => $stor_bin,
                'materialNo' => $materialParte,
                'conteoFinal' => $row['ConteoFinal']
            ];
        } else {
            // Si no hay resultados, asignar valores predeterminados
            $updatedData[] = [
                'storBin' => $stor_bin,
                'materialNo' => $materialParte,
                'conteoFinal' => '0'
            ];
        }
    } else {
        // Si ocurre un error en la consulta, registrar el error
        $updatedData[] = [
            'storBin' => $stor_bin,
            'materialNo' => $materialParte,
            'error' => mysqli_error($conexion)
        ];
    }
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>