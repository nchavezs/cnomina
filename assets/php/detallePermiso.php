<?php
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$sql = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Permiso WHERE id_permiso = " . $id . " LIMIT 1)";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Permiso WHERE id_permiso = " . $id;
$consulta = $conexion->query($sql);
$permiso = mysqli_fetch_array($consulta);

if ($permiso["categoria"] == 0) {
    $tipo = "con";
} else {
    $tipo = "sin";
}

if ($permiso["descripcion"] == "") {
    $desc = "Sin descripción";
} else {
    $desc = $permiso["descripcion"];
}

if ($permiso["materno"] == 1) {
    $materno = "active";
} else {
    $materno = "";
}

$del = strftime('Del %d de %B de %Y', strtotime($permiso['del']));
$al = strftime('Al %d de %B de %Y', strtotime($permiso['al']));

$html = '<div class="p-2">
			<h4 class="font-weight-bold text-primary">Detalle de permiso</h4>
			<small class="text-muted">Detalle de permiso de '.$usuario["nombre"].'.</small>
		</div>
		
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body centrado">
						<div id="fecha" class="datepicker-here"></div>
					</div>
				</div>';
if ($permiso["categoria"] == 0) {
	$html = $html . '<div class="card">
						<div class="card-body centrado">
							<i class="material-icons text-success mr-3">task_alt</i>
							Permiso materno
						</div>
					</div>';
}

$html = $html . '</div>
					<div class="col-md-6">
						<div class="card" id="fecha-contenido">
							<div class="card-body">
								<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($permiso["elaboracion"])) . '</span></p>
								<p class="card-category">Días de permiso: <span>' . $permiso["dias"] . '</span></p>
								
								<p class="card-category pt-2">' .$del. '</p>
								<p class="card-category pb-2">' .$al . '</p>
								
								<p class="card-category">Descripción:</p>
								<h5>' . $desc . '</h5>
								<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $permiso[0] . ',\'' . $permiso["url"] . '\',\'' . $permiso["RFC"] . '\',\'Permiso\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
		if (!is_null($permiso["url"])) {
			$html = $html . '<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo(' . $permiso[0] . ',\'Permiso\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
		}

	$html = $html . '</div>
				</div>
			</div>
		</div>

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verPermisos(\''.$usuario["RFC"].'\');">Regresar </div>
		</div>';

$datos["html"] = $html;
$datos["del"] = date("d/m/Y", strtotime($permiso["del"]));
$datos["al"] = date("d/m/Y", strtotime($permiso["al"]));

$conexion->close();

echo json_encode($datos);

