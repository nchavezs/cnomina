<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];


$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Descuento WHERE id_prenomina=".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
			<i class="material-icons btn2">sms_failed</i>
			<h2>Nada registrado</h2>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">add</i>
				<p onclick="descuento(\''.$id.'\');">Nuevo descuento</p>
			</div>
		</div>';
} else {
    echo '<div class="p-2">
		<h4 class="negrita text-primary">Lista de descuentos</h4>
		<small class="text-muted">Descuentos de '.$usuario["nombre"].'.</small>
	</div>
	<div class="ver_opciones">
			<div class="btn btn-secondary btn-sm"  onclick="descuento(\''.$id.'\')"><i class="material-icons">add</i> Nuevo descuento</div>
		</div>

		<div class="card ">
			<div class="card-body">
				<div class="caja-descuentos"></div>
			</div>
		</div>';
}

$conexion->close();
