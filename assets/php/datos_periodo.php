<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id_prenomina = $_SESSION["id_prenomina"];
$ano = $_SESSION["ano"];

$sql = "SELECT * FROM Prenomina WHERE id_prenomina = ".$id_prenomina;
$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$datos =[
    "ano" => $ano,
    "al" => $prenomina["al"],
    "del" => $prenomina["del"],
    "id_prenomina" => $id_prenomina
];

echo json_encode($datos);

