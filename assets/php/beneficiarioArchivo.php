<?php
include "conexion.php";
$conexion = conexion();
session_start();
$usuario = $_SESSION['usuario'];

$ruta = './../archivos_usuario/'.$usuario.'/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$ruta2 = './../beneficiario/';
if (!file_exists($ruta2)) {
    mkdir($ruta2, 0777, true);
}

$id = uniqid();

$archivo = $_FILES['file']['name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$ruta = $ruta . $id . "." . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
echo 'assets/beneficiario/' . $id . "." . $ext;
$conexion->close();