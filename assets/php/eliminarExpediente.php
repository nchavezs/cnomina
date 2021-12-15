<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$nombre = $_POST['nombre'];

$sql = "SELECT ".$nombre." FROM Expediente WHERE RFC = '".$id."'";
$consulta = mysqli_query($conexion, $sql);
$expediente = mysqli_fetch_row($consulta);

$url = explode("/", $expediente[0]);
$url = "./../expediente/".$url[2];
if (is_file($url)) {
    unlink($url);
}

$sql = "UPDATE Expediente SET ".$nombre." = NULL WHERE RFC = '". $id."'";
if (mysqli_query($conexion, $sql)) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($conexion);
