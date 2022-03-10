<?php
$id = $_POST['id'];
include "../../conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Movimiento WHERE id_movimiento = " . $id;
$consulta = $conexion->query($sql);
$movimiento = mysqli_fetch_array($consulta);

if (trim($movimiento["observacion"]) === "") {
    $obs = "Sin observación";
} else {
    $obs = $movimiento["observacion"];
}

$fecha = date("d/m/Y", strtotime($movimiento["fecha"]));

echo '<div class="p-2">
		<h4 class="negrita text-primary">Detalle de movimiento</h4>
		</div>
		<div class="card">
			<div class="card-body text-left">
				<div class="row">
					<div class="col-md-12">
						<div class="select-etiqueta">Fecha de movimiento</div>
						<input id="fecha1" type="text" class="campo datepicker-here" readonly value="' . $fecha . '" />
					</div>

					<div class="col-md-6">
						<div class="select-etiqueta">Puesto anterior</div>
						<input type="text" class="campo" readonly value="' . $movimiento["puestoAnterior"] . '" />
					</div>
					<div class="col-md-6">
						<div class="select-etiqueta">Puesto actual</div>
						<input type="text" class="campo" readonly value="' . $movimiento["puesto"] . '" />
					</div>

					<div class="col-md-6">
						<div class="select-etiqueta">Departamento anterior</div>
						<input type="text" class="campo" readonly value="' . $movimiento["departamentoAnterior"] . '" />
					</div>
					<div class="col-md-6">
						<div class="select-etiqueta">Departamento actual</div>
						<input type="text" class="campo" readonly value="' . $movimiento["departamento"] . '" />
					</div>
					<div class="col-md-12">
						<div class="select-etiqueta">Observaciones</div>
						<textarea id="observacion" readonly class="campo" rows="3">' . $obs . '</textarea>
					</div>
				</div>
			</div>
		</div>';

$conexion->close();
