<?php
date_default_timezone_set("America/Mexico_City");
	setlocale(LC_ALL, "spanish");
$hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();


echo '<div class="formulario_caja">
		<div class="row">
			<div class="col-md-4">
				<div class="wallpaper">
					<img src="assets/img/form.svg" alt="">
				</div>
			</div>

			<div class="col-md-8">
				<div class="formulario">
					<form id="form-empleado-1" class="pagina_1">
						<div class="p-2">
							<h4 class="font-weight-bold text-primary">Registrar nuevo empleado</h4>
							<small class="text-muted">Completa el siguiente formulario que contendrá los datos generales del empleado, podrás editarlos en cualquier momento.</small>
						</div>
						<div class="card">
							<div class="row card-body">
								<div class="col-md-12">
									<div class="">
										<div class="select-etiqueta">Nombre(s)</div>
										<input id="nombres" type="text" maxlength="50" placeholder="" class="campo" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">Apellido paterno</div>
										<input id="apellidop" type="text" placeholder="" maxlength="50" class="campo" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">Apellido materno</div>
										<input id="apellidom" type="text" placeholder="" maxlength="50" class="campo" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">RFC</div>
										<input id="rfc" type="text" class="campo" minlength="11" maxlength="13" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">CURP</div>
										<input id="curp" type="text" class="campo" maxlength="18" minlength="18" required>
									</div>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="row card-body">
								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">Número de empleado</div>
										<input id="numero" type="text" class="campo" maxlength=5 required
											onkeypress="return isNumberKey(event)">
									</div>
								</div>

								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">Fecha de ingreso</div>
										<input id="ingreso" type="text" class="campo" value="' . $hoy . '" readonly />
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
								<div class="col-md-6">
									<div class="select">
										<div class="select-etiqueta">Tipo de trabajador</div>
										<select id="trabajador">';
										$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
										$consulta = mysqli_query($conexion, $sql);
										while ($res2 = mysqli_fetch_row($consulta)) {
											echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
										}
										echo '</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="select">
										<div class="select-etiqueta">Tipo de periodo</div>
										<select id="periodo">';
										$sql = "SELECT * FROM Periodo WHERE id_periodo <> 3";
										$consulta = mysqli_query($conexion, $sql);
										while ($res2 = mysqli_fetch_row($consulta)) {
											echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
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
									<div class="">
										<div class="select-etiqueta">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
										<input id="banca" maxlength="18" type="text" class="campo">
									</div>
								</div>

								<div class="col-md-6">
									<div class="">
										<div class="select-etiqueta ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
										<input id="afiliacion" maxlength="20" type="text" class="campo">
									</div>
								</div>
							</div>
						</div>

						<div class="text-right p-3 pagina_2_opciones">
							<div class="btn btn-secondary pagina_2_boton btn-sm"><i class="material-icons">chevron_left</i> Anterior </div>
							<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div> ';

mysqli_close($conexion);