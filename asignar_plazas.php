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
    $user_rfc = $row['RFC'];
    $user_puesto = $row['id_puesto'];

    // Buscar una plaza disponible
    $sql_plaza = "SELECT id_plaza
                  FROM Plaza
                  WHERE id_puesto = $user_puesto
                  AND RFC IS NULL
                  AND YEAR(elaboracion) = $ano
                  LIMIT 1";
    $plaza_result = $conexion->query($sql_plaza);
    $plaza_row = $plaza_result->fetch_assoc();
    $plaza = $plaza_row['id_plaza'] ?? null;

    if ($plaza) {
        // Actualizar Plaza con el RFC del usuario
        $sql_update_plaza = "UPDATE Plaza
                             SET RFC = '$user_rfc'
                             WHERE id_plaza = $plaza";
        $conexion->query($sql_update_plaza);

        // Buscar el historial de plaza anterior
        $sql_historial = "SELECT id_historial_plaza
                          FROM Historial_Plaza
                          WHERE fecha_fin IS NULL
                          AND RFC = '$user_rfc'
                          AND YEAR(elaboracion) = ".($ano - 1)."
                          ORDER BY id_historial_plaza DESC
                          LIMIT 1";
        $historial_result = $conexion->query($sql_historial);
        $historial_row = $historial_result->fetch_assoc();
        $ultimo_historial = $historial_row['id_historial_plaza'] ?? null;

        if ($ultimo_historial) {
            // Actualizar el historial de plaza anterior
            $sql_update_historial = "UPDATE Historial_Plaza
                                     SET fecha_fin = '".($ano - 1)."-12-31'
                                     WHERE id_historial_plaza = $ultimo_historial";
            $conexion->query($sql_update_historial);

            // Insertar nuevo historial de plaza
            $sql_insert_historial = "INSERT INTO Historial_Plaza (RFC, id_plaza, fecha_inicio)
                                     VALUES ('$user_rfc', $plaza, '$ano-01-01')";
            $conexion->query($sql_insert_historial);
        }
    }
}

// Limpiar plazas antiguas
$sql_clean_plazas = "UPDATE Plaza SET RFC = NULL WHERE YEAR(elaboracion) = ".($ano - 1);
$conexion->query($sql_clean_plazas);

$conexion->close();