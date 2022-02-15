<?php
	include("conexion.php");
    $conexion = conexion();
	$hoy = date("d/m/Y");
    $RFC = $_POST['id'];

	$sql = "SELECT *,
	(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre  
	FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $RFC . "'";

	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);

	echo '<div class="formulario_caja">
		<input type="hidden" id="nombre" value="'.$usuario["nombre"].'"/>
		<div class="row">
			<div class="col-md-3">
				<div class="wallpaper">
					<img src="assets/img/form.svg" alt="">
				</div>
			</div>

			<div class="col-md-9">
				<div class="formulario">
					<div class="p-2">
						<h4 class="font-weight-bold text-primary">Registrar reingreso</h4>
						<small class="text-muted">Completa el siguiente formulario para realizar el reingreso de '.$usuario["nombre"].', asignará una plaza nueva.</small>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="row">';
							echo '<div class="col-md-6">
									<div class="select-etiqueta">Fecha de reingreso</div>
									<input id="fecha" type="text" class="campo" readonly value="'.$hoy.'"/> 
								</div>';

							echo '<div class="col-md-6">
									<div class="select">
										<div class="select-etiqueta">Tipo de trabajador nuevo</div>
										<select id="trabajador">';
											$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
											$consulta = mysqli_query($conexion, $sql);
											while($trabajador = mysqli_fetch_array($consulta)){
												if($usuario["id_trabajador"] == $trabajador["id_trabajador"]){
													echo '<option data-description="ASIGNADO ANTERIORMENTE" selected value="'.$trabajador[0].'">'.$trabajador[1].'</option>';
												}else{
													echo '<option value="'.$trabajador[0].'">'.$trabajador[1].'</option>';
												}
											}	
											echo '
										</select>
									</div>
								</div>';

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
						
						echo '</div>
						</div>
					</div>
					<div class="pie">
						<div class="btn btn-secondary btn-sm" onclick="ver(\''.$RFC.'\', 1);">Regresar </div>
						<div class="btn btn-success btn-sm" id="form-reingreso"><i class="material-icons">save</i> Guardar </div>
					</div>
				</div>
			</div>
		</div>
	</div>';

	mysqli_close($conexion);
