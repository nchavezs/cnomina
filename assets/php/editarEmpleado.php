<?php
setlocale(LC_ALL, "spanish");
$hoy = date("d/m/Y");
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT 
*, 
(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
(SELECT id_plaza FROM Plaza WHERE RFC = Empleado.RFC) AS plaza,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento 
FROM Empleado WHERE RFC = '" . $id . "'";

$consulta = mysqli_query($conexion, $sql);
$res = mysqli_fetch_array($consulta);

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
					<div class="text-left p-2">
						<h4 class="font-weight-bold text-primary">Editar información</h4>
						<small class="text-muted">Actualiza la información básica del empleado.</small>
					</div>
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="">
									<div class="select-etiqueta">Nombre(s)</div>
									<input id="nombres" type="text" placeholder="" maxlength="50" class="campo" value="'.$res["nombres"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">Apellido paterno</div>
									<input id="apellidop" type="text" placeholder="" maxlength="50" class="campo" value="'.$res["apellidop"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">Apellido materno</div>
									<input id="apellidom" type="text" placeholder="" maxlength="50" class="campo" value="'.$res["apellidom"].'" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">RFC</div>
									<input id="rfc" disabled type="text" class="campo" value="'.$res["RFC"].'">
								</div>
							</div>
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">CURP</div>
									<input id="curp" type="text" class="campo" maxlength="18" minlength="18" value="'.$res["CURP"].'" required>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">Número de empleado</div>
									<input id="numero" type="text" class="campo" disabled value="'.$res["id_empleado"].'">
								</div>
							</div>

							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">Fecha de ingreso</div>
									<input id="fecha" type="text" class="campo" disabled value="'.$res["fechaRelLab"].'" />
								</div>
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
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="select">
										<div class="select-etiqueta">Tipo de trabajador</div>
										<select id="trabajador">';
										$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
										$consulta = mysqli_query($conexion, $sql);
										if ($consulta && (mysqli_num_rows($consulta)) > 0) {
											$sql2 = "SELECT * FROM Trabajador WHERE id_trabajador = '" . $res['id_trabajador'] . "'";
											$consulta2 = mysqli_query($conexion, $sql2);
											if (mysqli_num_rows($consulta2) == 0) {
												echo '<option selected value="">SELECCIONA UNA OPCIÓN</option>';
											}

											while ($res2 = mysqli_fetch_row($consulta)) {
												echo '<option value="' . $res2[0] . '" ';
												if ($res['id_trabajador'] == $res2[0]) {
													echo 'selected';
												}

												echo '>' . $res2[1] . '</option>';
											}
										} else {
											echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
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
										if ($consulta && (mysqli_num_rows($consulta)) > 0) {
											while ($res2 = mysqli_fetch_row($consulta)) {
												echo '<option value="' . $res2[0] . '" ';
												if ($res['id_periodo'] == $res2[0]) {
													echo 'selected';
												}
												echo '>' . $res2[1] . '</option>';
											}
										} else {
											echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
										}
										echo '</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="">
										<div class="select-etiqueta ">Departamento</div>
										<input type="text" class="campo" disabled value="'.$res["departamento"].'" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="">
										<div class="select-etiqueta ">Puesto</div>
										<input type="text" class="campo" disabled value="'.$res["puesto"].'" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="">
										<div class="select-etiqueta ">Plaza</div>
										<input type="text" class="campo" disabled value="PLAZA #'.$res["plaza"].'" />
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="row card-body">
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta">Cuenta bancaria <cite class="text-danger"> opcional</cite></div>
									<input id="banca" maxlength="18" type="text" class="campo" value="'.$res["banca"].'">
								</div>
							</div>

							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">No. de afiliación <cite class="text-danger"> opcional</cite></div>
									<input id="afiliacion" maxlength="20" type="text" class="campo" value="'.$res["afiliacion"].'">
								</div>
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
</div>';

mysqli_close($conexion);
