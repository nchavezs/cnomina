<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$departamento = $_POST['departamento'];
$trabajador = $_POST['trabajador'];

$sql = "SELECT * FROM Puesto WHERE nombre = '" . $nombre . "' AND id_departamento = ".$departamento;
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql = "INSERT INTO Puesto(nombre, id_departamento, id_trabajador) VALUES('" . $nombre . "', ".$departamento.", ".$trabajador.")";
    if ($conexion->query($sql)) {
        $sql = "UPDATE Puesto SET id_trabajador = ".$trabajador." WHERE nombre = '" . $nombre."'";
        $conexion->query($sql);
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();