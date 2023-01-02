<?php
session_start();
setlocale(LC_ALL, "spanish");
include "conexion.php";
$conexion = conexion();
$ano = $_SESSION["ano"];
$id_periodo = $_SESSION["id_periodo"];
$id_prenomina = $_SESSION["id_prenomina"];

$sql = "SELECT * FROM Prenomina WHERE id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$nombre_periodo = strftime("del %e de %B", strtotime($prenomina["del"])) .
strftime(" al %e de %B", strtotime($prenomina["al"])) . " del " . $ano;

$sql = "SELECT *,
(SELECT nombre FROM Periodo WHERE id_periodo = Prenomina.id_periodo) AS periodo 
FROM Prenomina WHERE id_periodo = " . $id_periodo . " AND YEAR(del) = " . $ano . " AND id_prenomina <= " . $prenomina["id_prenomina"];

$consulta = $conexion->query($sql);
$numero_prenomina = mysqli_num_rows($consulta);
$prenomina = mysqli_fetch_array($consulta);
$conexion->close();

echo '<a class="cambiar_periodo" href="./periodo"><i class="material-icons mr-1">style</i> Periodo ' . $numero_prenomina . '</a>' .
'<a href="#" class="ml-3 nombre_periodo oculto">' . $prenomina["periodo"] . '</a>' .
'<a href="#" class="ml-3 nombre_periodo oculto"><i class="material-icons mr-2">bubble_chart</i>' . $nombre_periodo . '</a>';
