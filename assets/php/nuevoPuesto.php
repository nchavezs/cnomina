<?php
include "conexion.php";
$conexion = conexion();

echo '<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Nuevo puesto</h4>
				<small class="text-muted">Completa el siguiente formulario para agregar un nuevo puesto.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre</div>
					<input id="nombre" required type="text" class="campo">
					<div class="select">
						<div class="select-etiqueta">Departamento</div>
						<select id="departamento">';
						$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($departamento = mysqli_fetch_array($consulta)) {
                            echo '<option value="' . $departamento["id_departamento"] . '">' . $departamento["nombre"] . '</option>';
						}

						echo '</select>
					</div>
						
					<div class="select">
						<div class="select-etiqueta">Categoria</div>
						<select id="trabajador">';
						$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($trabajador = mysqli_fetch_array($consulta)) {
                            echo '<option value="' . $trabajador["id_trabajador"] . '">' . $trabajador["nombre"] . '</option>';
						}

						echo '</select>
					</div>
				</div>
			</div>
			<div class="pie">
				<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Guardar</button>
			</div>
		</form>
	</div>
</div>';
