<?php
	setlocale(LC_ALL, "spanish");
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("conexion.php");
    $conexion = conexion();
	$sql1 = "SELECT nombre FROM Usuario WHERE RFC = (SELECT RFC FROM Pase WHERE id_pase = ".$id.")";
	$consulta1 = mysqli_query($conexion, $sql1);
	$usuario = mysqli_fetch_array($consulta1);

	$sql2 = "SELECT * FROM Pase WHERE id_pase = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$pase = mysqli_fetch_array($consulta2);
	if($pase[4] == 0)
		$categoria = 'entrada';
	else
		$categoria = 'salida';

	if($pase[5] == null)
		$obs = 'Sin observación';
	else
		$obs = $pase[5];
	
	$datos["html"] = '<div class="card">
						<div class="card-header card-header-primary">
							<h4 class="card-title ">Pase de '.$categoria.'</h4>
							<p class="card-category">Empleado: '.$usuario[0].'</p>
						</div>
						<div class="card-body">
							<div class="row fecha-caja">
								<div class="col-md-6 fecha-date">
									<div id="fecha" class="datepicker-here"></div>
								</div>
								<div class="col-md-6">
									<div id="fecha-contenido">
										<p class="card-category">Fecha de pase: <span>'.date("d/m/Y",strtotime($pase[2])).'</span> </p>
										<p class="card-category">Hora: <span>'.$pase[3].'</span></p>
										<br>
										<p>Observación:</p>
										<h5>'.$obs.'</h5>
										<div class="btn3" onclick="archivo('.$pase[0].',\''.$pase[6].'\',\''.$pase[1].'\',\'Pase\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
										if(!is_null($pase[6]))
											$datos["html"] = $datos["html"] . '<div class="btn3" onclick="eliminar_archivo('.$pase[0].',\'Pase\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		$datos["html"] = $datos["html"] . '</div>
										</div>
									</div>
								</div>
							 </div>

							 <div class="row">
								<div class="col-12">
									<div class="btn btn-secondary btn-sm regresar " id="'.$pase[1].'" onclick="verPases(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
								</div>
							 </div>';

	$datos["fecha"] = date("d/m/Y",strtotime($pase[2]));
	$datos["hora"] = $pase[3];

    echo json_encode($datos);
    mysqli_close($conexion);

?>