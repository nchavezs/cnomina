<?php
include 'conexion.php';
$conexion = conexion();
$texto = $_POST["texto"];

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $texto . "'";

if (($consulta = mysqli_query($conexion, $sql)) && trim($texto) != "" && mysqli_num_rows($consulta) > 0) {
    $resultado = mysqli_fetch_row($consulta);
    echo $resultado[0];
} else {
    echo 0;
}

mysqli_close($conexion);
