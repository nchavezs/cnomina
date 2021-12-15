<?php
session_start();
include "rol.php";
$rol = rol();
if ($rol != 1) {
    echo 2;
} else {
    $conexion = conexion();
    $categoria = $_POST['categoria'];
    $id = $_POST['id'];

    $sql = "DELETE FROM " . $categoria . " WHERE id_" . strtolower($categoria) . " = " . $id;

    if (mysqli_query($conexion, $sql)) {
        echo 1;
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}
