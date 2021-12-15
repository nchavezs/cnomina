<?php
	include("conexion.php");
   $conexion = conexion();
	$categoria = $_POST['categoria'];
	
	$file = fopen("../archivos/archivo.txt", "r");
	while(!feof($file)){
		$dato = trim(fgets($file));
			$sql = "SELECT * FROM ".$categoria." WHERE nombre = '".$dato."'";
			$consulta = mysqli_query($conexion, $sql);
			if($consulta && (mysqli_num_rows($consulta) == 0)){
				if($dato !== ''){
					$sql1 = "INSERT INTO ".$categoria."(nombre) VALUES('".$dato."')";
					mysqli_query($conexion, $sql1);
					echo $dato;
				}
			}
	}
	fclose($file);
	mysqli_close($conexion);
?>