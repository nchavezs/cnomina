<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(strtoupper($_POST['nombre']));
$categoria = $_POST['categoria'];

$sql = "SELECT * FROM " . $categoria . " WHERE nombre = '" . $nombre . "'";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql1 = "INSERT INTO " . $categoria . "(nombre) VALUES('" . $nombre . "')";
    if (mysqli_query($conexion, $sql1)) {
        echo 1;
    } else {
        echo 0;
    }

} else {
    echo 2;
}

mysqli_close($conexion);
