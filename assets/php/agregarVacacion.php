<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];
$del = $_POST["del"];
$al = $_POST["al"];
$dias = $_POST["dias"];
$descripcion = $_POST["descripcion"];
$hoy = date("Y-m-d");

$sql = "INSERT INTO Vacacion(RFC,fecha,dias,del,al,descripcion,id_prenomina) VALUES(
    '" . $id . "',
    '" . $hoy . "',
    " . $dias . ",
    STR_TO_DATE('" . $del . "','%d/%m/%Y'),
    STR_TO_DATE('" . $al . "','%d/%m/%Y'),
    '" . $descripcion . "',
    " . $id_prenomina . "
)";

if (mysqli_query($conexion, $sql)) {
    echo 0;
} else {
    echo 1;
}

mysqli_close($conexion);
