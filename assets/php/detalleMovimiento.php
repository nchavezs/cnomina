<?php
    $id = explode("-", $_POST['id']);
	include("conexion.php");
    $conexion = conexion();

	$sql1 = "SELECT * FROM Movimiento WHERE id_movimiento = ".$id[0];
	$consulta1 = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta1);


	if(trim($resultado1[8]) === "")
		$obs = "Sin observación";
	else
		$obs = $resultado1["observacion"];


	$sql2 = "SELECT nombre FROM Usuario WHERE RFC = '".$resultado1[1]."'";
	$consulta2 = mysqli_query($conexion, $sql2);
	$nombre = mysqli_fetch_array($consulta2);
	$fecha = date("d/m/Y", strtotime($resultado1[2]));
			
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">Empleado: '.$nombre[0].'</p>
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de movimeinto</div>
							  <input id="fecha1" type="text" class="form-control datepicker-here" readonly value="'.$fecha.'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Puesto anterior</div>
								<input type="text" class="form-control" readonly value="'.$resultado1[6].'"/> 
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Puesto actual</div>
								<input type="text" class="form-control" readonly value="'.$resultado1[3].'"/> 
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Departamento anterior</div>
								<input type="text" class="form-control" readonly value="'.$resultado1[7].'"/> 
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Departamento actual</div>
								<input type="text" class="form-control" readonly value="'.$resultado1[4].'"/> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Observaciones</div>
								<textarea id="observacion" readonly class="form-control" rows="3">'.$obs.'</textarea>
							</div>
						  </div>';
						  if(!is_null($resultado1[5])){
							echo '<div class="col-md-6">
									<div class="btn3" onclick="archivo('.$resultado1[0].',\''.$resultado1[5].'\',\''.$resultado1[1].'\',\'Movimiento\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>
								</div>
								<div class="col-md-6">
								<div class="btn3" onclick="eliminar_archivo('.$resultado1[0].',\'Movimiento\')"><i class="material-icons">clear</i> Eliminar archivo </div>
								</div>';
						}
						else{
							echo '<div class="col-md-12">
							<div class="btn3" onclick="archivo('.$resultado1[0].',\''.$resultado1[5].'\',\''.$resultado1[1].'\',\'Movimiento\',1)"><i class="material-icons">cloud_upload</i> Ver archivo </div>
						</div>';
						}	  
						
					echo '</div>
				</div>
			</div>';

	echo '<div class="row">
				<div class="col-12">
					<div class="btn btn-secondary btn-sm regresar " id="'.$resultado1[1].'" onclick="verMovimientos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
			</div>';

	mysqli_close($conexion);
?>
