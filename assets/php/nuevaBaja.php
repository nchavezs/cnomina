<?php
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
// $hoy = date("d/m/Y");

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

echo '<form id="form-baja" autocomplete="off">
			<div class="p-2">
				<h4 class="font-weight-bold text-primary">Registrar baja de empleado</h4>
				<small class="text-muted">Completa el siguiente formulario para dar de baja a <span id="nombre">' . $usuario["nombre"] . '</span> .</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">

						<div class="col-md-12">
							<div class="select-etiqueta">Fecha de baja</div>
							<input id="fecha" type="text" class="campo" onkeypress="return false;" required/>
						</div>

						<div class="col-md-12">
							<div class="select-etiqueta">Motivo de baja</div>
							<textarea id="razon" required class="campo" rows="5"></textarea>
						</div>

					</div>
				</div>
			</div>
			
			<div class="card">
				<div class="card-body">
					<div class="check_opciones">
						<div class="toggle-btn">
							<input id="retroactivo" type="checkbox" class="cb-value" /> 
							<span class="round-btn"></span>
						</div>
						<span class="text-muted ml-3">¿El empleado fue dado de baja en el periodo anterior? Se establecerán 0 días a pagar para este periodo.</span>	

					</div>
				</div>
			</div>';

echo '<div class="pie">
		<div class="btn btn-secondary btn-sm" onclick="verHistorial(\'' . $id . '\',1);">Regresar </div>
		<button type="submit" class="btn btn-danger btn-sm" ><i class="material-icons">thumb_down_alt</i> Dar de baja </button>
	</div>	
</form>';

mysqli_close($conexion);
