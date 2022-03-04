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

    $sql = "SELECT url FROM Prenomina WHERE id_prenomina = " . $id;
    $consulta = $conexion->query($sql);
    $prenomina = mysqli_fetch_array($consulta);
    $file = "../prenominas/".$prenomina["url"];
    
    if (is_file($file)) {
        unlink($file);
    }
    $sql = "DELETE FROM Prenomina WHERE id_prenomina = " . $id;
    if ($conexion->query($sql)) {
        echo 1;
    } else {
        echo 0;
    }

    $conexion->close();
}
