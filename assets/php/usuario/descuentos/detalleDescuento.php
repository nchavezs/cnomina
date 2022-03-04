<?php
$id = $_POST['id'];
include "../../conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");

$sql = "SELECT * FROM Descuento WHERE id_descuento = " . $id;
$consulta = $conexion->query($sql);
$descuento = mysqli_fetch_array($consulta);

$html = '<div class="card card-profile">
			<div class="card-header card-header-primary">
				<h4 class="card-title ">DESCUENTOS</h4>
				<p class="card-category">Días de descuento: ' . $descuento["dias"] . '</p>
			</div>
			<div class="card-body">
				<div class="row fecha-caja">
					<div class="col-md-6 fecha-date">
						<div id="fecha" class="datepicker-here"></div>
					</div>
					<div class="col-md-6">
						<div id="fecha-contenido">
							<p class="card-category">Fecha(s) de descuento:</p>';
							$fechas = explode(",", $descuento["fechas"]);
							foreach ($fechas as $fecha) {
								$date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
								$html = $html . '<h5>• ' . strftime("%d de %B de %G", strtotime($date)) . '</h5>';
							}
							$html = $html . '<p class="card-category">Descripción:</p>
							<h5>' . $descuento["motivo"] . '</h5>
						</div>
					</div>
				</div>
			</div>
		</div>';

$datos["html"] = $html;
$datos["fecha"] = date("d/m/Y", strtotime($descuento["fecha"]));
$datos["fechas"] = $descuento["fechas"];

echo json_encode($datos);
$conexion->close();
