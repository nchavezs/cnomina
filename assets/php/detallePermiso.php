<?php
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$sql1 = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Permiso WHERE id_permiso = " . $id . ") LIMIT 1";
$consulta1 = mysqli_query($conexion, $sql1);
$usuario = mysqli_fetch_array($consulta1);

$sql2 = "SELECT * FROM Permiso WHERE id_permiso = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$permiso = mysqli_fetch_array($consulta2);

if ($permiso[6] == 0) {
    $tipo = "con";
} else {
    $tipo = "sin";
}

if ($permiso[7] == "") {
    $desc = "Sin descripción";
} else {
    $desc = $permiso[7];
}

if ($permiso[9] == 1) {
    $materno = "active";
} else {
    $materno = "";
}

$html = '<div class="p-2">
			<h4 class="font-weight-bold text-primary">Detalle de permiso</h4>
			<small class="text-muted">Detalle de permiso de '.$usuario["nombre"].'.</small>
		</div>
		<div class="row">
			<div class="col-lg-5">
				<div class="card">
					<div class="card-body p-3 d-flex justify-content-center">
						<div id="fecha" class="datepicker-here"></div>
					</div>
				</div>';
if ($permiso[6] == 0) {
	$html = $html . '<div class="card">
						<div class="card-body d-flex justify-content-center">
							<i class="material-icons text-success mr-3">task_alt</i>
							Permiso materno
						</div>
					</div>';
}

$html = $html . '</div>
					<div class="col-lg-7">
						<div class="card" id="fecha-contenido">
							<div class="card-body">
								<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($permiso[2])) . '</span></p>
								<p class="card-category">Días de permiso: <span>' . $permiso[3] . '</span></p>
								<br>
								<p class="card-category">Del ' . strftime("%A, %d de %B de %G", strtotime($permiso[4])) . '</p>
								<p class="card-category">Al ' . strftime("%A, %d de %B de %G", strtotime($permiso[5])) . '</p>
								<br>
								<p class="card-category">Descripción:</p>
								<h5>' . $desc . '</h5>
								<div class="btn3" onclick="archivo(' . $permiso[0] . ',\'' . $permiso[8] . '\',\'' . $permiso[1] . '\',\'Permiso\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
		if (!is_null($permiso[8])) {
			$html = $html . '<div class="btn3" onclick="eliminar_archivo(' . $permiso[0] . ',\'Permiso\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		}

	$html = $html . '</div>
				</div>
			</div>
		</div>

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verPermisos(\''.$usuario["RFC"].'\');">Regresar </div>
		</div>';

$datos["fecha1"] = date("d/m/Y", strtotime($permiso[2]));
$datos["fecha2"] = date("d/m/Y", strtotime($permiso[4]));
$datos["fecha3"] = date("d/m/Y", strtotime($permiso[5]));
$datos["html"] = $html;

echo json_encode($datos);
mysqli_close($conexion);
