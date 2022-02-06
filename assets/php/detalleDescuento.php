<?php
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("conexion.php");
  	$conexion = conexion();
	date_default_timezone_set("America/Mexico_City");
	setlocale(LC_ALL, "spanish");
	$sql1 = "SELECT nombre FROM Usuario WHERE RFC = (SELECT RFC FROM Descuento WHERE id_descuento = ".$id.") LIMIT 1";
	$consulta1 = mysqli_query($conexion, $sql1);
	$usuario = mysqli_fetch_array($consulta1);

	$sql2 = "SELECT * FROM Descuento WHERE id_descuento = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$descuento = mysqli_fetch_array($consulta2);
	
	$datos["html"] = '<div class="card">
								<div class="card-header card-header-primary">
									<h4 class="card-title ">Descuentos</h4>
									<p class="card-category">Empleado: '.$usuario[0].'</p>
								</div>
								<div class="card-body">
									<div class="row fecha-caja">
										<div class="col-md-6 fecha-date">
											<div id="fecha" class="datepicker-here"></div>
										</div>
										<div class="col-md-6">
											<div id="fecha-contenido">
												<p class="card-category">Fecha de elaboración: <span>'.date("d/m/Y",strtotime($descuento[3])).'</span></p>
												<p class="card-category">Días de descuento: <span>'.$descuento[2].'</span></p>
												<p class="card-category">Fecha(s) de descuento:</p>';
										$fechas = explode(",",$descuento[4]);
										foreach($fechas as $fecha){
											$date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
											$datos["html"] =  $datos["html"].'<h5>• '.strftime("%d de %B de %G", strtotime($date)).'</h5>';
										}
										$datos["html"] =  $datos["html"].'<p class="card-category">Descripción:</p>
												<h5>'.$descuento[5].'</h5>
												<div class="btn3" onclick="archivo('.$descuento[0].',\''.$descuento[6].'\',\''.$descuento[1].'\',\'Descuento\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
												if(!is_null($descuento[6]))
													$datos["html"] = $datos["html"] . '<div class="btn3" onclick="eliminar_archivo('.$descuento[0].',\'Descuento\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		$datos["html"] = $datos["html"] . '</div>
										</div>
									</div>
								</div>
							 </div>

							 <div class="row">
								<div class="col-12">
									<div class="btn btn-secondary btn-sm regresar " id="'.$descuento[1].'" onclick="verDescuentos(this.id);"><i class="material-icons">arrow_back</i> Regresar </div>
								</div>
							 </div>';

	$datos["fecha"] = date("d/m/Y",strtotime($descuento[3]));
	$datos["fechas"] = $descuento[4];

    echo json_encode($datos);
    mysqli_close($conexion);

?>