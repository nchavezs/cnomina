<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "UPDATE Plaza SET estado = 1 WHERE id_plaza = " . $id;
$consulta = $conexion->query($sql);

mysqli_close($conexion);
