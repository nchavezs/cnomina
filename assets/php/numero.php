<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];

$sql = "SELECT COUNT(*) FROM Mensaje WHERE estado = 0 AND receptor = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
if ($consulta) {
    $total = mysqli_fetch_row($consulta);
    echo $total[0];
} else {
    echo 0;
}

mysqli_close($conexion);
