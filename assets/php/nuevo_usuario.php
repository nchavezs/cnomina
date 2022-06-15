<?php
include "conexion.php";
$conexion = conexion();

session_start();
include "rol.php";

$bloqueo = "bloqueo();";
if (in_array(17, rol())) {
    $bloqueo = "nuevo_rol();";
}

echo '
<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Nuevo usuario</h4>
				<small class="text-muted">Registra un nuevo usuario, asigna un rol con permisos.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre</div>
					<input id="nombre" required type="text" class="campo">

					<div class="select-etiqueta">Alias</div>
					<input id="alias" required type="text" class="campo">

					<div class="select-etiqueta">Email</div>
					<input id="email" required type="email" class="campo">

					<div class="select">
						<div class="select-etiqueta">Rol</div>
						<select id="rol">';
						$sql = "SELECT * FROM Rol ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($rol = mysqli_fetch_array($consulta)) {
							echo '<option value="' . $rol[0] . '">' . $rol["nombre"] . '</option>';
						}

						echo '</select>
					</div>
					<p class="text-muted my-2">Puedes crear tu propio rol desde <i class="text-warning nuevo_rol" onclick="' . $bloqueo . '"> aquí</i> .</p>
				</div>
			</div>
			<div class="pie">
				<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Registrar usuario</button>
			</div>
		</form>
	</div>
</div>
';
