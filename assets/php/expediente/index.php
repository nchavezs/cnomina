<?php
include "../conexion.php";
include "../rol.php";
session_start();
$conexion = conexion();
$id = $_POST['id'];
$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);


include "./menu.php";
include "./expediente.php";

$conexion->close();