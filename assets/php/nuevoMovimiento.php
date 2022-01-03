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
	
		
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">Empleado: '.$resultado1[6].'</p>
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Fecha de movimiento</div>
							  <input id="fecha1" type="text" class="form-control datepicker-here" readonly value="'.$hoy.'"/> 
							</div>
						</div>

						<div class="col-md-6">
							
							  <div class="select">
							  	<div class="select-label label-puesto">Tipo de trabajador</div>
								<select id="trabajador" class="custom-select select-empleado trabajador-select">';
									$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
									$consulta = mysqli_query($conexion, $sql);
									while($res2 = mysqli_fetch_row($consulta)){
										echo '<option value="'.$res2[1].'">'.$res2[1].'</option>';
									}	
									echo '
								</select>
							  </div>	
							
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Puesto actual</div>
								<input type="text" class="form-control" disabled value="'.$resultado1[11].'"/> 
							</div>
						</div>';

						echo '<div class="col-md-6">
						<div class="select">
							<div class="select-label label-puesto">Nuevo puesto</div>
								<select id="puesto" class="custom-select select-empleado puesto-select">
								</select>
							</div>
						</div>';

						echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label label-puesto">Departamento actual</div>
									<input type="text" class="form-control" disabled value="'.$resultado1[12].'"/> 
								</div>
							</div>';
						
						echo '<div class="col-md-6">
							<div class="select">
							<div class="select-label label-puesto">Nuevo departamento</div>
									<select id="departamento" class="custom-select select-empleado departamento-select">';

						$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
						$consulta = mysqli_query($conexion, $sql);
						if($consulta && (mysqli_num_rows($consulta)) > 0){
							while($res2 = mysqli_fetch_row($consulta)){
								echo '<option value="'.$res2[1].'">'.$res2[1].'</option>';
							}		
						}else{
						echo '<option selected="true" value="">NO SE ENCONTRO DEPARTAMENTO</option>';
						}

						echo '</select></div>
						</div>';


						echo '<div class="col-md-12">
						  <div class="form-group">
						  <div class="select-label label-puesto">Observacion <cite class="text-danger">opcional</cite></div>
							 <textarea id="observacion" class="form-control" rows="2"></textarea>
						  </div>
                  </div>
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Selecciona un puesto y departamento</div>
			</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-primary regresar" id="'.$id[0].'" onclick="verMovimientos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
					</div>
				<div class="col-6">
					<div class="btn btn-primary regresar" id="form-movimiento" ><i class="material-icons">save</i> Guardar </div>
				</div>
			</div>';

	mysqli_close($conexion);
?>
