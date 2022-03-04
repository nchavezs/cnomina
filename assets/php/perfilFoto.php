<?php
session_start();
include 'conexion.php';
$conexion = conexion();
$id = $_SESSION['usuario'];
$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if ($consulta && mysqli_num_rows($consulta) == 1) {
    $res = mysqli_fetch_array($consulta);
    if (!is_null($res['urlFoto'])) {
        $x = explode("/", $res[5]);
        $ruta = "../" . $x[1] . "/" . $x[2] . "/" . $x[3];
        if (file_exists($ruta)) {
            echo $res['urlFoto'];
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
} else {
    echo 0;
}

$conexion->close();
