<?php
include "conexion.php";
$conexion = conexion();

$ruta = './../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$ruta2 = './../usuario/';
if (!file_exists($ruta2)) {
    mkdir($ruta2, 0777, true);
}

$id = uniqid();

$archivo = $_FILES['file']['name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$ruta = $ruta . $id . "." . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
echo 'assets/usuario/' . $id . "." . $ext;
$conexion->close();
