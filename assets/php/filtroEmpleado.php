<?php
include 'conexion.php';
$conexion = conexion();
$texto = $_POST["texto"];

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $texto . "'";

if (($consulta = $conexion->query($sql)) && trim($texto) != "" && mysqli_num_rows($consulta) > 0) {
    $resultado = mysqli_fetch_row($consulta);
    echo $resultado[0];
} else {
    echo 0;
}

$conexion->close();
