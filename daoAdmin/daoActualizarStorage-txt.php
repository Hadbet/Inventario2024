<?php
include_once('connection.php');

header('Content-Type: application/json');

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
    $storageUnit = mysqli_real_escape_string($conexion, $record['storUnit']);

    $consP = "SELECT Cantidad
                FROM Storage_Unit
                WHERE Id_StorageUnit = '$storageUnit'";
    $rsconsPro = mysqli_query($conexion, $consP);

    if ($rsconsPro) {
        if ($row = mysqli_fetch_assoc($rsconsPro)) {
            $updatedData[] = [
                'storageUnit' => $storageUnit,
                'cantidad' => $row['Cantidad']
            ];
        } else {
            // Si no hay resultados, asignar valores predeterminados
            $updatedData[] = [
                'storageUnit' => $storageUnit,
                'cantidad' => '0'
            ];
        }
    } else {
        // Si ocurre un error en la consulta, registrar el error
        $updatedData[] = [
            'storageUnit' => $storageUnit,
            'error' => mysqli_error($conexion)
        ];
    }
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>