<?php
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("conexion.php");
    $conexion = conexion();
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$sql1 = "SELECT nombre FROM Usuario WHERE RFC = (SELECT RFC FROM Vacacion WHERE id_vacacion = ".$id.") LIMIT 1";
	$consulta1 = mysqli_query($conexion, $sql1);
	$usuario = mysqli_fetch_array($consulta1);

	$sql2 = "SELECT * FROM Vacacion WHERE id_vacacion = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$vacacion = mysqli_fetch_array($consulta2);

	if(trim($vacacion[6]) == "")
		$desc = "Sin descripción";
	else
		$desc = $vacacion[6];
	
	$datos["html"] = '<div class="card">
								<div class="card-header card-header-primary">
									<h4 class="card-title ">Vacaciones</h4>
									<p class="card-category">Empleado: '.$usuario[0].'</p>
								</div>
								<div class="card-body">
									<div class="row fecha-caja">
										<div class="col-md-6 fecha-date">
											<div id="fecha" class="datepicker-here"></div>
										</div>
										<div class="col-md-6">
											<div id="fecha-contenido">
												<p class="card-category">Fecha de elaboración: '.date("d/m/Y",strtotime($vacacion[2])).'</p>
												<p class="card-category">Días de vacaciones: '.$vacacion[3].'</p>
												<br>
												<p class="card-category">Del '.strftime("%A, %d de %B de %G", strtotime($vacacion[4])).'</p>
												<p class="card-category">Al '.strftime("%A, %d de %B de %G", strtotime($vacacion[5])).'</p>
												<br>
												<p>Descripción:</p>
												<h5>'.$desc.'</h5>
												<div class="btn3" onclick="archivo('.$vacacion[0].',\''.$vacacion[7].'\',\''.$vacacion[1].'\',\'Vacacion\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
												if(!is_null($vacacion[7]))
													$datos["html"] = $datos["html"] . '<div class="btn3" onclick="eliminar_archivo('.$vacacion[0].',\'Vacacion\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		$datos["html"] = $datos["html"] . '</div>
										</div>
									</div>
								</div>
							 </div>

							 <div class="row">
								<div class="col-12">
									<div class="btn btn-secondary btn-sm regresar " id="'.$vacacion[1].'" onclick="verVacaciones(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
								</div>
							 </div>';

	$datos["fecha1"] = date("d/m/Y",strtotime($vacacion[2]));
	$datos["fecha2"] = date("d/m/Y",strtotime($vacacion[4]));
	$datos["fecha3"] = date("d/m/Y",strtotime($vacacion[5]));

    echo json_encode($datos);
    mysqli_close($conexion);

?>