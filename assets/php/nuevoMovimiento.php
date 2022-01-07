<?php
	include("conexion.php");
    $conexion = conexion();
    $id = explode("-", $_POST['id']);
	$RFC = $id[0];

	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$hoy = date("d/m/Y");

	$sql = "SELECT * FROM Usuario WHERE RFC = '".$RFC."'";
	$consulta = mysqli_query($conexion, $sql);
	$usuario = mysqli_fetch_array($consulta);
		
	echo '<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Movimiento</h4>
					<p class="card-category">Empleado: '.$usuario["nombre"].'</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="select-label">Tipo de trabajador actual</div>
								<input type="text" class="form-control" disabled value="'.$usuario["tipoTrabajador"].'"/> 
							</div>
						</div>

						<div class="col-md-6">
							<div class="select">
								<div class="select-label">Tipo de trabajador</div>
								<select id="trabajador" class="custom-select select-empleado trabajador-select">';
									$sql = "SELECT nombre FROM Trabajador ORDER BY nombre ASC";
									$consulta = mysqli_query($conexion, $sql);
									while($trabajador = mysqli_fetch_row($consulta)){
										echo '<option value="'.$trabajador[0].'">'.$trabajador[0].'</option>';
									}	
									echo '</select>
							</div>
						</div>';

						echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">Departamento actual</div>
									<input type="text" class="form-control" disabled value="'.$usuario[12].'"/> 
								</div>
							</div>';

							echo '<div class="col-md-6">
							<div class="select">
							<div class="select-label">Nuevo departamento</div>
									<select id="departamento" class="custom-select select-empleado departamento-select">';

						$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
						$consulta = mysqli_query($conexion, $sql);
						if($consulta && (mysqli_num_rows($consulta)) > 0){
							while($departamento = mysqli_fetch_row($consulta)){
								echo '<option value="'.$departamento[0].'">'.$departamento[1].'</option>';
							}		
						}else{
							echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
						}

						echo '</select></div>
						</div>';

						echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">Puesto actual</div>
								<input type="text" class="form-control" disabled value="'.$usuario[11].'"/> 
							</div>
						</div>';

						echo '<div class="col-md-6">
								<div class="select">
									<div class="select-label">Nuevo puesto</div>
										<select id="puesto" class="">
										</select>
									</div>
								</div>';

						echo '<div class="col-md-6">
								<div class="form-group">
								<div class="select-label">Fecha de movimiento</div>
								<input id="fecha1" type="text" class="form-control datepicker-here" readonly value="'.$hoy.'"/> 
								</div>
							</div>';

						echo '<div class="col-md-6">
						<div class="select">
							<div class="select-label">Plaza</div>
							<select id="plaza" class="">
							</select>
						</div>
					</div>';

						echo '<div class="col-md-12">
						  	<div class="form-group">
								<div class="select-label">Observacion <cite class="text-danger">opcional</cite></div>
									<textarea id="observacion" class="form-control" rows="2"></textarea>
								</div>
                  			</div>
						</div>
				</div>
				
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Completa los campos necesarios</div>
			</div>';

	echo '<div class="row">
			<div class="col-6">
				<div class="btn btn-primary regresar" id="'.$RFC.'" onclick="verMovimientos(this.id);"><i
						class="material-icons">arrow_back</i> Regresar </div>
			</div>
			<div class="col-6">
				<div class="btn btn-primary regresar" id="form-movimiento"><i class="material-icons">save</i> Guardar </div>
			</div>
		</div>';

	mysqli_close($conexion);