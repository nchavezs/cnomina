<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql1 = "SELECT * FROM Empleado WHERE RFC = '" . $id . "'";
$consulta1 = mysqli_query($conexion, $sql1);
$res = mysqli_fetch_array($consulta1);
$usuario = str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT);
$sql2 = "UPDATE Usuario SET contrasenia = '" . $usuario . "' WHERE RFC = '" . $id . "'";

mysqli_query($conexion, $sql2);
$conexion->close();
