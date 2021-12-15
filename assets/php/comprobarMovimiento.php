<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$datos = [];
$sql = "SELECT RFC FROM Movimiento WHERE id_movimiento = " . $id;
$consulta = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_row($consulta);
$datos["usuario"] = $usuario[0];

$sql = "SELECT MAX(id_movimiento) FROM Movimiento WHERE RFC = '" . $usuario[0] . "'";
$consulta = mysqli_query($conexion, $sql);
$maximo = mysqli_fetch_row($consulta);
$datos["maximo"] = $maximo[0];

mysqli_close($conexion);
echo json_encode($datos);
