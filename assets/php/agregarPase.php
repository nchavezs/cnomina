<?php
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST["id"]);
$id = $id[0];
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$fecha = $_POST["fecha"];
$categoria = $_POST["categoria"];
$observacion = $_POST["observacion"];

if ($fecha === "") {
    echo 2;
} else {
    $fecha = explode(" ", $fecha);
    $hora = $fecha[1] . " " . $fecha[2];
    $fecha = $fecha[0];

    $sql = "INSERT INTO Pase(RFC,fecha,hora,categoria,observacion) VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),'" . $hora . "', " . $categoria . ", '" . $observacion . "')";

    if (mysqli_query($conexion, $sql)) {
        echo 1;
    } else {
        echo 0;
    }

}

mysqli_close($conexion);
