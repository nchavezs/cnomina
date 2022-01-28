<?php
	$id = $_POST['id'];
	$tabla = $_POST['tabla'];
	$usuario = $_POST['usuario'];

	$ruta = './../'.mb_strtolower($tabla).'/'.$id.'_'.$usuario;
	if (!file_exists($ruta))
   	mkdir($ruta, 0777, true);
	$archivo = $_FILES['file']['name'];
	$ext = pathinfo($archivo, PATHINFO_EXTENSION);
	$ruta = $ruta.'/archivo.'.$ext;
	move_uploaded_file($_FILES['file'][ 'tmp_name'], $ruta);

	$ruta = 'assets/'.mb_strtolower($tabla).'/'.$id.'_'.$usuario.'/archivo.'.$ext;

	echo $ruta;
?>