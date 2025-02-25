<?php
$id = $_POST['id'];
include "../../conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");

$sql2 = "SELECT * FROM Descuento WHERE id_descuento = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$descuento = mysqli_fetch_array($consulta2);

$html = '<div class="p-2">
		<h4 class="negrita text-primary">Detalle de descuento</h4>
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
					<div class="card-body text-left">
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
						</div>
					</div>
				</div>
			</div>
		</div>';

$datos["html"] = $html;
$datos["fechas"] = $descuento["fechas"];

echo json_encode($datos);
$conexion->close();
