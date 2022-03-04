<?php
    $id = $_POST['id'];
	include("conexion.php");
    $conexion = conexion();
	setlocale(LC_ALL, "spanish");
	// $hoy = date("d/m/Y");
	$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);

	
	echo '<form id="form-descuento">
				<div class="p-2">
					<h4 class="negrita text-primary">Registrar descuentos</h4>
					<small class="text-muted">Nuevo permiso para '.$usuario["nombre"].'.</small>
				</div>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 fecha-date">
								<div id="fecha" class="datepicker-here"></div>
								<input id="fechas" type="hidden" />
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="select-etiqueta">Días de descuento</div>
										<input id="dias" value="0" type="text" class="campo" readonly />
									</div>

									<div class="col-md-12">
										<div class="select-etiqueta">Motivo</div>
										<textarea id="motivo" required class="campo" rows="5"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="advertencia" class="hide"><i class="material-icons">error</i>Selecciona la fecha de descuento</div>
				</div>';

	echo '<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verDescuentos(\''.$id.'\');"><i class="material-icons">arrow_back</i> Regresar </div>

			<button type="submit" class="btn btn-success btn-sm"  ><i class="material-icons">save</i> Guardar </button>
		</div>
	</form>';

	$conexion->close();
?>
