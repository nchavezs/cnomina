<?php
   	$id = $_POST['id'];
	include("../../conexion.php");
	$conexion = conexion();
	setlocale(LC_ALL, "spanish");
	$sql2 = "SELECT * FROM Vacacion WHERE id_vacacion = " . $id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$vacacion = mysqli_fetch_array($consulta2);
	
	if (trim($vacacion["descripcion"]) == "") {
		$desc = "Sin descripción";
	} else {
		$desc = $vacacion["descripcion"];
	}
	
	$html = '<div class="p-2">
				<h4 class="negrita text-primary">Detalle de vacaciones</h4>
			</div>
			<div class="row">
			<div class="col-md-6">
					<div class="card">
						<div class="card-body centrado">
							<div id="fecha" class="datepicker-here"></div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-body text-left">
							<div id="fecha-contenido">
								<p class="card-category">Fecha de elaboración: ' . date("d/m/Y", strtotime($vacacion["elaboracion"])) . '</p>
								<p class="card-category">Días de vacaciones: ' . $vacacion["dias"] . '</p>
								<br>
								<p class="card-category">Del ' . strftime("%d de %B de %Y", strtotime($vacacion["del"])) . '</p>
								<p class="card-category">Al ' . strftime("%d de %B de %Y", strtotime($vacacion["al"])) . '</p>
								<br>
								<p>Descripción:</p>
								<h5>' . $desc . '</h5>
							</div>
						</div>
					</div>
				</div>
			</div>';
	
	$datos["html"] = $html;
	$datos["fecha2"] = date("d/m/Y", strtotime($vacacion["del"]));
	$datos["fecha3"] = date("d/m/Y", strtotime($vacacion["al"]));
	
	echo json_encode($datos);
	$conexion->close();