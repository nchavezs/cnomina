<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$tabla = $_POST['tabla'];

$sql = "UPDATE " . $tabla . " SET url = NULL WHERE id_" . mb_strtolower($tabla) . " = " . $id;
if ($conexion->query($sql)) {
    echo 1;
} else {
    echo 0;
}

$conexion->close();
