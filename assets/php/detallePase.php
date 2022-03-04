<?php
setlocale(LC_ALL, "spanish");
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();
$sql = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Pase WHERE id_pase = " . $id . ")";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql2 = "SELECT * FROM Pase WHERE id_pase = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$pase = mysqli_fetch_array($consulta2);
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
		<small class="text-muted">Detalle de pase de '.$usuario["nombre"].'.</small>
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
					<div class="card-body" id="fecha-contenido">
						<p class="card-category">Fecha de pase: <span>' . date("d/m/Y", strtotime($pase[2])) . '</span> </p>
						<p class="card-category">Hora: <span>' . $pase[3] . '</span></p>
						<br>
						<p>Observación:</p>
						<h5>' . $obs . '</h5>
						<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $pase[0] . ',\'' . $pase[6] . '\',\'' . $pase[1] . '\',\'Pase\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
						if (!is_null($pase[6])) {
							$html = $html . '<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo(' . $pase[0] . ',\'Pase\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
						}

	$html = $html . '</div>
				</div>
			</div>
		</div>
		

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verPases(\'' . $usuario["RFC"] . '\');"><i class="material-icons">arrow_back</i> Regresar </div>
		</div>';

$datos["html"] = $html;
$datos["fecha"] = date("d/m/Y", strtotime($pase[2]));
$datos["hora"] = $pase[3];

echo json_encode($datos);
$conexion->close();