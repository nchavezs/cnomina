<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];
$sql = "SELECT COUNT(*) FROM Chat WHERE RFC = '" . $id."'";
$resultado = $conexion->query($sql);
$res = mysqli_fetch_row($resultado);
$files = glob('./../mensajes/' . $res[0] . '_' . $id . '/*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}