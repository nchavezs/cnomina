<?php
include("../../conexion.php");
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$id =  $_POST['id'];

$sql2 = "SELECT * FROM Gastos WHERE id_gastos = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$gastos = mysqli_fetch_array($consulta2);

$html = '<div class="p-2">
			<h4 class="negrita text-primary">Detalle de gastos económicos</h4>
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
						</div>
					</div>
				</div>
			</div>
		</div>';

$datos["html"] = $html;
$datos["fecha"] = date("d/m/Y", strtotime($gastos['fecha']));

echo json_encode($datos);
$conexion->close();