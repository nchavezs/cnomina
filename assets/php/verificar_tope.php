<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id_prenomina = $_SESSION["id_prenomina"];
$ano = date("Y");

$sql = "SELECT * FROM Prenomina WHERE id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$del = date("Y-m-d",strtotime($prenomina["del"]."- 44 days"));
if(date("Y", strtotime($del)) < $ano){
    $del = $ano."-01-02";
    // SE LE AUMENTA 1 VER ZONA HORARIA
}

$datos["del"] = $del;
$datos["al"] = date("Y-m-d",strtotime($prenomina["al"]."+ 1 days"));

mysqli_close($conexion);

echo json_encode($datos);