<?php
$id = $_POST['id'];

include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$sql1 = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Descuento WHERE id_descuento = " . $id . ") LIMIT 1";
$consulta1 = mysqli_query($conexion, $sql1);
$usuario = mysqli_fetch_array($consulta1);

$sql2 = "SELECT * FROM Descuento WHERE id_descuento = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$descuento = mysqli_fetch_array($consulta2);

$html = '<div class="p-2">
		<h4 class="negrita text-primary">Detalle de descuento</h4>
			<small class="text-muted">Detalle de descuento de '.$usuario["nombre"].'.</small>
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
					<div class="card-body">
						<div id="fecha-contenido">
							<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($descuento["elaboracion"])) . '</span></p>
							<p class="card-category">Días de descuento: <span>' . $descuento[2] . '</span></p>
							<p class="card-category">Fecha(s) de descuento:</p>';
							$fechas = explode(",", $descuento["fechas"]);
							foreach ($fechas as $fecha) {
								$date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
								$html = $html . '<h5>• ' . strftime("%d de %B de %Y", strtotime($date)) . '</h5>';
							}
							$html = $html . '<p class="card-category">Descripción:</p>
							<h5>' . $descuento["motivo"] . '</h5>
							<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $descuento[0] . ',\'' . $descuento["url"] . '\',\'' . $descuento["RFC"] . '\',\'Descuento\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
							if (!is_null($descuento["url"])) {
								$html = $html . '<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo(' . $descuento[0] . ',\'Descuento\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
							}

		$html = $html . '</div>
					</div>
				</div>
			</div>
		</div>

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verDescuentos(\'' . $usuario["RFC"] . '\');"><i class="material-icons">arrow_back</i> Regresar </div>
		</div>';

$datos["html"] = $html;
$datos["fechas"] = $descuento["fechas"];

echo json_encode($datos);
$conexion->close();
