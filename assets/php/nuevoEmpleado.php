<?php
session_start();
$id_periodo = $_SESSION["id_periodo"];

setlocale(LC_ALL, "spanish");
// $hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Periodo WHERE id_periodo = ".$id_periodo;
$consulta = $conexion->query($sql);
$periodo = mysqli_fetch_array($consulta);

echo '<div class="formulario_caja">
		<div class="row">
			<div class="col-md-3">
				<div class="wallpaper">
					<img src="assets/img/form.svg" alt="">
				</div>
			</div>

			<div class="col-md-9">
				<div class="formulario">
					<form id="form-empleado-1" class="pagina_1" autocomplete="off">
						<div class="p-2">
							<h4 class="negrita text-primary">Registrar nuevo empleado</h4>
							<small class="text-muted">Completa el siguiente formulario que contendrá los datos generales del empleado, podrás editarlos en cualquier momento.</small>
						</div>
						<div class="card">
							<div class="row card-body">
								<div class="col-md-12">
									<div class="select-etiqueta">Nombre(s)</div>
									<input id="nombres" type="text" maxlength="50" placeholder="" class="campo" required>
								</div>
								<div class="col-md-6">
									<div class="select-etiqueta ">Apellido paterno</div>
									<input id="apellidop" type="text" placeholder="" maxlength="50" class="campo" required>
								</div>
								<div class="col-md-6">
									<div class="select-etiqueta ">Apellido materno</div>
									<input id="apellidom" type="text" placeholder="" maxlength="50" class="campo" required>
								</div>
								<div class="col-md-6">
									<div class="select-etiqueta ">RFC</div>
									<input id="rfc" type="text" class="campo" minlength="10" maxlength="13" required>
								</div>
								<div class="col-md-6">
									<div class="select-etiqueta ">CURP</div>
									<input id="curp" type="text" class="campo" maxlength="18" minlength="18" required>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="row card-body">
								<div class="col-md-6">
									<div class="select-etiqueta">Domicilio <cite class="text-danger"> opcional</cite></div>
									<input id="domicilio" maxlength="100" type="text" class="campo">
								</div>

								<div class="col-md-6">
									<div class="select-etiqueta">Código Postal <cite class="text-danger"> opcional</cite></div>
									<input id="postal" maxlength="5" type="text" class="campo">
								</div>
							</div>
						</div>
						<div class="pie">
							<div class="btn btn-secondary btn-sm" id="salir">Cancelar </div>
							<button type="submit" class="btn btn-primary pagina_1_boton btn-sm">Siguiente<i class="material-icons">navigate_next</i></button>
						</div>
					</form>

					<form id="form-empleado-2" class="pagina_2 adp-hide">
						<div class="card">
							<div class="row card-body">
								<div class="col-md-6">
									<div class="select-etiqueta ">Número de empleado</div>
									<input id="numero" type="text" class="campo" maxlength=5 required onkeypress="return isNumberKey(event)">
								</div>

								<div class="col-md-6">
									<div class="select-etiqueta ">Fecha de ingreso</div>
									<input id="fecha" type="text" class="campo" onkeypress="return false;" required/>
								</div>
								
								<div class="col-md-12">
									<div class="select">
										<div class="select-etiqueta">Departamento</div>
										<select id="departamento">';
										$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
										$consulta = $conexion->query($sql);
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
								<div class="col-md-6">
									<div class="select">
										<div class="select-etiqueta">Puesto</div>
										<select id="puesto" class="">
										</select>
									</div>
								</div>
								<div class="col-md-6">
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
									<div class="select-etiqueta">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
									<input id="banca" maxlength="18" type="text" class="campo">
								</div>

								<div class="col-md-6">
									<div class="select-etiqueta ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
									<input id="afiliacion" maxlength="20" type="text" class="campo">
								</div>

								<div class="col-md-6">
									<div class="select-etiqueta ">E-mail <cite class="text-danger"> opcional</cite></div>
									<input id="email" maxlength="50" type="email" class="campo">
								</div>

								<div class="col-md-6">
									<div class="select-etiqueta ">Teléfono <cite class="text-danger"> opcional</cite></div>
									<input id="telefono" name="telefono" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 type="text" class="campo">
								</div>

							</div>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="check_opciones">
									<div class="toggle-btn">
										<input id="retroactivo" type="checkbox" class="cb-value" /> 
										<span class="round-btn"></span>
									</div>
									<span class="text-muted ml-3">¿Pagar retroactivo para este periodo?</span>	
								</div>
							</div>
						</div>

						<div class="pie pagina_2_opciones">
							<div class="btn btn-secondary pagina_2_boton btn-sm"><i class="material-icons">chevron_left</i> Anterior </div>
							<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div> ';

$conexion->close();