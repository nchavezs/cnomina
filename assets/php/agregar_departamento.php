<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));

$sql = "SELECT * FROM Departamento WHERE nombre = '" . $nombre . "'";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql = "INSERT INTO Departamento(nombre) VALUES('" . $nombre . "')";
    if ($conexion->query($sql)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();