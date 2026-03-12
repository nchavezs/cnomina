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

    $sql = "SELECT id_plaza
    FROM Plaza
    WHERE RFC = '$rfc'
    AND YEAR(elaboracion) = $ano";

    $result = $conexion->query($sql);
    $total = $result->num_rows;

    if ($total == 0) {
        $sql = "INSERT INTO Plaza (dias, id_puesto, RFC) VALUES(365, $puesto, '$rfc')";
        $result = $conexion->query($sql);
        $plaza = $conexion->insert_id;

        // Insertar nuevo historial de plaza
        $sql = "INSERT INTO Historial_Plaza (id_plaza,RFC,fecha_inicio) VALUES ($plaza, '$rfc', '$ano-01-01')";
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
