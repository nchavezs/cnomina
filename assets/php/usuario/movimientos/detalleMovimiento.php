<?php
    $id = $_POST['id'];
	include("../../conexion.php");
    $conexion = conexion();

	$sql1 = "SELECT * FROM Movimiento WHERE id_movimiento = ".$id;
	$consulta1 = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta1);


	if(trim($resultado1["observacion"]) === "")
		$obs = "Sin observación";
	else
		$obs = $resultado1["observacion"];

	setlocale(LC_ALL, "spanish");
	$fecha = date("d/m/Y", strtotime($resultado1["fecha"]));
			
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">'.strftime("%A, %d de %B de %G", strtotime($resultado1["fecha"])).'</p>
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="form-group">
							  <label class="bmd-label-floating">Fecha de movimiento</label>
							  <input id="fecha1" type="text" class="form-control datepicker-here" disabled value="'.$fecha.'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Puesto anterior</label>
								<input type="text" class="form-control" disabled value="'.$resultado1["puestoAnterior"].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Puesto actual</label>
								<input type="text" class="form-control" disabled value="'.$resultado1["puesto"].'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Departamento anterior</label>
								<input type="text" class="form-control" disabled value="'.$resultado1["departamentoAnterior"].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  	<label class="bmd-label-floating">Departamento actual</label>
								<input type="text" class="form-control" disabled value="'.$resultado1["departamento"].'"/> 
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
