<?php
   include("conexion.php");
   $conexion = conexion();
   $id = explode("-",$_POST["id"]);
	$id = $id[0];
	$fecha1 = $_POST["fecha1"];
	$fecha2 = $_POST["fecha2"];
	$fecha3 = $_POST["fecha3"];
	$dias = $_POST["dias"];
	$descripcion = $_POST["descripcion"];

	$sql = "INSERT INTO Vacacion(RFC,fecha,dias,del,al,descripcion) VALUES('".$id."', STR_TO_DATE('".$fecha1."','%d/%m/%Y'),".$dias.",STR_TO_DATE('".$fecha2."','%d/%m/%Y'),STR_TO_DATE('".$fecha3."','%d/%m/%Y'),'".$descripcion."')";

	if(mysqli_query($conexion, $sql))
		echo 0;
	else
		echo 1;

	mysqli_close($conexion);
?>
