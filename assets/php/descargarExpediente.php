<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$nombre = $_POST['nombre'];

$sql = "SELECT ".$nombre." FROM Expediente WHERE RFC = '".$id."'";
$consulta = mysqli_query($conexion, $sql);
$expediente = mysqli_fetch_row($consulta);
$url = $expediente[0];
echo $url;

mysqli_close($conexion);
