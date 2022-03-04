<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$password = $_POST['password'];
$confirmar = $_POST['confirmar'];

if ($password === $confirmar) {
    $sql = "UPDATE Usuario SET nombre = '" . $nombre . "', contrasenia = '" . $password . "' WHERE RFC = 'admin' AND categoria = 'admin'";
    if ($conexion->query($sql)) {
        session_start();
        $_SESSION['nombre'] = $nombre;
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();
