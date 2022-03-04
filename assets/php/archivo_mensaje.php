<?php
session_start();
include "conexion.php";
$conexion = conexion();
$texto = $_SESSION['texto'];

$sql = "";
$consulta = $conexion->query($sql);




$conexion->close();