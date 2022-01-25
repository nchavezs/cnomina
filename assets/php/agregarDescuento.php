<?php
	include("conexion.php");
	$conexion = conexion();
	$id = explode("-",$_POST["id"]);
	$id = $id[0];
	setlocale(LC_ALL, "spanish");
	$fecha = date("d/m/Y");
	$fecha1 = $_POST["fecha1"];
	$dias = $_POST["dias"];
	$motivo = $_POST["motivo"];

	$sql = "INSERT INTO Descuento(RFC,dias,fecha,fechas,motivo) VALUES('".$id."',".$dias.", STR_TO_DATE('".$fecha."','%d/%m/%Y'),'".$fecha1."','".$motivo."')";

	if($dias == 0){
		echo 1;
	}else{
			if(mysqli_query($conexion, $sql))
				echo 0;
			else
				echo 1;
	}

	mysqli_close($conexion);
?>
