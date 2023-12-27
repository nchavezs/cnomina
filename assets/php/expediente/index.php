<?php
include "../conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);


include "./menu.php";
include "./expediente.php";

$conexion->close();