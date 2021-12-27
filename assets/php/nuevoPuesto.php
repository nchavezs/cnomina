<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(strtoupper($_POST['nombre']));
$departamento = $_POST['departamento'];
$cantidad = $_POST['cantidad'];

$sql = "SELECT * FROM Puesto WHERE nombre = '" . $nombre . "' AND id_departamento = ".$departamento;
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql1 = "INSERT INTO Puesto(nombre, id_departamento, cantidad) VALUES('" . $nombre . "', ".$departamento.", ".$cantidad.")";
    if (mysqli_query($conexion, $sql1)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

mysqli_close($conexion);