<?php
    $id = $_POST['id'];
	include("../../conexion.php");
    $conexion = conexion();

	$sql1 = "SELECT * FROM Movimiento WHERE id_movimiento = ".$id;
	$consulta1 = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta1);


	if(trim($resultado1[8]) === "")
		$obs = "Sin observación";
	else
		$obs = $resultado1[8];

	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$fecha = date("d/m/Y", strtotime($resultado1[2]));
			
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">'.strftime("%A, %d de %B de %G", strtotime($resultado1[2])).'</p>
				</div>
				<div class="card-body">
					<div class="row formulario3">
						
						<div class="col-md-12">
							<div class="form-group">
							  <label class="bmd-label-floating">Fecha de movimiento</label>
							  <input id="fecha1" type="text" class="form-control datepicker-here" disabled value="'.$fecha.'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Puesto anterior</label>
								<input type="text" class="form-control" disabled value="'.$resultado1[6].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Puesto actual</label>
								<input type="text" class="form-control" disabled value="'.$resultado1[3].'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Departamento anterior</label>
								<input type="text" class="form-control" disabled value="'.$resultado1[7].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Departamento actual</label>
								<input type="text" class="form-control" disabled value="'.$resultado1[4].'"/> 
							</div>
						</div>
						<div class="col-md-12">
						  <div class="form-group">
							 <label class="bmd-label-floating">Observación </label>
							 <textarea id="observacion" disabled class="form-control" rows="3">'.$obs.'</textarea>
						  </div>
                  </div>
						
					</div>
				</div>
			</div>';

	mysqli_close($conexion);
?>
