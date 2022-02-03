<?php
include("conexion.php");
$conexion = conexion();

$id = $_POST["id"];

$sql = "SELECT nombre FROM Departamento WHERE id_departamento = ".$id;
$consulta = $conexion->query($sql);
$departamento = mysqli_fetch_array($consulta);

echo '<div class="formulario_caja">
<div class="formulario">
	<form id="form">
		<div class="text-left p-2">
			<h4 class="font-weight-bold text-primary">Editar departamento</h4>
			<small class="text-muted">Completa el siguiente formulario para actualiza el nombre del departamento.</small>
		</div>
		<div class="card">	
			<div class="card-body">
				<div class="select-etiqueta">Nombre</div>
				<input id="nombre" required type="text" value="'.$departamento["nombre"].'" class="campo">
			</div>
		</div>
		<div class="pie">
			<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
			<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Guardar</button>
		</div>
	</form>
</div>
</div>';