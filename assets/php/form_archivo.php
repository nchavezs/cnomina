<?php
session_start();
include "conexion.php";
include "rol.php";
$conexion = conexion();

$estado = 0;
$html = "No cuenta con los permisos suficientes.";

if(in_array(11 , rol())){
    $estado = 1;
	$html = '
	<p>No se encontró ningún archivo, seleccione un archivo para continuar.</p>
	<div class="text-center">
		<input type="file" accept=".pdf, .xlsx" id="file">
		<label for="file" class="btn-3">
			<span><i class="material-icons">cloud_upload</i>Subir archivo</span>
		</label>
	</div>';
}



$datos["estado"] = $estado;
$datos["html"] = $html;
echo json_encode($datos);