<?php
include "conexion.php";
// $conexion = conexion();
// $archivo = $_FILES['file']['tmp_name'];
// $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
// $ruta = "../img/logo.".$ext;
// move_uploaded_file($archivo, $ruta);

$max_ancho = 800;
$max_alto = 600;
$tipo = $_FILES["file"]["type"];

if ($tipo == "image/jpeg" || $tipo == "image/png") {
    $ruta = "../img/logo.png";

    $medidasimagen = getimagesize($_FILES['file']['tmp_name']);

    $nombrearchivo = $_FILES['file']['name'];

    $nombretemporal = $_FILES['file']['tmp_name'];

    if ($tipo == 'image/jpeg') {
        $original = imagecreatefromjpeg($nombretemporal);
    } else if ($tipo == 'image/png') {
        $original = imagecreatefrompng($nombretemporal);
    }

    list($ancho, $alto) = getimagesize($nombretemporal);

    $x_ratio = $max_ancho / $ancho;
    $y_ratio = $max_alto / $alto;

    if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {
        $ancho_final = $ancho;
        $alto_final = $alto;
    } elseif (($x_ratio * $alto) < $max_alto) {
        $alto_final = ceil($x_ratio * $alto);
        $ancho_final = $max_ancho;
    } else {
        $ancho_final = ceil($y_ratio * $ancho);
        $alto_final = $max_alto;
    }

    $lienzo = imagecreatetruecolor($ancho_final, $alto_final);
    imagecopyresampled($lienzo, $original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);
    imagedestroy($original);

    $bandera = false;
    if ($tipo == 'image/jpeg') {
        $bandera = imagejpeg($lienzo, $ruta);
    } else if ($tipo == 'image/png') {
        $bandera = imagepng($lienzo, $ruta);
    }

    if ($bandera) {
        $conexion = conexion();
        $sql = "UPDATE Configuracion SET logo = 'logo.png?v=" . uniqid() . "'";
        mysqli_query($conexion, $sql);
        mysqli_close($conexion);
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}
