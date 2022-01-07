<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
if ($rol != 1) {
    echo 2;
} else {
    $conexion = conexion();
    $id = $_POST['id'];
    $sql = "SELECT * FROM Plaza WHERE id_plaza = " . $id;
    $consulta = mysqli_query($conexion, $sql);
    $plaza = mysqli_fetch_array($consulta);
    if ($plaza["RFC"] == null) {
        $sql = "DELETE FROM Plaza WHERE id_plaza = " . $id;
        if (mysqli_query($conexion, $sql)) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}