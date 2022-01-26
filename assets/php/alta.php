<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$fecha = $_POST['fecha'];
$observaciones = trim($_POST['observaciones']);

$sql = "SELECT
Usuario.estado,
Empleado.fechaRelLab,
Empleado.RFC AS RFC
FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE Usuario.RFC = '" . $id . "' AND Usuario.estado = 'baja'";

if (($consulta = mysqli_query($conexion, $sql)) && (mysqli_num_rows($consulta) == 1)) {
    $resultado = mysqli_fetch_array($consulta);
    $inicio = $resultado['fechaRelLab'];
    $sql = "UPDATE Usuario SET estado = 'alta' WHERE RFC = '" . $id . "'";
    if (mysqli_query($conexion, $sql)) {
        $sql = "UPDATE Empleado SET fechaRelLab = '" . $fecha . "' WHERE RFC = '" . $id . "'";
        if (mysqli_query($conexion, $sql)) {
            $sql = "INSERT INTO Reingreso(RFC, fecha, inicio, observaciones)
            VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), STR_TO_DATE('" . $inicio . "','%d/%m/%Y'), '" . $observaciones . "')";
            if (mysqli_query($conexion, $sql)) {
                
                // $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $RFC."'";
                // $consulta = mysqli_query($conexion, $sql);

                // $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '" . $RFC . "' ORDER BY elaboracion desc LIMIT 1";
                // $consulta = mysqli_query($conexion, $sql);
                // $historial = mysqli_fetch_row($consulta);

                // $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('" . $fecha . "','%d/%m/%Y') WHERE id_historial_plaza = " . $historial[0];
                // $consulta = mysqli_query($conexion, $sql);

                echo 1;
            } 
        } 
    } 
}

mysqli_close($conexion);
