<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));

$sql = "SELECT * FROM Departamento WHERE nombre = '" . $nombre . "'";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql1 = "INSERT INTO Departamento(nombre) VALUES('" . $nombre . "')";
    if (mysqli_query($conexion, $sql1)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();