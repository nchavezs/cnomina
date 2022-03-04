<?php

session_start();
$id = $_SESSION['usuario'];

include 'conexion.php';
$ruta = './../img/perfil/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$key = $id . "_" . uniqid();
$max_ancho = 800;
$max_alto = 600;

if (($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png")) {
    $ruta = './../img/perfil/' . $key . ".jpg";

    $medidasimagen = getimagesize($_FILES['file']['tmp_name']);

    $nombrearchivo = $_FILES['file']['name'];

    $rtOriginal = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['type'] == 'image/jpeg') {
        $original = imagecreatefromjpeg($rtOriginal);
    } else if ($_FILES['file']['type'] == 'image/png') {
        $original = imagecreatefrompng($rtOriginal);
    }

    list($ancho, $alto) = getimagesize($rtOriginal);

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

    $cal = 8;

    $bandera = false;
    if ($_FILES['file']['type'] == 'image/jpeg') {
        $bandera = imagejpeg($lienzo, $ruta);
    } else if ($_FILES['file']['type'] == 'image/png') {
        $bandera = imagepng($lienzo, $ruta);
    }

    if ($bandera) {
        $conexion = conexion();
        $ruta = 'assets/img/perfil/' . $key . ".jpg";

        $sql = "SELECT urlFoto FROM Usuario WHERE RFC = '" . $id . "'";
        $consulta = $conexion->query($sql);
        $foto = mysqli_fetch_row($consulta);
        if ($consulta && $foto[0] != null) {
            $foto = explode("/", $foto[0]);
            $foto = "./../" . $foto[1] . "/" . $foto[2] . "/" . $foto[3];
            if (is_file($foto)) {
                unlink($foto);
            }
        }

        $sql = "UPDATE Usuario SET urlFoto = '" . $ruta . "' WHERE RFC = '" . $id . "'";
        if ($conexion->query($sql)) {
            $_SESSION['foto'] = $ruta;
            echo $ruta;
        } else {
            echo 0;
        }
        $conexion->close();

    } else {
        echo 0;
    }
} else {
    echo 0;
}
