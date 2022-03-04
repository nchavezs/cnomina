<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];
setlocale(LC_ALL, "spanish");
$fechas = $_POST["fechas"];
$dias = $_POST["dias"];
$motivo = $_POST["motivo"];

$sql = "INSERT INTO Descuento(RFC,dias,fechas,motivo,id_prenomina) VALUES(
    '" . $id . "',
    " . $dias . ", 
    '" . $fechas . "',
    '" . $motivo . "',
    " . $id_prenomina . "
)";

if ($dias == 0) {
    echo 1;
} else {
    if ($conexion->query($sql)) {
        echo 0;
    } else {
        echo 1;
    }

}

$conexion->close();
