<?php
    $id = $_POST['id'];
	include("conexion.php");
	$conexion = conexion();

	setlocale(LC_ALL, "spanish");
	// $hoy = date("d/m/Y");

	$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);
	
	echo '<form id="form-gastos" autocomplete="off">
			<div class="p-2">
				<h4 class="negrita text-primary">Registro de gastos médicos</h4>
				<small class="text-muted">Nuevo gasto médico de '.$usuario["nombre"].'.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="select-etiqueta">Fecha de apoyo</div>
							<input id="fecha" type="text" class="campo" onkeypress="return false;" required/> 
						</div>
						
						
						<div class="col-md-6">
							<div class="select-etiqueta">Monto $</div>
							<input onkeypress="validate(event);" id="monto" type="text" class="campo" required="true" />
						</div>

						<div class="col-md-12">
							<div class="select-etiqueta">Nombre de quién otorga el apoyo</div>
							<input id="nombre" type="text" maxlength="100" class="campo" required="true" />
						</div>

						
						<div class="col-md-12">
							<div class="select-etiqueta">Concepto</div>
							<textarea id="concepto" class="campo" rows="5" required="true"></textarea>
                 		 </div>
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>El monto debe ser un número mayor a 0</div>
			</div>';

	echo '<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verGastos(\''.$id.'\');">Regresar </div>
			<button type="submit" class="continuar btn btn-success btn-sm" ><i class="material-icons">save</i> Guardar </button>
		</div>
	</form>';

	$conexion->close();
?>
