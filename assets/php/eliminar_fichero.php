<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Fichero WHERE id_fichero = ".$id;
$row = $consulta->fetch_assoc();
$archivo_anterior = "../ficheros/".$row["RFC"] . "/" . $row['url'];

if (file_exists($archivo_anterior)) {
    unlink($archivo_anterior);
}

$sql = "DELETE FROM Fichero WHERE id_fichero = ".$id;
$consulta = $conexion->query($sql);
$conexion->close();