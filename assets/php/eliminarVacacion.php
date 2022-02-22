<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT RFC FROM Vacacion WHERE id_vacacion = " . $id;
$res = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_row($res);

$sql1 = "SELECT url FROM Vacacion WHERE id_vacacion = " . $id;
$consulta1 = mysqli_query($conexion, $sql1);
$res1 = mysqli_fetch_row($consulta1);
if ($res1[0] != null) {
    $dir = explode("/", $res1[0]);
    $url = "./../vacacion/" . $dir[2] . "/*";

    $files = glob($url);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }

    }
}

echo $usuario[0];
$sql = "DELETE FROM Vacacion WHERE id_vacacion = " . $id;
mysqli_query($conexion, $sql);

mysqli_close($conexion);
