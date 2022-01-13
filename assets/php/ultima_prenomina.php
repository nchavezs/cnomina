<?php
include "conexion.php";
$conexion = conexion();
$periodo = $_POST["periodo"];
$ano = date("Y");

$sql = "SELECT * FROM Prenomina WHERE YEAR(al) = ".$ano." AND periodo = '".$periodo."' ORDER BY al DESC LIMIT 1";
$consulta = mysqli_query($conexion, $sql);
if($consulta && mysqli_num_rows($consulta) > 0){
	$prenomina = mysqli_fetch_array($consulta);
	echo date("d/m/Y", strtotime($historial["al"]));
}else{
	echo "01/01/".$ano;
}

mysqli_close($conexion);