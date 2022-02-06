<?php
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("conexion.php");
    $conexion = conexion();
	date_default_timezone_set("America/Mexico_City");
	setlocale(LC_ALL, "spanish");
	$sql1 = "SELECT nombre FROM Usuario WHERE RFC = (SELECT RFC FROM Permiso WHERE id_permiso = ".$id.") LIMIT 1";
	$consulta1 = mysqli_query($conexion, $sql1);
	$usuario = mysqli_fetch_array($consulta1);

	$sql2 = "SELECT * FROM Permiso WHERE id_permiso = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$permiso = mysqli_fetch_array($consulta2);

	if($permiso[6] == 0)
		$tipo = "con";
	else
		$tipo = "sin";

	if($permiso[7] == "")
		$desc = "Sin descripción";
	else
		$desc = $permiso[7];

	if($permiso[9] == 1)
		$materno = "active";
	else
		$materno = "";
	
	
	$datos["html"] = '<div class="card">
								<div class="card-header card-header-primary">
									<h4 class="card-title ">Permiso '.$tipo.' goce de sueldo</h4>
									<p class="card-category">Empleado: '.$usuario[0].'</p>
								</div>
								<div class="card-body">
									<div class="row fecha-caja">
										<div class="col-md-6 fecha-date">
											<div id="fecha" class="datepicker-here"></div>';
										if($permiso[6] == 0){
											$datos["html"] = $datos["html"].'<div id="materno-caja" class="p-2">
													<div class="materno">
														<div class="row">
															<div class="col-3">
																<div class="toggle-btn '.$materno.'">
																	<input type="checkbox" class="cb-value" />
																	<span class="round-btn"></span>
																</div>
															</div>
															<div class="col-9">
																<p>Permiso materno</p>
															</div>
														</div>
													</div>
												</div>';
										}
										$datos["html"] = $datos["html"].'</div>
										<div class="col-md-6">
											<div id="fecha-contenido">
												<p class="card-category">Fecha de elaboración: <span>'.date("d/m/Y",strtotime($permiso[2])).'</span></p>
												<p class="card-category">Días de permiso: <span>'.$permiso[3].'</span></p>
												<br>
												<p class="card-category">Del '.strftime("%A, %d de %B de %G", strtotime($permiso[4])).'</p>
												<p class="card-category">Al '.strftime("%A, %d de %B de %G", strtotime($permiso[5])).'</p>
												<br>
												<p class="card-category">Descripción:</p>
												<h5>'.$desc.'</h5>
												<div class="btn3" onclick="archivo('.$permiso[0].',\''.$permiso[8].'\',\''.$permiso[1].'\',\'Permiso\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
												if(!is_null($permiso[8]))
													$datos["html"] = $datos["html"] . '<div class="btn3" onclick="eliminar_archivo('.$permiso[0].',\'Permiso\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		$datos["html"] = $datos["html"] . '</div>
										</div>
									</div>
								</div>
							 </div>

							 <div class="row">
								<div class="col-12">
									<div class="btn btn-secondary btn-sm regresar " id="'.$permiso[1].'" onclick="verPermisos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
								</div>
							 </div>';

	$datos["fecha1"] = date("d/m/Y",strtotime($permiso[2]));
	$datos["fecha2"] = date("d/m/Y",strtotime($permiso[4]));
	$datos["fecha3"] = date("d/m/Y",strtotime($permiso[5]));

    echo json_encode($datos);
    mysqli_close($conexion);

?>