<?php
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("conexion.php");
    $conexion = conexion();
	$sql1 = "SELECT nombre FROM Usuario WHERE RFC = (SELECT RFC FROM Gastos WHERE id_gastos = ".$id.")";
	$consulta1 = mysqli_query($conexion, $sql1);
	$usuario = mysqli_fetch_array($consulta1);

	$sql2 = "SELECT * FROM Gastos WHERE id_gastos = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$gastos = mysqli_fetch_array($consulta2);
	
	$datos["html"] = '<div class="card">
						<div class="card-header card-header-primary">
							<h4 class="card-title ">Gastos médicos</h4>
							<p class="card-category">Empleado: '.$usuario[0].'</p>
						</div>
						<div class="card-body">
							<div class="row fecha-caja">
								<div class="col-md-6 fecha-date">
									<div id="fecha" class="datepicker-here"></div>
								</div>
								<div class="col-md-6">
									<div id="fecha-contenido">
										<p class="card-category">Fecha de elaboración: <span>'.date("d/m/Y",strtotime($gastos['elaboracion'])).'</span></p>
										<p class="card-category">Fecha de apoyo: <span>'.date("d/m/Y",strtotime($gastos['fecha'])).'</span></p>
										<br>
										<p class="card-category">Monto de apoyo: <span>$'.$gastos['monto'].'</span></p>
										<br>
										<p class="card-category">Nombre quién otorga el apoyo:</p>
										<h5 class="card-category">'.$gastos['nombre'].'</h5>
										<br>
										<p>Concepto:</p>
										<h5>'.$gastos['concepto'].'</h5>
										<div class="btn3" onclick="archivo('.$gastos[0].',\''.$gastos['url'].'\',\''.$gastos[1].'\',\'Gastos\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
										if(!is_null($gastos['url']))
											$datos["html"] = $datos["html"] . '<div class="btn3" onclick="eliminar_archivo('.$gastos[0].',\'Gastos\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		$datos["html"] = $datos["html"] . '</div>
										</div>
									</div>
								</div>
							 </div>

							 <div class="row">
								<div class="col-12">
									<div class="btn btn-primary regresar" id="'.$gastos[1].'" onclick="verGastos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
								</div>
							 </div>';

	$datos["fecha"] = date("d/m/Y",strtotime($gastos['fecha']));

    echo json_encode($datos);
    mysqli_close($conexion);

?>