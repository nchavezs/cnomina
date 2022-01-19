<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$texto = $_POST['texto'];
$myid = $_SESSION['usuario'];

$sql = "";
$consulta = mysqli_query($conexion, $sql);




mysqli_close($conexion);