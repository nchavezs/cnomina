<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
if ($rol == 2) {
    echo 2;
} else {
    $conexion = conexion();
    $consulta = "DELETE FROM Archivo WHERE id_archivo = " . $_POST['id'];

    if (mysqli_query($conexion, $consulta)) {
        echo 1;
    } else {
        echo 0;
    }

    $conexion->close();
}
