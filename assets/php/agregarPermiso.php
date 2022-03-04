<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];
$del = $_POST["fecha2"];
$al = $_POST["fecha3"];
$categoria = $_POST["categoria"];
$dias = $_POST["dias"];
$descripcion = $_POST["descripcion"];
$materno = $_POST["materno"];

$sql = "INSERT INTO Permiso(RFC,dias,del,al,categoria,descripcion,materno,id_prenomina) VALUES(
    '" . $id . "', 
    " . $dias . ",
    STR_TO_DATE('" . $del . "','%d/%m/%Y'),
    STR_TO_DATE('" . $al . "','%d/%m/%Y'),
    " . $categoria . ",
    '" . $descripcion . "', 
    " . $materno . ",
    " . $id_prenomina . "
)";

if ($conexion->query($sql)) {
    echo 0;
} else {
    echo 1;
}

$conexion->close();
