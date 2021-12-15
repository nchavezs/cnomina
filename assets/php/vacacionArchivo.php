<?php
	include("conexion.php");
   $conexion = conexion();
   $id = explode("-",$_POST["id"]);
	$id = $id[0];

	$sql = "SELECT MAX(id_vacacion) FROM Vacacion";
	$resultado = mysqli_query($conexion, $sql);
	$res = mysqli_fetch_row($resultado);
	if(is_null($res[0]))
		$res[0] = 1;
	else
		$res[0] = $res[0] + 1;
	$ruta = './../vacaciones/'.$res[0].'_'.$id;
	if (!file_exists($ruta))
   	mkdir($ruta, 0777, true);
	$archivo = $_FILES['file']['name'];
	$ext = pathinfo($archivo, PATHINFO_EXTENSION);
	$ruta = $ruta.'/archivo.'.$ext;
	move_uploaded_file($_FILES['file'][ 'tmp_name'], $ruta);
	$ruta2 = 'assets/vacaciones/'.$res[0].'_'.$id.'/archivo.'.$ext;
	mysqli_close($conexion);
	echo $ruta2;
?>
