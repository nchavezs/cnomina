<?php
    $id = explode("-", $_POST['id']);
	include("conexion.php");
    $conexion = conexion();

	$sql1 = "SELECT * FROM Usuario WHERE RFC = '".$id[0]."'";
	$consulta1 = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta1);
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$hoy = date("d/m/Y");
	
	echo '<form id="form-descuento">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Descuentos</h4>
						<p class="card-category">Empleado: '.$resultado1[6].'</p>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6 fecha-date">
								<div id="fecha" class="datepicker-here"></div>
								<input id="fecha1" type="hidden" />
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
										<div class="select-etiqueta">Días de descuento</div>

											<input id="dias" type="text" class="form-control" readonly />
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
										<div class="select-etiqueta">Motivo</div>

											<textarea id="motivo" required class="form-control" rows="5"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="advertencia" class="hide"><i class="material-icons">error</i>Selecciona la fecha de descuento</div>
				</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-primary regresar" id="'.$id[0].'" onclick="verDescuentos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
					</div>
				<div class="col-6">
					<button type="submit" class="btn btn-primary regresar" id="'.$id[0].'-" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);
?>
