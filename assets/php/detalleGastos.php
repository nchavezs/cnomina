<?php
setlocale(LC_ALL, "spanish");
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();

$sql1 = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Gastos WHERE id_gastos = " . $id . ")";
$consulta1 = mysqli_query($conexion, $sql1);
$usuario = mysqli_fetch_array($consulta1);

$sql2 = "SELECT * FROM Gastos WHERE id_gastos = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$gastos = mysqli_fetch_array($consulta2);

$html = '<div class="p-2">
			<h4 class="negrita text-primary">Detalle de permiso</h4>
			<small class="text-muted">Detalle de permiso de ' . $usuario["nombre"] . '.</small>
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
							<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($gastos['elaboracion'])) . '</span></p>
							<p class="card-category">Fecha de apoyo: <span>' . date("d/m/Y", strtotime($gastos['fecha'])) . '</span></p>
							<br>
							<p class="card-category">Monto de apoyo: <span>$' . $gastos['monto'] . '</span></p>
							<br>
							<p class="card-category">Nombre quién otorga el apoyo:</p>
							<h5 class="card-category">' . $gastos['nombre'] . '</h5>
							<br>
							<p>Concepto:</p>
							<h5>' . $gastos['concepto'] . '</h5>
							<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $gastos[0] . ',\'' . $gastos['url'] . '\',\'' . $gastos[1] . '\',\'Gastos\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>';
							if (!is_null($gastos['url'])) {
								$html = $html . '<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo(' . $gastos[0] . ',\'Gastos\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
							}

		$html = $html . '</div>
					</div>
				</div>
			</div>
		</div>

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verGastos(\'' . $usuario["RFC"] . '\');"><i class="material-icons">arrow_back</i> Regresar </div>
		</div>';

$datos["html"] = $html;
$datos["fecha"] = date("d/m/Y", strtotime($gastos['fecha']));

echo json_encode($datos);
$conexion->close();