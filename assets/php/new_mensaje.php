<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];
$mensaje = $_POST["mensaje"];

$sql = "INSERT INTO Correos(RFC,mensaje) VALUES('" . $id . "', '" . $mensaje . "')";

if (mysqli_query($conexion, $sql))
    echo 1;
else
    echo 0;

mysqli_close($conexion);
