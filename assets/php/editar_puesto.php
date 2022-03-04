<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

$sql = "SELECT * FROM Puesto WHERE id_puesto = " . $id;
$consulta = $conexion->query($sql);
$puesto = mysqli_fetch_array($consulta);

echo '<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Editar puesto</h4>
				<small class="text-muted">Completa el siguiente formulario para actualizar la información del puesto.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre</div>
					<input id="nombre" required type="text" value="' . $puesto["nombre"] . '" class="campo">
					<div class="select">
						<div class="select-etiqueta">Departamento</div>
						<select id="departamento">';
						$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($departamento = mysqli_fetch_array($consulta)) {
							if($puesto["id_departamento"] == $departamento["id_departamento"]){
								echo '<option data-description="ASIGNADO ACTUALMENTE" selected value="' . $departamento["id_departamento"] . '">' . $departamento["nombre"] . '</option>';
							}
							else{
								echo '<option value="' . $departamento["id_departamento"] . '">' . $departamento["nombre"] . '</option>';
							}
						}

						echo '</select>
					</div>
						
					<div class="select">
						<div class="select-etiqueta">Categoria</div>
						<select id="trabajador">';
						$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($trabajador = mysqli_fetch_array($consulta)) {
							if($puesto["id_trabajador"] == $trabajador["id_trabajador"]){
								echo '<option data-description="ASIGNADO ACTUALMENTE" selected value="' .$trabajador["id_trabajador"] . '">' .$trabajador["nombre"] . '</option>';
							}
							else{
								echo '<option value="' . $trabajador["id_trabajador"] . '">' . $trabajador["nombre"] . '</option>';
							}
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
