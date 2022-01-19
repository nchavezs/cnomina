<?php
session_start();
include "conexion.php";
$conexion = conexion();
$texto = $_SESSION['texto'];

$sql = "";
$consulta = mysqli_query($conexion, $sql);




mysqli_close($conexion);