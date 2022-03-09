<?php
   	$elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("../../conexion.php");
	$conexion = conexion();
	setlocale(LC_ALL, "spanish");
	$sql2 = "SELECT * FROM Vacacion WHERE id_vacacion = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$vacacion = mysqli_fetch_array($consulta2);

	if(trim($vacacion["descripcion"]) == "")
		$desc = "Sin descripción";
	else
		$desc = $vacacion["descripcion"];
	
	$datos["html"] = '<div class="card card-profile">
						<div class="card-header card-header-primary">
							<h4 class="card-title ">VACACIONES</h4>
							<p id="sub_vaca" class="card-category">Días de vacaciones: '.$vacacion["dias"].'</p>
						</div>
						<div class="card-body">
							<div class="row fecha-caja">
								<div class="col-md-6 fecha-date">
									<div id="fecha" class="datepicker-here"></div>
								</div>
								<div class="col-md-6">
									<div id="fecha-contenido">
										<p class="card-category">Del '.strftime("%d de %B de %Y", strtotime($vacacion["del"])).'</p>
										<p class="card-category">Al '.strftime("%d de %B de %Y", strtotime($vacacion["al"])).'</p>
										<p class="card-category mt-4">Descripción:</p>
										<h5>'.$desc.'</h5>
									</div>
								</div>
							</div>
						</div>
					</div>';

	// $datos["fecha1"] = date("d/m/Y",strtotime($vacacion[2]));
	$datos["fecha2"] = date("d/m/Y",strtotime($vacacion["del"]));
	$datos["fecha3"] = date("d/m/Y",strtotime($vacacion["al"]));

	echo json_encode($datos);
	$conexion->close();
?>

