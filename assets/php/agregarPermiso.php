<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];
// $fecha1 = $_POST["fecha1"];
$fecha1 = date("d/m/Y");
$fecha2 = $_POST["fecha2"];
$fecha3 = $_POST["fecha3"];
$categoria = $_POST["categoria"];
$dias = $_POST["dias"];
$descripcion = $_POST["descripcion"];
$materno = $_POST["materno"];

$sql = "INSERT INTO Permiso(RFC,fecha,dias,del,al,categoria,descripcion,materno) VALUES('" . $id . "', STR_TO_DATE('" . $fecha1 . "','%d/%m/%Y')," . $dias . ",STR_TO_DATE('" . $fecha2 . "','%d/%m/%Y'),STR_TO_DATE('" . $fecha3 . "','%d/%m/%Y')," . $categoria . ",'" . $descripcion . "', " . $materno . ")";

if (mysqli_query($conexion, $sql)) {
    echo 0;
} else {
    echo 1;
}

mysqli_close($conexion);
