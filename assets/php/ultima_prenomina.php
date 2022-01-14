<?php
include "conexion.php";
$conexion = conexion();
$periodo = $_POST["periodo"];
$ano = date("Y");

$sql = "SELECT * FROM Prenomina WHERE YEAR(al) = ".$ano." AND periodo = '".$periodo."' ORDER BY al DESC LIMIT 1";
$consulta = mysqli_query($conexion, $sql);
if($consulta && mysqli_num_rows($consulta) > 0){
	$prenomina = mysqli_fetch_array($consulta);
	echo date("d/m/Y", strtotime($prenomina["al"]));
}else{
	echo "31/12/". ($ano - 1) ;
}

mysqli_close($conexion);