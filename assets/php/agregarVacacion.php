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

$sql = "INSERT INTO Vacacion(RFC,dias,del,al,descripcion,id_prenomina) VALUES(
    '" . $id . "',
    " . $dias . ",
    STR_TO_DATE('" . $del . "','%d/%m/%Y'),
    STR_TO_DATE('" . $al . "','%d/%m/%Y'),
    '" . $descripcion . "',
    " . $id_prenomina . "
)";

if ($conexion->query($sql)) {
    echo 0;
} else {
    echo 1;
}

$conexion->close();
