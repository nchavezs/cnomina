<?php
include "conexion.php";
$conexion = conexion();

$ruta = './../expediente/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$archivo = $id."_".$nombre;

$sql = "SELECT ".$nombre." FROM Expediente WHERE RFC = '".$id."'";
$consulta = $conexion->query($sql);
$expediente = mysqli_fetch_row($consulta);

if ($expediente[0] != null) {
    $url = explode("/", $expediente[0]);
    $url = "./../expediente/".$url[2];
    if (is_file($url)) {
        unlink($url);
    }
}

$file = $_FILES['file']['name'];
$extension = pathinfo($file, PATHINFO_EXTENSION);
$ruta = $ruta . $archivo . "." . $extension;
move_uploaded_file($_FILES['file']['tmp_name'], $ruta);

$ruta =  'assets/expediente/' . $archivo . "." . $extension;

$sql = "UPDATE Expediente SET ".$nombre." = '".$ruta."' WHERE RFC = '".$id."'";
$consulta = $conexion->query($sql);

$conexion->close();
