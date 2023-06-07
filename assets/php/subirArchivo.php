<?php
include "conexion.php";
$conexion = conexion();

$id = $_POST['id'];
$tabla = $_POST['tabla'];
$usuario = $_POST['usuario'];

$ruta = './../' . mb_strtolower($tabla) . '/' . $id . '_' . $usuario;
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}
$nombre = uniqid().".".$ext;
$archivo = $_FILES['file']['name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$ruta = $ruta . '/'.$nombre;
move_uploaded_file($_FILES['file']['tmp_name'], $ruta);

$url = 'assets/' . mb_strtolower($tabla) . '/' . $id . '_' . $usuario . '/'.$nombre;

$sql = "UPDATE " . $tabla . "  SET url = '" . $url . "' WHERE id_" . mb_strtolower($tabla) . " = " . $id;
if ($conexion->query($sql)) {
    echo 1;
} else {
    echo 0;
}

$conexion->close();
