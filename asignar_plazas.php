<?php
include "assets/php/conexion.php";
$conexion = conexion();
$ano = date("Y");

// Obtener los usuarios activos
$sql = "SELECT u.RFC, e.id_puesto
        FROM Usuario u
        INNER JOIN Empleado e ON u.RFC = e.RFC
        WHERE u.estado = 'alta' AND u.categoria = 'user'";

$consulta = $conexion->query($sql);

while ($row = $consulta->fetch_assoc()) {
    $rfc = $row['RFC'];
    $puesto = $row['id_puesto'];

    // Buscar una plaza disponible
    $sql = "SELECT id_plaza
            FROM Plaza
            WHERE id_puesto = $puesto
            AND RFC IS NULL
            AND YEAR(elaboracion) = $ano
            LIMIT 1";

    $result = $conexion->query($sql);
    $plaza_row = $result->fetch_assoc();
    $plaza = $plaza_row['id_plaza'] ?? null;

    $sql = "SELECT id_plaza
    FROM Plaza
    WHERE RFC = '$rfc'
    AND YEAR(elaboracion) = $ano";

    $result = $conexion->query($sql);
    $total = $result->num_rows;

    if ($plaza && $total == 0) {
        // Insertar nuevo historial de plaza
        $sql = "INSERT INTO Historial_Plaza (id_plaza,RFC,fecha_inicio) VALUES ($plaza, '$rfc', '$ano-01-01')";
        $conexion->query($sql);

        // Actualizar Plaza con el RFC del usuario
        $sql = "UPDATE Plaza SET RFC = '$rfc' WHERE id_plaza = $plaza";
        $conexion->query($sql);

        // Buscar el historial de plaza anterior
        $sql = "SELECT id_historial_plaza
                FROM Historial_Plaza
                WHERE fecha_fin IS NULL
                AND RFC = '$rfc'
                AND YEAR(elaboracion) = " . ($ano - 1) . "
                ORDER BY id_historial_plaza DESC
                LIMIT 1";
        $historial_result = $conexion->query($sql);
        $historial_row = $historial_result->fetch_assoc();
        $ultimo_historial = $historial_row['id_historial_plaza'] ?? null;

        if ($ultimo_historial) {
            // Actualizar el historial de plaza anterior
            $sql = "UPDATE Historial_Plaza
                    SET fecha_fin = '" . ($ano - 1) . "-12-31'
                    WHERE id_historial_plaza = $ultimo_historial";
            $conexion->query($sql);
        }
    }
}

// Limpiar plazas antiguas
$sql = "UPDATE Plaza SET RFC = NULL WHERE YEAR(elaboracion) = " . ($ano - 1);
$conexion->query($sql);

$conexion->close();
