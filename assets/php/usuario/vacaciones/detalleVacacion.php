<?php
   	$elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("../../conexion.php");
	$conexion = conexion();
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
	$sql2 = "SELECT * FROM Vacacion WHERE id_vacacion = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$vacacion = mysqli_fetch_array($consulta2);

	if(trim($vacacion[6]) == "")
		$desc = "Sin descripción";
	else
		$desc = $vacacion[6];
	
	$datos["html"] = '<div class="card card-profile">
						<div class="card-header card-header-primary">
							<h4 class="card-title ">VACACIONES</h4>
							<p id="sub_vaca" class="card-category">Días de vacaciones: '.$vacacion[3].'</p>
						</div>
						<div class="card-body">
							<div class="row fecha-caja">
								<div class="col-md-6 fecha-date">
									<div id="fecha" class="datepicker-here"></div>
								</div>
								<div class="col-md-6">
									<div id="fecha-contenido">
										<p class="card-category">Del '.strftime("%A, %d de %B de %G", strtotime($vacacion[4])).'</p>
										<p class="card-category">Al '.strftime("%A, %d de %B de %G", strtotime($vacacion[5])).'</p>
										<p class="card-category mt-4">Descripción:</p>
										<h5>'.$desc.'</h5>
									</div>
								</div>
							</div>
						</div>
					</div>';

	$datos["fecha1"] = date("d/m/Y",strtotime($vacacion[2]));
	$datos["fecha2"] = date("d/m/Y",strtotime($vacacion[4]));
	$datos["fecha3"] = date("d/m/Y",strtotime($vacacion[5]));

	echo json_encode($datos);
	mysqli_close($conexion);
?>

