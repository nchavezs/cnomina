<?php
session_start();
include "conexion.php";
$conexion = conexion();
$ruta = './../mensajes/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$id = $_SESSION['usuario'];
$sql = "SELECT COUNT(*) FROM Chat WHERE RFC = '" . $id."'";
$resultado = mysqli_query($conexion, $sql);
$res = mysqli_fetch_row($resultado);
$ruta = './../mensajes/' . $res[0] . '_' . $id;
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$archivo = $_FILES['file']['name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$ruta = $ruta . '/' . $id . '_' . date("Y_m_d_H_i_s") . '.' . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
$ruta2 = 'assets/mensajes/' . $res[0] . '_' . $id . '/' . $id . '_' . date("Y_m_d_H_i_s") . '.' . $ext;
mysqli_close($conexion);
echo $ruta2;
