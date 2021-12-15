<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$condicion = $_POST['condicion'];

$sql = "SELECT RFC FROM Pase WHERE id_pase = " . $id;
$res = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_row($res);
echo $usuario[0];

if ($condicion == 1) {
    $sql1 = "SELECT url FROM Pase WHERE id_pase = " . $id;
    $consulta1 = mysqli_query($conexion, $sql1);
    $res1 = mysqli_fetch_row($consulta1);

    if ($res1[0] != null) {
        $dir = explode("/", $res1[0]);
        $url = "./../pase/" . $dir[2] . "/*";

        $files = glob($url);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }

        }
    }
    $sql = "DELETE FROM Pase WHERE id_pase = " . $id;
    mysqli_query($conexion, $sql);

}

mysqli_close($conexion);
