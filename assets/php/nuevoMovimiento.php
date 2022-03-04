<?php
	setlocale(LC_ALL, "spanish");
	include("conexion.php");
    $conexion = conexion();
	$RFC = $_POST['id'];

	$sql = "SELECT
	Empleado.RFC AS RFC,
	Usuario.nombre,
	(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
	(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento 
	FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $RFC . "'";
	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);

	echo '<form id="form-movimiento" autocomplete="off">
		<div>
			<div class="p-2">
				<h4 class="negrita text-primary">Registrar nuevo movimiento</h4>
				<small class="text-muted">Completa el siguiente formulario para realizar el cambio de puesto y departamento para '.$usuario["nombre"].'.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">';
					echo '<div class="col-md-6">
							<div class="select-etiqueta">Fecha de movimiento</div>
							<input id="fecha" type="text" class="campo" onkeypress="return false;" required/> 
						</div>';
		

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Nuevo departamento</div>
								<select id="departamento" class="custom-select select-empleado departamento-select">';

								$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
								$consulta = $conexion->query($sql);
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

				

					echo '<div class="col-md-6">
							<div class="select">
								<div class="select-etiqueta">Nuevo puesto</div>
								<select id="puesto" class=""></select>
							</div>
						</div>';

				

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
			<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
		</div>
	</form>';

	$conexion->close();