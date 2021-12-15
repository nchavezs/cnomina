<?php
include "../../conexion.php";
$conexion = conexion();
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$elemento = explode("-", $_POST['id']);
$id = $elemento[0];

$sql2 = "SELECT * FROM Pase WHERE id_pase = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$pase = mysqli_fetch_array($consulta2);

if ($pase[4] == 0) {
    $categoria = 'ENTRADA';
} else {
    $categoria = 'SALIDA';
}

if ($pase[5] == null) {
    $obs = 'Sin descripción';
} else {
    $obs = $pase[5];
}

$datos["html"] = '<div class="card card-profile">
					<div class="card-header card-header-primary">
						<h4 class="card-title">PASE DE ' . $categoria . '</h4>
						<p class="card-category">' . strftime("%d de %B de %G", strtotime($pase[2])) . '</p>
					</div>
					<div class="card-body">
						<div class="row fecha-caja">
							<div class="col-md-6 fecha-date">
								<div id="fecha" class="datepicker-here"></div>
							</div>
							<div class="col-md-6">
								<div id="fecha-contenido">
									<p class="card-category">Hora de pase: <span>' . $pase[3] . '</span></p>
									<p class="card-category mt-4">Descripción:</p>
									<h5>' . $obs . '</h5>
								</div>
							</div>
						</div>
					</div>
					</div>';

$datos["fecha"] = date("d/m/Y", strtotime($pase[2]));
$datos["hora"] = $pase[3];

echo json_encode($datos);
mysqli_close($conexion);
