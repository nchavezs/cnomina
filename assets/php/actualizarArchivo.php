<?php
include "conexion.php";
$conexion = conexion();
$url = $_POST['url'];
$tabla = $_POST['tabla'];
$id = $_POST['id'];

$sql = "UPDATE " . $tabla . "  SET url = '" . $url . "' WHERE id_" . mb_strtolower($tabla) . " = " . $id;
$resultado = $conexion->query($sql);
$conexion->close();
