<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];
$nombre = $_POST['nombre'];
$parentesco = $_POST['parentesco'];
$url = trim($_POST["url"]);
$sql = "INSERT INTO Beneficiario(RFC, beneficiario, parentesco, url) VALUES('" . $id . "', '" . $nombre . "' ,'" . $parentesco . "', NULLIF('" . $url . "', ''))";
$conexion->query($sql);

if ($url !== "") {
    $file = explode("/", $url);
    $de = "./../archivos_usuario/". $id."/". $file[2];
    $a = "./../beneficiario/" . $file[2];
    copy($de, $a);

    $files = glob('./../archivos_usuario/'. $id.'/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }

    }
}

echo mysqli_insert_id($conexion);
$conexion->close();
