<?php
include "conexion.php";
$conexion = conexion();
$consulta = "DELETE FROM Archivo";
if (mysqli_query($conexion, $consulta)) {
    $url = "./../nominas/*";

    $files = glob($url);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo 1;
} else {
    echo 0;
}

mysqli_close($conexion);
