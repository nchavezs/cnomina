<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "UPDATE Plaza SET estado = 1, fecha_baja = NULL WHERE id_plaza = " . $id;
$consulta = $conexion->query($sql);

$conexion->close();
