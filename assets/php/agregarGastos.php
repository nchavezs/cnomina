<?php
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST["id"]);
$id = $id[0];
$fecha = $_POST["fecha"];
$concepto = trim($_POST["concepto"]);
$monto = trim($_POST["monto"]);
$nombre = trim($_POST["nombre"]);

$sql = "INSERT INTO Gastos(RFC,fecha,monto,nombre,concepto) VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y')," . $monto . ",'" . $nombre . "', '" . $concepto . "')";

if (mysqli_query($conexion, $sql)) {
    echo 0;
} else {
    echo 1;
}

mysqli_close($conexion);