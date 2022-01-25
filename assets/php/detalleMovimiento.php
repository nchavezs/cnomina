<?php
	include("conexion.php");
    $conexion = conexion();
    $id = $_POST['id'];

	$sql = "SELECT * FROM Movimiento WHERE id_movimiento = ".$id;
	$consulta = mysqli_query($conexion, $sql);
	$movimiento = mysqli_fetch_array($consulta);

	if(trim($movimiento["observacion"]) === "")
		$obs = "Sin observación";
	else
		$obs = $movimiento["observacion"];


	$sql = "SELECT nombre FROM Usuario WHERE RFC = '".$movimiento["RFC"]."'";
	$consulta = mysqli_query($conexion, $sql);
	$usuario = mysqli_fetch_array($consulta);
	$fecha = date("d/m/Y", strtotime($movimiento["fecha"]));
			
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">Empleado: '.$usuario["nombre"].'</p>
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de movimiento</div>
							  <input id="fecha1" type="text" class="form-control datepicker-here" readonly value="'.$fecha.'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Puesto anterior</div>
								<input type="text" class="form-control" readonly value="'.$movimiento["puestoAnterior"].'"/> 
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Puesto actual</div>
								<input type="text" class="form-control" readonly value="'.$movimiento["puesto"].'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Departamento anterior</div>
								<input type="text" class="form-control" readonly value="'.$movimiento["departamentoAnterior"].'"/> 
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Departamento actual</div>
								<input type="text" class="form-control" readonly value="'.$movimiento["departamento"].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Observaciones</div>
								<textarea id="observacion" readonly class="form-control" rows="3">'.$obs.'</textarea>
							</div>
						  </div>';
						  if(!is_null($movimiento["url"])){
							echo '<div class="col-md-6">
									<div class="btn3" onclick="archivo('.$movimiento[0].',\''.$movimiento[5].'\',\''.$movimiento[1].'\',\'Movimiento\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>
								</div>
								<div class="col-md-6">
								<div class="btn3" onclick="eliminar_archivo('.$movimiento[0].',\'Movimiento\')"><i class="material-icons">clear</i> Eliminar archivo </div>
								</div>';
						}
						else{
							echo '<div class="col-md-12">
							<div class="btn3" onclick="archivo('.$movimiento[0].',\''.$movimiento[5].'\',\''.$movimiento[1].'\',\'Movimiento\',1)"><i class="material-icons">cloud_upload</i> Ver archivo </div>
						</div>';
						}	  
						
					echo '</div>
				</div>
			</div>';

	echo '<div class="row">
				<div class="col-12">
					<div class="btn btn-secondary btn-sm" onclick="verMovimientos(\''.$movimiento["RFC"].'\');"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
			</div>';

	mysqli_close($conexion);
?>
