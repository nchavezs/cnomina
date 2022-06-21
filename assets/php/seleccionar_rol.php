<?php
include "conexion.php";
$conexion = conexion();
$tipo = $_POST["tipo"];

$sql = "SELECT * FROM Rol";
$consulta = $conexion->query($sql);

echo '
<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">'.$tipo.' rol</h4>
				<small class="text-muted">¿Qué rol deseas '.mb_strtolower($tipo).'?</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select">
						<div class="select-etiqueta">Rol de usuario</div>
						<select id="rol">';
						$sql = "SELECT * FROM Rol WHERE id_rol <> 1 ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($rol = mysqli_fetch_array($consulta)) {
							echo '<option value="' . $rol[0] . '">' . $rol["nombre"] . '</option>';
						}

						echo '</select>
					</div>
				</div>
			</div>
			<div class="pie">
				<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">done_all</i> Continuar</button>
			</div>
		</form>
	</div>
</div>
';


$conexion->close();