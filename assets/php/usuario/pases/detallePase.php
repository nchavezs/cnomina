<?php
include "../../conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$id = $_POST['id'];

$sql = "SELECT * FROM Pase WHERE id_pase = " . $id;
$consulta = mysqli_query($conexion, $sql);
$pase = mysqli_fetch_array($consulta);
if ($pase[4] == 0) {
    $categoria = 'entrada';
} else {
    $categoria = 'salida';
}

if ($pase[5] == null) {
    $obs = 'Sin observación';
} else {
    $obs = $pase[5];
}

$html = '<div class="p-2">
		<h4 class="negrita text-primary">Detalle de pase</h4>
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
					<div class="card-body text-left" id="fecha-contenido">
						<p class="card-category">Fecha de pase: <span>' . date("d/m/Y", strtotime($pase[2])) . '</span> </p>
						<p class="card-category">Hora: <span>' . $pase[3] . '</span></p>
						<br>
						<p>Observación:</p>
						<h5>' . $obs . '</h5>
					</div>
				</div>
			</div>
		</div>';

$datos["html"] = $html;
$datos["fecha"] = date("d/m/Y", strtotime($pase[2]));
$datos["hora"] = $pase[3];

echo json_encode($datos);
$conexion->close();