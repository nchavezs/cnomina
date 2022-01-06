<?php
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();

echo '<form id="form-empleado">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">Nuevo empleado</h4>
					<p class="card-category">Complete los siguientes campos</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-label">Nombre(s)</div>
								<input id="nombres" type="text" placeholder="" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label ">Apellido paterno</div>
								<input id="apellidop" type="text" placeholder="" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label ">Apellido materno</div>
								<input id="apellidom" type="text" placeholder="" class="form-control" required>
							</div>
						</div>';

echo '<div class="col-md-6">
						<div class="select">
							<div class="select-label label-tipo">Tipo de trabajador</div>
								<select id="trabajador" class="custom-select select-empleado trabajador-select">';

$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
while ($res2 = mysqli_fetch_row($consulta)) {
    echo '<option value="' . $res2[1] . '">' . $res2[1] . '</option>';
}
echo '</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<div class="select-label ">Número de empleado</div>
								<input id="numero" type="text" class="form-control" maxlength=5 required onkeypress="return isNumberKey(event)">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
							<div class="select-label ">Fecha de ingreso</div>
								<input id="ingreso" type="text" class="form-control datepicker-here" value="' . $hoy . '" readonly/>
							</div>
						</div>';

echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label ">RFC</div>
								<input id="rfc" type="text" class="form-control" minlength=13 maxlength=13 required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="select-label ">CURP</div>
								<input id="curp" type="text" class="form-control" maxlength=18 minlength=18 required>
							</div>
						</div>';

						echo '<div class="col-md-6">
							<div class="select">
								<div class="select-label label-depa">Departamento</div>
									<select id="departamento" class="custom-select select-empleado departamento-select">';

$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res2 = mysqli_fetch_row($consulta)) {
        echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
}

echo '</select></div>
						</div>';

echo '<div class="col-md-6">
						<div class="select">
							<div class="select-label label-puesto">Puesto</div>
								<select id="puesto" class="custom-select select-empleado puesto-select">';



echo '</select>
							</div>
						</div>';

echo '<div class="col-md-6">
							<div class="form-group">
							<div class="select-label ">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
								<input id="banca" type="text" class="form-control" >
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="select-label ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
								<input id="afiliacion" type="text" class="form-control">
							</div>
						</div>


					</div>
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

// <div class="col-md-12">
//                     <input type="file" id="file" /><label for="file" class="btn-3"><span> <i class="material-icons">cloud_upload</i> Subir archivo</span></label>
//                     <input type="input" id="archivo" hidden="true">
//                 </div>