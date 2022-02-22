<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Pase WHERE id_pase = " . $id;
$consulta = $conexion->query($sql);
$res = mysqli_fetch_array($consulta);

if ($res["url"] != null) {
    $dir = explode("/", $res["url"]);
    $url = "./../pase/" . $dir[2] . "/*";

    $files = glob($url);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }

    }
}
$sql = "DELETE FROM Pase WHERE id_pase = " . $id;
echo $res["RFC"];

$conexion->query($sql);

mysqli_close($conexion);
