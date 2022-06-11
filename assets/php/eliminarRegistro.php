<?php
session_start();
include "conexion.php";
$conexion = conexion();
$consulta = "DELETE FROM Archivo WHERE id_archivo = " . $_POST['id'];

if (mysqli_query($conexion, $consulta)) {
    echo 1;
} else {
    echo 0;
}

$conexion->close();