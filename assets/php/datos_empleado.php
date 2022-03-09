<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];

$banca = trim($_POST['banca']);
$afiliacion = trim($_POST['afiliacion']);
$domicilio = trim($_POST['domicilio']);
$email = trim($_POST['email']);
$telefono = $_POST['telefono'];

$sql = "UPDATE Usuario SET
telefono = '" . $telefono . "',
email = '" . $email . "',
domicilio = '" . $domicilio . "'
WHERE RFC = '" . $id . "'";

if ($conexion->query($sql)) {
    $_SESSION['email'] = $email;

    $sql = "UPDATE Empleado SET
    banca = '" . $banca . "',
    afiliacion = '" . $afiliacion . "'
    WHERE RFC = '" . $id . "'";
    $conexion->query($sql);

    echo 1;
} else {
    echo 0;
}

$conexion->close();
