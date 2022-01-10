<?php
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();



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
						<h4 class="font-weight-bold text-primary">Registrar nuevo empleado</h4>
						<small class="text-muted">Completa el siguiente formulario que contendrá los datos generales del empleado, podrás editarlos en cualquier momento.</small>
					</div>
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="form-group">
									<div class="select-etiqueta">Nombre(s)</div>
									<input id="nombres" type="text" placeholder="" class="form-control" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Apellido paterno</div>
									<input id="apellidop" type="text" placeholder="" class="form-control" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Apellido materno</div>
									<input id="apellidom" type="text" placeholder="" class="form-control" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">RFC</div>
									<input id="rfc" type="text" class="form-control" minlength=13 maxlength=13 required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">CURP</div>
									<input id="curp" type="text" class="form-control" maxlength=18 minlength=18 required>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Número de empleado</div>
									<input id="numero" type="text" class="form-control" maxlength=5 required
										onkeypress="return isNumberKey(event)">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Fecha de ingreso</div>
									<input id="ingreso" type="text" class="form-control datepicker-here"
										value="' . $hoy . '" readonly />
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
									while ($res2 = mysqli_fetch_row($consulta)) {
										echo '<option value="' . $res2[1] . '">' . $res2[1] . '</option>';
									}
									echo '</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="select">
									<div class="select-etiqueta">Departamento</div>
									<select id="departamento">';
									$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
									$consulta = mysqli_query($conexion, $sql);
									if ($consulta && (mysqli_num_rows($consulta)) > 0) {
										while ($res2 = mysqli_fetch_row($consulta)) {
											echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
										}
									} else {
										echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
									}

									echo '</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="select">
									<div class="select-etiqueta">Puesto</div>
									<select id="puesto" class="">
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="select">
									<div class="select-etiqueta">Plaza</div>
									<select id="plaza" class="">
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
									<input id="banca" type="text" class="form-control">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
									<input id="afiliacion" type="text" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="text-right p-3 pagina_2_opciones">
						<div class="btn btn-secondary pagina_2_boton btn-sm"><i class="material-icons">keyboard_backspace</i> Anterior </div>
						<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
					</div>
				</form>
			</div>
		</div>
	</div>';

mysqli_close($conexion);