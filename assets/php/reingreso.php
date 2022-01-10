<?php
    $id = $_POST['id'];
	include("conexion.php");
    $conexion = conexion();

	$sql1 = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
	$consulta1 = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta1);
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$hoy = date("d/m/Y");
	
	echo '<form id="form-reingreso">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Reingreso de empleado</h4>
					<p class="card-category">Empleado: <span id="nombre">'.$resultado1[6].'</span></p>
					
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de reingreso</div>
							  <input id="fecha1" type="text" class="form-control datepicker-here" readonly value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Observaciones <cite class="text-danger"> opcional</cite></div>
								<textarea id="observaciones" class="form-control" rows="5"></textarea>
							</div>
						</div>
				
					</div>
				</div>
			</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-primary regresar" id="'.$id.'" onclick="ver(this.id,1);"><i class="material-icons">arrow_back</i> Regresar </div>
					</div>
				<div class="col-6">
					<button type="submit" class="btn btn-primary regresar" ><i class="material-icons">thumb_up_alt</i> Continuar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);
