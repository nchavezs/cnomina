<?php
	include("conexion.php");
    $conexion = conexion();
	$RFC = $_POST['id'];

	setlocale(LC_ALL, "spanish");
	$hoy = date("d/m/Y");

	$sql = "SELECT
	Empleado.RFC AS RFC,
	Usuario.nombre,
	Empleado.id_trabajador,
	(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
	(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
	(SELECT nombre FROM Trabajador WHERE Trabajador.id_trabajador = Empleado.id_trabajador) AS tipoTrabajador 
	FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $RFC . "'";
	$consulta = mysqli_query($conexion, $sql);
	$usuario = mysqli_fetch_array($consulta);

	// $sql = "SELECT * FROM Plaza WHERE RFC = '".$RFC."'";
	// $consulta = mysqli_query($conexion, $sql);
	
	// $plaza = "";
	// if($consulta && mysqli_num_rows($consulta) > 0){
	// 	$plaza = mysqli_fetch_array($consulta);
	// 	$plaza = "PLAZA #".$plaza[0];
	// }

	echo '<div class="">
			<div class="p-2">
				<h4 class="font-weight-bold text-primary">Registrar nuevo movimiento</h4>
				<small class="text-muted">Completa el siguiente formulario para realizar el cambio de puesto y departamento para '.$usuario["nombre"].'.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">';
					echo '<div class="col-md-6">
							<div class="select-etiqueta">Fecha de movimiento</div>
							<input id="fecha" type="text" class="campo" readonly value="'.$hoy.'"/> 
						</div>';
					// echo '<div class="col-md-6"></div>';
					

					// echo '<div class="col-md-6">
					// 		<div class="select-etiqueta">Tipo de trabajador actual</div>
					// 		<input type="text" class="campo" disabled value="'.$usuario["tipoTrabajador"].'"/> 
					// 	</div>';

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Tipo de trabajador nuevo</div>
								<select id="trabajador">';
									$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
									$consulta = mysqli_query($conexion, $sql);
									while($trabajador = mysqli_fetch_array($consulta)){
										if($usuario["id_trabajador"] == $trabajador["id_trabajador"]){
											echo '<option data-description="ASIGNADO ACTUALMENTE" selected value="'.$trabajador[0].'">'.$trabajador[1].'</option>';
										}else{
											echo '<option value="'.$trabajador[0].'">'.$trabajador[1].'</option>';
										}
									}	
									echo '
								</select>
							</div>
						</div>';

					// echo '<div class="col-md-6">
					// 			<div class="select-etiqueta">Departamento actual</div>
					// 			<input type="text" class="campo" disabled value="'.$usuario["departamento"].'"/> 
					// 		</div>';

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Nuevo departamento</div>
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

								echo '</select>
								</div>
							</div>';

					// echo '<div class="col-md-6">
					// 			<div class="select-etiqueta">Puesto actual</div>
					// 			<input type="text" class="campo" disabled value="'.$usuario["puesto"].'"/> 
					// 		</div>';

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Nuevo puesto</div>
								<select id="puesto" class=""></select>
							</div>
						</div>';

					// echo '<div class="col-md-6">
					// 		<div class="select-etiqueta">Plaza actual</div>
					// 		<input type="text" class="campo" disabled value="'.$plaza.'"/> 
					// 	</div>';

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Nueva Plaza</div>
								<select id="plaza" class=""></select>
							</div>
						</div>';

					echo '<div class="col-md-12">
								<div class="select-etiqueta">Observacion <cite class="text-danger">opcional</cite></div>
								<textarea id="observacion" class="campo" maxlength="300" rows="2"></textarea>
						</div>';
				
				echo '
				</div>
			</div>
		</div>
		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verMovimientos(\''.$RFC.'\');">Regresar </div>
			<div class="btn btn-success btn-sm" id="form-movimiento"><i class="material-icons">save</i> Guardar </div>
		</div>';

	mysqli_close($conexion);