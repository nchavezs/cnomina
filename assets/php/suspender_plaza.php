<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "UPDATE Plaza SET estado = 0, fecha_baja = CURDATE() WHERE id_plaza = " . $id;
$consulta = $conexion->query($sql);

$conexion->close();
