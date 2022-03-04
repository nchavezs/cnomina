<?php
include "conexion.php";
$conexion = conexion();
session_start();
$rfc = $_SESSION['usuario'];
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$parentesco = $_POST['parentesco'];
$url = trim($_POST["url"]);
$sql = "UPDATE Beneficiario SET beneficiario = '" . $nombre . "', parentesco = '" . $parentesco . "', url = '" . $url . "' WHERE id_beneficiario = " . $id;
$conexion->query($sql);

if ($url !== "") {
    $file = explode("/", $url);
    $de = "./../archivos_usuario/". $rfc."/". $file[2];
    $a = "./../beneficiario/" . $file[2];
    copy($de, $a);

    $files = glob('./../archivos_usuario/'. $rfc.'/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }

    }
}

echo mysqli_insert_id($conexion);
$conexion->close();
