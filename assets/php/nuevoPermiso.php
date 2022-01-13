<?php
    $id = explode("-", $_POST['id']);
	include("conexion.php");
	$conexion = conexion();

	$sql1 = "SELECT * FROM Usuario WHERE RFC = '".$id[0]."'";
	$consulta = mysqli_query($conexion, $sql1);
	$resultado1 = mysqli_fetch_array($consulta);
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$hoy = date("d/m/Y");

	// $sql2 = "SELECT COUNT(*) FROM Permiso WHERE RFC = '".$id[0]."'";
	// $consulta2 = mysqli_query($conexion, $sql2);
	// $permiso = mysqli_fetch_array($consulta2);
	// $permiso[0] = $permiso[0]+1;
	
	echo '<form id="form-permiso">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Permiso económico</h4>
					<p class="card-category">Empleado: '.$resultado1[6].'</p>
				</div>
				<div class="card-body">
					<div class="row">
					
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Fecha de elaboración</div>
							  <input id="fecha1" type="text" class="form-control datepicker-here" disabled value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-8">
							<div class="select">
								<div class="select-etiqueta">Tipo de permiso</div>
								<select id="sources" class="custom-select select-empleado sources" >
									<option value="0">PERMISO CON GOCE DE SUELDO</option>
									<option value="1">PERMISO SIN GOCE DE SUELDO</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Días de permiso</div>
							  <input id="dias" type="text" class="form-control" disabled onkeypress="return isNumberKey(event)">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Periodo del</div>
							  <input id="fecha2" type="text" class="datepicker-here form-control" required="true" readonly value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
							<div class="select-etiqueta">Al</div>
							  <input id="fecha3" type="text" class="datepicker-here form-control" required="true" readonly value="'.$hoy.'"/> 
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								 <div class="select-etiqueta">Descripción <cite class="text-danger"> opcional</cite></div>
								 <textarea id="descripcion" class="form-control" rows="5"></textarea>
							</div>
                  		</div>
						  <div class="col-md-12">
						  <div class="p4">
							  <div class="materno">
								  <div class="row">
									  <div class="col-3">
										  <div class="toggle-btn">
											  <input id="materno" type="checkbox" class="cb-value" />
											  <span class="round-btn"></span>
										  </div>
									  </div>
									  <div class="col-9">
										  <h5>Permiso materno</h5>
									  </div>
								  </div>
							  </div>
						  </div>
					  </div>
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Los días no coinciden con el período seleccionado</div>
			</div>';

	echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-secondary btn-sm regresar " id="'.$id[0].'" onclick="verPermisos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
				<div class="col-6">
					<button type="submit" class="continuar btn btn-secondary btn-sm regresar " id="'.$id[0].'-" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';

	mysqli_close($conexion);
