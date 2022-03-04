<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];
$mensaje = $_POST["mensaje"];

$sql = "INSERT INTO Correos(RFC,mensaje) VALUES('" . $id . "', '" . $mensaje . "')";

if ($conexion->query($sql))
    echo 1;
else
    echo 0;

$conexion->close();
