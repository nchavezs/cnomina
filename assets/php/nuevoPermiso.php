<?php
	include("conexion.php");
	$conexion = conexion();
	$id = $_POST['id'];
	$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
	$consulta = mysqli_query($conexion, $sql);
	$usuario = mysqli_fetch_array($consulta);
	setlocale(LC_ALL, "spanish");
	$hoy = date("d/m/Y");
	

	// $sql2 = "SELECT COUNT(*) FROM Permiso WHERE RFC = '".$id[0]."'";
	// $consulta2 = mysqli_query($conexion, $sql2);
	// $permiso = mysqli_fetch_array($consulta2);
	// $permiso[0] = $permiso[0]+1;
	
	echo '<form id="form-permiso">
			<div class="">
				<div class="p-2">
					<h4 class="font-weight-bold text-primary">Registrar nueva licencia</h4>
					<small class="text-muted">Nueva licencia para '.$usuario["nombre"].'.</small>
				</div>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="select">
									<div class="select-etiqueta">Tipo de permiso</div>
									<select id="categoria">
										<option value="0">PERMISO CON GOCE DE SUELDO</option>
										<option value="1">PERMISO SIN GOCE DE SUELDO</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="select-etiqueta">Días de permiso</div>
								<input id="dias" value="1" type="text" class="campo" disabled onkeypress="return isNumberKey(event)">
							</div>
							<div class="col-md-6">
								<div class="select-etiqueta">Periodo del</div>
								<input id="fecha2" type="text" class="datepicker-here campo" required="true" readonly value="'.$hoy.'"/> 
							</div>
							<div class="col-md-6">
								<div class="select-etiqueta">Al</div>
								<input id="fecha3" type="text" class="datepicker-here campo" required="true" readonly value="'.$hoy.'"/> 
							</div>
							<div class="col-md-12">
								<div class="select-etiqueta">Descripción <cite class="text-danger"> opcional</cite></div>
								<textarea id="descripcion" class="campo" rows="3"></textarea>
							</div>
						</div>
					</div>
					<div id="advertencia" class="hide"><i class="material-icons">error</i>Los días no coinciden con el período seleccionado</div>
				</div>

				<div class="card">
					<div class="card-body">
						<div class="check_opciones">
							<div class="toggle-btn">
								<input id="materno" type="checkbox" class="cb-value" /> 
								<span class="round-btn"></span>
							</div>
							<span class="text-muted ml-3">Permiso materno</span>	
						</div>
					</div>
				</div>
				<div class="pie">
					<div class="btn btn-secondary btn-sm " onclick="verPermisos(\''.$id.'\');">Regresar </div>
					<button type="submit" class="continuar btn btn-success btn-sm" id="'.$id.'" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);


		//  <div class="col-md-4">
		// 	<div class="form-group">
		// 	<div class="select-etiqueta">Fecha de elaboración</div>
		// 		<input id="fecha1" type="text" class="campo datepicker-here" disabled value="'.$hoy.'"/> 
		// 	</div>
		// </div>