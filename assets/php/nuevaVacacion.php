<?php
	include("conexion.php");
    $conexion = conexion();
	setlocale(LC_ALL, "spanish");
	$id = $_POST['id'];
	// $hoy = date("d/m/Y");

	$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);
	
	
	echo '<form id="form-vacacion" autocomplete="off">
			<div class="p-2">
				<h4 class="font-weight-bold text-primary">Registrar vacaciones</h4>
				<small class="text-muted">Completa el siguiente formulario para registrar vacaciones de '.$usuario["nombre"].'.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-4">
							<div class="select-etiqueta">Periodo del</div>
							<input id="fecha1" type="text" class="campo" onkeypress="return false;" required/> 
						</div>
						
						<div class="col-md-4">
							<div class="select-etiqueta">Al</div>
							<input id="fecha2" type="text" class="campo" onkeypress="return false;" required/> 
						</div>

						<div class="col-md-4">
							<div class="select-etiqueta">Días de vacaciones</div>
							<input id="dias" value="1" type="text" class="campo" disabled onkeypress="return isNumberKey(event)">
						</div>
						
						<div class="col-md-12">
							<div class="select-etiqueta">Descripción <cite class="text-danger"> opcional</cite></div>
							<textarea id="descripcion" class="campo" rows="5"></textarea>
                  		</div>
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Los días no coinciden con el período seleccionado</div>
			</div>';

	echo '<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verVacaciones(\''.$id.'\');">Regresar </div>
			<button type="submit" class="btn btn-success btn-sm" ><i class="material-icons">save</i> Guardar </button>
			</div>
		</form>';

	mysqli_close($conexion);
