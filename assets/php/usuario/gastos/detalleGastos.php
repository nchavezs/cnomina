<?php
include("../../conexion.php");
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$elemento = explode("-", $_POST['id']);
$id = $elemento[0];

$sql2 = "SELECT * FROM Gastos WHERE id_gastos = " . $id;
$consulta2 = mysqli_query($conexion, $sql2);
$gastos = mysqli_fetch_array($consulta2);

$datos["html"] = '<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Gastos médicos</h4>
						<p class="card-category">'.strftime("%A, %d de %B de %G", strtotime($gastos['fecha'])).'</p>
					</div>
					<div class="card-body">
						<div class="row fecha-caja">
							<div class="col-md-6 fecha-date">
								<div id="fecha" class="datepicker-here"></div>
							</div>
							<div class="col-md-6">
								<div id="fecha-contenido">
									<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($gastos['elaboracion'])) . '</span></p>
									<p class="card-category">Monto de apoyo: <span>$' . $gastos['monto'] . '</span></p>
									<br>
									<p class="card-category">Nombre quién otorga el apoyo:</p>
									<p class="card-category">' . $gastos['nombre'] . '</p>
									<br>
									<p>Concepto:</p>
									<h5>' . $gastos['concepto'] . '</h5>
								</div>
							</div>

						</div>
					</div>
				</div>';

$datos["fecha"] = date("d/m/Y", strtotime($gastos['fecha']));

echo json_encode($datos);
mysqli_close($conexion);
