<?php
    $id = explode("-", $_POST['id']);
	include("conexion.php");
	$conexion = conexion();

	$sql1 = "SELECT * FROM Usuario WHERE RFC = '".$id[0]."'";
	$consulta = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta);
	setlocale(LC_ALL, "spanish");
	$hoy = date("d/m/Y");

	
	echo '<form id="form-gastos">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Gastos médicos</h4>
					<p class="card-category">Empleado: '.$resultado1[6].'</p>
				</div>
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de elaboración</div>
							  <input type="text" class="form-control datepicker-here" disabled value="'.$hoy.'"/> 
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de apoyo</div>
							  <input id="fecha" type="text" class="form-control datepicker-here" readonly required="true" value="'.$hoy.'"/> 
							</div>
						</div>
						
						
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Monto $</div>
							  <input id="monto" type="text" class="form-control" required="true" />
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
							  <div class="select-etiqueta">Nombre de quién otorga el apoyo</div>
							  <input id="nombre" type="text" maxlength="100" class="form-control" required="true" />
							</div>
						</div>

						
						<div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Concepto</div>
								 <textarea id="concepto" class="form-control" rows="5" required="true"></textarea>
							</div>
                  </div>
						 
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>El monto debe ser un número mayor a 0</div>
			</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-secondary btn-sm regresar " id="'.$id[0].'" onclick="verGastos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
				<div class="col-6">
					<button type="submit" class="continuar btn btn-secondary btn-sm regresar " id="'.$id[0].'-" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);
?>
