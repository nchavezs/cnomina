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
	
	echo '<form id="form-vacacion">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Vacaciones</h4>
					<p class="card-category">Empleado: '.$resultado1[6].'</p>
					
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Fecha de elaboración</div>

							  <input id="fecha1" type="text" class="form-control datepicker-here" disabled value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Días de vacaciones</div>
							  <input id="dias" type="text" class="form-control" disabled onkeypress="return isNumberKey(event)">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Periodo del</div>

							  <input id="fecha2" type="text" class="datepicker-here form-control" required="true" readonly value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Al</div>

							  <input id="fecha3" type="text" class="datepicker-here form-control" required="true" readonly value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
						  <div class="form-group">
						  <div class="select-label label-puesto">Descripción <cite class="text-danger"> opcional</cite></div>
						  <textarea id="descripcion" class="form-control" rows="5"></textarea>
						  </div>
                  </div>
						 
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Los días no coinciden con el período seleccionado</div>
			</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-primary regresar" id="'.$id[0].'" onclick="verVacaciones(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
				<div class="col-6">
					<button type="submit" class="btn btn-primary regresar" id="'.$id[0].'-" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);
