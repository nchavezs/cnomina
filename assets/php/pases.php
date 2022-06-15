<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
include "rol.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Pase WHERE id_prenomina =".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);


// ----------------------------------------------------
$bloqueo = 'bloqueo()';

if(in_array(11 , rol())){
	$bloqueo = 'pase(\''.$usuario["RFC"].'\')';
}
// ----------------------------------------------------


if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
			<i class="material-icons btn2">sms_failed</i>
			<h2>Nada registrado</h2>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">add</i>
				<p onclick="'.$bloqueo.'">Nuevo pase</p>
			</div>
		</div>';
} else {
	echo '<div class="p-2">
			<h4 class="negrita text-primary">Lista de pases</h4>
			<small class="text-muted">Pases de entrada y salida de '.$usuario["nombre"].'.</small>
		</div>
		<div class="ver_opciones">
			<select id="categoria" class="sources">
				<option value="0" selected>Entrada</option>
				<option value="1">Salida</option>
			</select>
			<div class="btn btn-secondary btn-sm" onclick="'.$bloqueo.'"><i class="material-icons">add</i> Nuevo pase</div>
		</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="caja-pases"></div>
			</div>
		</div>';
}

$conexion->close();