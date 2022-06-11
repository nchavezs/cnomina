<?php
session_start();
include "conexion.php";
$conexion = conexion();
$categoria = $_POST['categoria'];
$id = $_POST['id'];

$sql = "DELETE FROM " . $categoria . " WHERE id_" . mb_strtolower($categoria) . " = " . $id;

if ($conexion->query($sql)) {
    echo 1;
} else {
    echo 0;
}

$conexion->close();