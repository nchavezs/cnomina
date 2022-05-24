<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];
$nombre = mb_strtoupper(trim($_POST['nombre']));
$email = trim($_POST['email']);
$telefono = $_POST['telefono'];

$sql = "UPDATE Usuario SET nombre = '" . $nombre . "', telefono = '" . $telefono . "',
email = '" . $email . "' WHERE RFC = '" . $id . "'";
if ($conexion->query($sql)) {
    $_SESSION['nombre'] = $nombre;
    $_SESSION['email'] = $email;
    $_SESSION['telefono'] = $telefono;
    echo 1;
} else {
    echo 0;
}

$conexion->close();
