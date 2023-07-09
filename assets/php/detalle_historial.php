<?php
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");

$sql = "SELECT * FROM Usuario WHERE RFC = (SELECT RFC FROM Historial WHERE id_historial = " . $id . ") LIMIT 1";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Historial WHERE RFC = '" . $usuario["RFC"] . "' ORDER BY elaboracion DESC";
$consulta = $conexion->query($sql);
$historial = mysqli_fetch_array($consulta);

$puesto = " - ";
if ($historial["tipo"] == "baja") {
    $sql = "SELECT id_plaza FROM Baja WHERE RFC = '" . $historial["RFC"] . "' AND  fecha = '" . $historial["fecha"] . "' LIMIT 1";
    $query = $conexion->query($sql);
} else if ($historial["tipo"] == "alta") {
    $sql = "SELECT id_plaza FROM Historial_Plaza WHERE RFC = '" . $historial["RFC"] . "' ORDER BY fecha_inicio ASC LIMIT 1";
    $query = $conexion->query($sql);

    $sql = "SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Empleado WHERE RFC = '" . $historial["RFC"] . "')";
    $query1 = $conexion->query($sql);
    if ($query1 && mysqli_num_rows($query1) > 0) {
        $puesto = mysqli_fetch_row($query1);
        $puesto = $puesto[0];
    }
} else if ($historial["tipo"] == "reingreso") {
    $sql = "SELECT id_plaza FROM Reingreso WHERE RFC = '" . $historial["RFC"] . "' AND fecha = '" . $historial["fecha"] . "' LIMIT 1";
    $query = $conexion->query($sql);
}

if ($query && mysqli_num_rows($query) > 0) {
    $plaza = mysqli_fetch_row($query);
    $plaza = $plaza[0];

    $sql = "SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = " . $plaza . ")";
    $query = $conexion->query($sql);
    if ($query && mysqli_num_rows($query) > 0) {
        $puesto = mysqli_fetch_row($query);
        $puesto = $puesto[0];
    }
}

echo '<div class="p-2">
			<h4 class="negrita text-primary">Detalle de historial</h4>
			<small class="text-muted">Detalle de historial de ' . $usuario["nombre"] . '.</small>
		</div>';

echo '<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="select-etiqueta">Fecha</div>
					<input type="text" class="campo" readonly value="' . date("d/m/Y", strtotime($historial["fecha"])) . '" />
				</div>

				<div class="col-md-6">
					<div class="select-etiqueta">Tipo</div>
					<input type="text" class="campo" readonly value="' . $historial["tipo"] . '" />
				</div>

				<div class="col-md-12">
					<div class="select-etiqueta">Puesto</div>
					<input type="text" class="campo" readonly value="' . $puesto . '" />
				</div>

				<div class="col-md-12">
					<div class="select-etiqueta">Descripción</div>
					<textarea readonly class="campo" rows="3">' . $historial["descripcion"] . '</textarea>
				</div>
			</div>
		</div>
	</div>';

echo '<div class="card">
				<div class="card-body centrado">';

if (!is_null($historial["url"])) {
    echo '<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $historial[0] . ',\'' . $historial["url"] . '\',\'' . $historial["RFC"] . '\',\'Historial\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>
				<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo(' . $historial[0] . ',\'Historial\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
} else {
    echo '<div class="btn btn-primary btn-sm btn3" onclick="archivo(' . $historial[0] . ',\'' . $historial["url"] . '\',\'' . $historial["RFC"] . '\',\'Historial\',1)"><i class="material-icons">cloud_upload</i> Subir archivo </div>';
}

echo '</div></div>';

echo '<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verHistorial(\'' . $historial["RFC"] . '\');">Regresar </div>
		</div>';

$conexion->close();
