<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];

$sql = "UPDATE Usuario SET nombre = '" . $_POST['nombre'] . "', telefono = '" . $_POST['telefono'] . "',
email = '" . $_POST['email'] . "' WHERE RFC = '" . $id . "'";
if (mysqli_query($conexion, $sql)) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($conexion);
