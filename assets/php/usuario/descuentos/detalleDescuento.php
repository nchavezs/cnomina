<?php
    $elemento = explode("-", $_POST['id']);
	$id = $elemento[0];
	include("../../conexion.php");
    $conexion = conexion();
	setlocale(LC_ALL, "spanish");

	$sql2 = "SELECT * FROM Descuento WHERE id_descuento = ".$id;
	$consulta2 = mysqli_query($conexion, $sql2);
	$descuento = mysqli_fetch_array($consulta2);
	
	$datos["html"] = '<div class="card card-profile">
								<div class="card-header card-header-primary">
									<h4 class="card-title ">DESCUENTOS</h4>
									<p class="card-category">Días de descuento: '.$descuento[2].'</p>
								</div>
								<div class="card-body">
									<div class="row fecha-caja">
										<div class="col-md-6 fecha-date">
											<div id="fecha" class="datepicker-here"></div>
										</div>
										<div class="col-md-6">
											<div id="fecha-contenido">
												<p class="card-category">Fecha(s) de descuento:</p>';
										$fechas = explode(",",$descuento[4]);
										foreach($fechas as $fecha){
											$date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
											$datos["html"] =  $datos["html"].'<h5>• '.strftime("%A, %d de %B de %G", strtotime($date)).'</h5>';
										}
										$datos["html"] =  $datos["html"].'<p class="card-category">Descripción:</p>
												<h5>'.$descuento[5].'</h5>
											</div>
										</div>
									</div>
								</div>
							 </div>';

	$datos["fecha"] = date("d/m/Y",strtotime($descuento[3]));
	$datos["fechas"] = $descuento[4];

    echo json_encode($datos);
    mysqli_close($conexion);

?>
