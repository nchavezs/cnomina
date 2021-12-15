<?php
include "conexion.php";
$conexion = conexion();
$url = $_POST['url'];
$tabla = $_POST['tabla'];
$id = $_POST['id'];

$sql = "UPDATE " . $tabla . "  SET url = '" . $url . "' WHERE id_" . strtolower($tabla) . " = " . $id;
$resultado = mysqli_query($conexion, $sql);
mysqli_close($conexion);
