<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
include "rol.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Permiso WHERE id_prenomina=".$id_prenomina." AND RFC = '" . $id."'";
$resultado = $conexion->query($sql);

// ----------------------------------------------------
$bloqueo = 'bloqueo()';

if(in_array(11 , rol())){
	$bloqueo = 'permiso(\''.$id.'\')';
}
// ----------------------------------------------------


if (mysqli_num_rows($resultado) == 0) {
    echo '<div class="vacia">
			<i class="material-icons btn2">sms_failed</i>
			<h2>Nada registrado</h2>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">add</i>
				<p onclick="'.$bloqueo.'">Nueva licencia</p>
			</div>
		</div>';
} else {
    echo '<div class="p-2">
			<h4 class="negrita text-primary">Lista de licencias</h4>
			<small class="text-muted">Permisos con goce de sueldo, sin goce de sueldo de '.$usuario["nombre"].'.</small>
		</div>
		<div class="ver_opciones">
			<select name="sources" id="permiso" class="select-permiso custom-select sources" >
				<option value="0" selected="true">CON GOCE DE SUELDO</option>
				<option value="1">SIN GOCE DE SUELDO</option>
			</select>
			<div class="btn btn-secondary btn-sm" onclick="'.$bloqueo.'"><i class="material-icons">add</i> Nueva licencia </div>
	</div>
	
	<div id="caja-permiso"></div>';
}

$conexion->close();
