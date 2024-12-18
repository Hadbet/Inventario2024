<?php
include_once('connection.php');

header('Content-Type: application/json');

// Leer datos del frontend
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data)) {
    echo json_encode(['error' => 'No se recibieron datos']);
    exit();
}

// Conectar a la base de datos
$con = new LocalConector();
$conexion = $con->conectar();

if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}

$updatedData = [];

foreach ($data as $record) {
    $storageUnit = mysqli_real_escape_string($conexion, $record['StorUnitType']);
    $storageBin = mysqli_real_escape_string($conexion, $record['StorageBin']);
    $noParte = mysqli_real_escape_string($conexion, $record['Material']);

    // Inicializar datos predeterminados
    $cantidad = '0';
    $unit = '';

    // Consulta de cantidad
    $consQ = ($storageUnit != null && $storageUnit != "")
        ? "SELECT Cantidad FROM Storage_Unit WHERE Id_StorageUnit = '$storageUnit'"
        : "SELECT 
                CASE
                    WHEN (SegundoConteo IS NULL OR SegundoConteo = 0) 
                         AND (TercerConteo IS NULL OR TercerConteo = 0) 
                    THEN PrimerConteo
                    WHEN (TercerConteo IS NULL OR TercerConteo = 0) 
                         AND (SegundoConteo IS NOT NULL AND SegundoConteo != 0) 
                    THEN SegundoConteo
                    WHEN (TercerConteo IS NOT NULL AND TercerConteo != 0)
                    THEN TercerConteo
                END AS Cantidad
           FROM Bitacora_Inventario
           WHERE StorageBin = '$storageBin' 
             AND NumeroParte = '$noParte'";

    $rsconsQty = mysqli_query($conexion, $consQ);

    // Consulta de unidad de medida
    $consUnit = "SELECT UM FROM Parte WHERE GrammerNo = '$noParte'";
    $rsconsUnit = mysqli_query($conexion, $consUnit);

    if ($rsconsQty) {
        if ($row = mysqli_fetch_assoc($rsconsQty)) {
            $cantidad = isset($row['Cantidad']) ? $row['Cantidad'] : '0';
        }
    } else {
        // Registrar error si falla la consulta de cantidad
        $updatedData[] = [
            'storageUnit' => $storageUnit,
            'storageBin' => $storageBin,
            'noParte' => $noParte,
            'error' => 'Error en consulta de cantidad: ' . mysqli_error($conexion)
        ];
        continue;
    }

    if ($rsconsUnit) {
        if ($rowU = mysqli_fetch_assoc($rsconsUnit)) {
            $unit = isset($rowU['UM']) ? $rowU['UM'] : '';
        }
    } else {
        // Registrar error si falla la consulta de unidad
        $updatedData[] = [
            'storageUnit' => $storageUnit,
            'storageBin' => $storageBin,
            'noParte' => $noParte,
            'error' => 'Error en consulta de unidad: ' . mysqli_error($conexion)
        ];
        continue;
    }

    // Agregar datos al resultado
    $updatedData[] = [
        'storageUnit' => $storageUnit,
        'storageBin' => $storageBin,
        'noParte' => $noParte,
        'cantidad' => $cantidad,
        'unit' => $unit
    ];
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>