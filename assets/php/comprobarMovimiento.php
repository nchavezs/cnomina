<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$datos = [];
$sql = "SELECT RFC FROM Movimiento WHERE id_movimiento = " . $id;
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_row($consulta);
$datos["usuario"] = $usuario[0];

$sql = "SELECT MAX(id_movimiento) FROM Movimiento WHERE RFC = '" . $usuario[0] . "'";
$consulta = $conexion->query($sql);
$maximo = mysqli_fetch_row($consulta);
$datos["maximo"] = $maximo[0];

$conexion->close();
echo json_encode($datos);
