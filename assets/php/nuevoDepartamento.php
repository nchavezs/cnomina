<?php
include "conexion.php";
$conexion = conexion();

echo '<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Nuevo departamento</h4>
				<small class="text-muted">Completa el siguiente formulario para agregar un nuevo departamento.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre</div>
					<input id="nombre" required type="text" class="campo">
				</div>
			</div>
			<div class="pie">
				<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Guardar</button>
			</div>
		</form>
	</div>
</div>';
