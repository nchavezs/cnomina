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

echo '<div class="formulario row">
		<div class="col-md-4 p-0">
			<div class="wallpaper">
				<img src="assets/img/form.svg" alt="">
			</div>
		</div>

		<div class="col-md-8 p-0">
			<div class="card-body">
				<form id="form-empleado-1" class="pagina_1">
					<div class="text-left p-2">
						<h4 class="font-weight-bold text-primary">Editar información</h4>
						<small class="text-muted">Actualiza la información básica del empleado.</small>
					</div>
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="form-group">
									<div class="select-etiqueta">Nombre(s)</div>
									<input id="nombres" type="text" placeholder="" class="form-control" value="'.$res["nombres"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Apellido paterno</div>
									<input id="apellidop" type="text" placeholder="" class="form-control" value="'.$res["apellidop"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Apellido materno</div>
									<input id="apellidom" type="text" placeholder="" class="form-control" value="'.$res["apellidom"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">RFC</div>
									<input id="rfc" disabled type="text" class="form-control" value="'.$res["RFC"].'">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">CURP</div>
									<input id="curp" type="text" class="form-control" maxlength=18 minlength=18 value="'.$res["CURP"].'" required>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Número de empleado</div>
									<input id="numero" type="text" class="form-control" maxlength=5 value="'.$res["id_usuario"].'" required
										onkeypress="return isNumberKey(event)">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Fecha de ingreso</div>
									<input id="ingreso" type="text" class="form-control" readonly value="'.$res["fechaRelLab"].'" />
								</div>
							</div>
						</div>
					</div>
					<div class="text-right p-3">
						<div class="btn btn-secondary btn-sm" id="salir">Cancelar </div>
						<button type="submit" class="btn btn-primary pagina_1_boton btn-sm">Siguiente<i class="material-icons">navigate_next</i></button>
					</div>
				</form>

				<form id="form-empleado-2" class="pagina_2 adp-hide">
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="select">
									<div class="select-etiqueta">Tipo de trabajador</div>
									<select id="trabajador">';
									$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
									$consulta = mysqli_query($conexion, $sql);
									if ($consulta && (mysqli_num_rows($consulta)) > 0) {
										$sql2 = "SELECT * FROM Trabajador WHERE nombre = '" . $res['tipoTrabajador'] . "'";
										$consulta2 = mysqli_query($conexion, $sql2);
										if (mysqli_num_rows($consulta2) == 0) {
											echo '<option selected value="">SELECCIONA UNA OPCIÓN</option>';
										}

										while ($res2 = mysqli_fetch_row($consulta)) {
											echo '<option value="' . $res2[1] . '" ';
											if ($res['tipoTrabajador'] === $res2[1]) {
												echo 'selected';
											}

											echo '>' . $res2[1] . '</option>';
										}
									} else {
										echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
									}
									echo '</select>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
									<input id="banca" type="text" class="form-control" value="'.$res["banca"].'">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
									<input id="afiliacion" type="text" class="form-control" value="'.$res["afiliacion"].'">
								</div>
							</div>
						</div>
					</div>

					<div class="text-right p-3 pagina_2_opciones">
						<div class="btn btn-secondary pagina_2_boton btn-sm"><i class="material-icons">keyboard_backspace</i> Anterior </div>
						<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Actualizar información </button>
					</div>
				</form>
			</div>
		</div>
	</div>';

mysqli_close($conexion);
