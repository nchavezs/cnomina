<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST["id"]);
$id = $id[0];
setlocale(LC_ALL, "spanish");
$fechas = $_POST["fechas"];
$categoria = $_POST["categoria"];
$observacion = $_POST["observacion"];

if ($fechas === "") {
    echo 2;
} else {
    $sql = "";
    $fechas = explode(",", $fechas);
    foreach($fechas as $fecha){
        $fecha = explode(" ", $fecha);
        $hora = $fecha[1] . " " . $fecha[2];
        $fecha = $fecha[0];
        $sql = $sql."INSERT INTO Pase(RFC,fecha,hora,categoria,observacion,id_prenomina) VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),'" . $hora . "', " . $categoria . ", '" . $observacion . "',".$id_prenomina.");";
    }

    if ($conexion->multi_query($sql)) {
        echo 1;
    } else {
        echo 0;
    }

}

$conexion->close();
