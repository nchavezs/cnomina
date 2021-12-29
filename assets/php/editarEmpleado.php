<?php
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "' AND categoria = 'user'";
$consulta = mysqli_query($conexion, $sql);
$res = mysqli_fetch_array($consulta);

echo '<form id="form-empleado">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">Modificar empleado</h4>
					<p class="card-category">' . $res['nombre'] . '</p>
				</div>
				<div class="card-body">
					<div class="row formulario3">
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">Nombre(s)</div>
								<input id="nombres" type="text" value="' . $res['nombres'] . '" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label">Apellido paterno</div>
								<input id="apellidop" type="text" value="' . $res['apellidop'] . '" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label">Apellido materno</div>
								<input id="apellidom" type="text" value="' . $res['apellidom'] . '" class="form-control" required>
							</div>
						</div>';

echo '<div class="col-md-6">
						<div class="select">
						<div class="select-label">Tipo de trabajador</div>
						<select id="trabajador" class="custom-select select-empleado trabajador-select">';

$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    $sql2 = "SELECT * FROM Trabajador WHERE nombre = '" . $res['tipoTrabajador'] . "'";
    $consulta2 = mysqli_query($conexion, $sql2);
    if (mysqli_num_rows($consulta2) == 0) {
        echo '<option selected value="">SELECCIONAR</option>';
    }

    while ($res2 = mysqli_fetch_row($consulta)) {
        echo '<option value="' . $res2[1] . '" ';
        if ($res['tipoTrabajador'] === $res2[1]) {
            echo 'selected';
        }

        echo '>' . $res2[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">SIN RESULTADOS</option>';
}

echo '</select></div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<div class="select-label">Número de empleado</div>
								<input id="numero" type="text" class="form-control" maxlength=5 value="' . str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT) . '" required>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label">Fecha de ingreso</div>
								<input id="ingreso" type="text" class="datepicker-here form-control readonly" value="' . $res['fechaRelLab'] . '" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">RFC</div>
								<input id="rfc" type="text" class="form-control" disabled minlength=13 maxlength=13 value="' . $res['RFC'] . '" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="select-label">CURP</div>
								<input id="curp" type="text" class="form-control" minlength=18 maxlength=18 value="' . $res['CURP'] . '" required>
							</div>
						</div>';
echo '<div class="col-md-6">
						<div class="select">
						<div class="select-label label-depa">Departamento</div>
						<select id="departamento" class="custom-select select-empleado departamento-select">';
$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    $sql2 = "SELECT * FROM Departamento WHERE nombre = '" . $res['departamento'] . "'";
    $consulta2 = mysqli_query($conexion, $sql2);
    if (mysqli_num_rows($consulta2) == 0) {
        echo '<option selected value="">SELECCIONAR</option>';
    }

    while ($res2 = mysqli_fetch_row($consulta)) {
        echo '<option value="' . $res2[1] . '" ';
        if ($res['departamento'] === $res2[1]) {
            echo 'selected';
        }

        echo '>' . $res2[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">SIN RESULTADOS</option>';
}

echo '</select></div>
						</div>';

echo '<div class="col-md-6">
						<div class="select">
						<div class="select-label label-puesto">Puesto</div>
						<select id="puesto" class="custom-select select-empleado puesto-select">';

$sql = "SELECT * FROM Puesto ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    $sql2 = "SELECT * FROM Puesto WHERE nombre = '" . $res['puesto'] . "'";
    $consulta2 = mysqli_query($conexion, $sql2);
    if (mysqli_num_rows($consulta2) == 0) {
        echo '<option selected value="">SELECCIONAR</option>';
    }

    while ($res2 = mysqli_fetch_row($consulta)) {
        echo '<option value="' . $res2[1] . '" ';
        if ($res['puesto'] === $res2[1]) {
            echo 'selected';
        }

        echo '>' . $res2[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">SIN RESULTADOS</option>';
}

echo '</select></div>
						</div>';

echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
								<input id="banca" type="text" class="form-control" value="' . $res['banca'] . '">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="select-label">No. de afiliación <cite class="text-danger"> opcional</cite></div>
								<input id="afiliacion" type="text" class="form-control" value="' . $res['afiliacion'] . '">
							</div>
						</div>';

// if(!is_null($res["archivo"])){
//     echo '<div class="col-md-6">
//             <input type="file" id="file" /><label for="file" class="descargar btn-3"><span> <i class="material-icons">cloud_upload</i> Reemplazar archivo</span></label>
//             <input type="input" id="archivo" value="' . $res['archivo'] . '" hidden="true">
//         </div>
//             <div class="col-md-6">
//             <div id="descargar-btn" class="descargar btn-3"><span> <i class="material-icons">cloud_download</i>Descargar archivo</span></div>
//             <input id="descargar-input" value="' . $res['archivo'] . '" hidden="true">
//         </div>';
// }
// else{
//     echo '<div class="col-md-12">
//     <input type="file" id="file" /><label for="file" class="descargar btn-3"><span> <i class="material-icons">cloud_upload</i> Subir archivo</span></label>
//     <input type="input" id="archivo" value="' . $res['archivo'] . '" hidden="true">
// </div>';
// }
echo '</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Completa todos los campos</div>
			</div>
			<div class="text-right p-3">
				<div class="btn btn-secondary regresar" id="salir"><i class="material-icons">arrow_back</i> Cancelar </div>
					<button type="submit" class="btn btn-primary regresar" ><i class="material-icons">save</i> Guardar </button>
				</div>
			</div>
		</form>';
mysqli_close($conexion);
