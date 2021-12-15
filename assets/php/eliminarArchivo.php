<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$tabla = $_POST['tabla'];

$sql = "UPDATE " . $tabla . " SET url = NULL WHERE id_" . strtolower($tabla) . " = " . $id;
if (mysqli_query($conexion, $sql)) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($conexion);
