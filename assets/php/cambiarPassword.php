<?php
session_start();
include "conexion.php";

$user = $_SESSION['usuario'];
$pass = $_POST['pass'];
$newPass = $_POST['newPass'];
$confirmacion = $_POST['confirmacion'];

$conexion = conexion();
$consultaPassword = mysqli_query($conexion, "SELECT contrasenia FROM Usuario WHERE RFC = '" . $user . "'"); //Devuelve campo password
$recogerPass = mysqli_fetch_assoc($consultaPassword);

if ($recogerPass['contrasenia'] === $pass && $newPass === $confirmacion) {
    $actualizarPass = "UPDATE Usuario SET contrasenia = '" . $newPass . "' WHERE RFC = '" . $user . "'";
    if (mysqli_query($conexion, $actualizarPass)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();
