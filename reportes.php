<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(41, rol())){
	header("location: ./perfil");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Consulta Nómina</title>
   <?php include "assets/layouts/header.php"?>
</head>

<body>
	<div class="wrapper ">
		<?php include "assets/layouts/sidebar.php"; ?>
		<div class="main-panel">
			<?php include "assets/layouts/navbar.php"?>
			
			<div class="content">
				<div class="container-fluid">
				<div class="reportes">
						<div class="pagina pagina_1">
							<div class="row">
								<div class="col-md-4">
									<?php
									if(in_array( 42, rol())){
										echo '<div class="card" onclick="pagina(2);">';
									}else{
										echo '<div class="card" onclick="bloqueo();">';
									}
									?>
								
										<div class="card-body text-center">
											<h3 class="text-primary">General</h3>
											<img src="assets/img/reportes.svg" alt="">
											<p class="text-muted">Reporte general de puestos y departamentos.</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<?php
									if(in_array( 43, rol())){
										echo '<div class="card" onclick="pagina(3);">';
									}else{
										echo '<div class="card" onclick="bloqueo();">';
									}
									?>
										<div class="card-body text-center">
											<h3 class="text-primary">Plazas</h3>
											<img src="assets/img/plazas.svg" alt="">
											<p class="text-muted">Consulta información sobre las plazas.</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<?php
									if(in_array( 44, rol())){
										echo '<div class="card" onclick="pagina(4);">';
									}else{
										echo '<div class="card" onclick="bloqueo();">';
									}
									?>
										<div class="card-body text-center">
											<h3 class="text-primary">Empleados</h3>
											<img src="assets/img/usuarios.svg" alt="">
											<p class="text-muted">Realiza reportes de empleados.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="pagina pagina_2 adp-hide">
							<div class="row">
								<div class="col-md-7">
									<div class="card formulario">
										<div class="card-header py-4">
											<i class="material-icons regresar"
												onclick="pagina(1);">keyboard_backspace</i>
												<h4 class="text-primary negrita">Reporte de puestos y departamentos</h4>
											<h5 class="text-muted">Selecciona el periodo y almenos uno de los parámetros
												siguientes.
											</h5>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="del_1" type="text" readonly class="campo" placeholder="DEL"
														required>
												</div>
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="al_1" type="text" readonly class="campo" placeholder="AL"
														required>
												</div>
												<div class="col-md-12">
													<div class="select">
														<div class="select-etiqueta">Departamento</div>
														<select id="departamentos_1" multiple class="departamentos">
															<?php
																$conexion = conexion();
																$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
																$consulta = $conexion->query($sql);
																if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																	while ($res2 = mysqli_fetch_row($consulta)) {
																		echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
																	}
																} else {
																}
																$conexion->close();	
															?>
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="select">
														<div class="select-etiqueta">Puesto</div>
														<select id="puestos_1" multiple class="puestos"></select>
													</div>
												</div>
											</div>
											<div class="pie mt-3">
												<button class="btn btn-sm btn-success" id="reporte_general"><i
														class="material-icons">download</i> Generar reporte</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="card">
										<div class="card-body">
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="sin_goce" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Permiso sin goce de sueldo</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="con_goce" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Permiso con goce de sueldo</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="pases" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Pases</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="movimientos" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Movimientos</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="vacaciones" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Vacaciones</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="descuentos" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Descuentos</span>
											</div>
											<!-- <div class="check_opciones">
												<div class="toggle-btn">
													<input id="gastos" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Gastos médicos</span>
											</div> -->
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="altas" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Altas</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="bajas" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Bajas</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="pagina pagina_3 adp-hide">
							<div class="row">
								<div class="col-xl-8">
									<div class="card formulario">
										<div class="card-header py-4">
											<i class="material-icons regresar"
												onclick="pagina(1);">keyboard_backspace</i>
												<h4 class="text-primary negrita">Reporte de plazas</h4>
											<h5 class="text-muted">Selecciona el periodo y el tipo de reporte de plaza.
											</h5>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="del_2" disabled type="text" readonly class="campo" placeholder="DEL"
														required>
												</div>
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="al_2" type="text" readonly class="campo" placeholder="AL"
														required>
												</div>
												<div class="col-md-12">
													<div class="select">
														<div class="select-etiqueta">Departamento</div>
														<select id="departamentos_2" multiple class="departamentos">
															<?php
															$conexion = conexion();
															$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
															$consulta = $conexion->query($sql);
															if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																while ($res2 = mysqli_fetch_row($consulta)) {
																	echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
																}
															} else {
															}
															$conexion->close();	
														?>
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="select">
														<div class="select-etiqueta">Puesto</div>
														<select id="puestos_2" multiple class="puestos"></select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="select adp-hide">
														<div class="select-etiqueta">Plazas</div>
														<select id="plazas_2" multiple class="plazas"></select>
													</div>
												</div>
											</div>
											<div class="pie mt-3">
												<button class="btn btn-sm btn-success" id="reporte_plazas">
													<i class="material-icons">download</i> Generar reporte
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4">
									<div class="card">
										<div class="card-body">
											<div class="check_opciones">
												<div class="toggle-btn active">
													<input id="descripcion_plaza" type="checkbox" class="cb-value" checked/>
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Descripción de plazas</span>
											</div>
											<div class="check_opciones">
												<div class="toggle-btn">
													<input id="historial_plaza" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
												<span class="text-muted ml-3">Historial de plaza</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="pagina pagina_4 adp-hide">
						<div class="row">
								<div class="col-md-12">
									<div class="card formulario">
										<div class="card-header py-4">
											<i class="material-icons regresar"
												onclick="pagina(1);">keyboard_backspace</i>
												<h4 class="text-primary negrita">Reporte de empleados</h4>
											<h5 class="text-muted">Selecciona el periodo para generar el reporte de registro de información de usuario.
											</h5>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="del_3" type="text" readonly class="campo" placeholder="DEL"
														required>
												</div>
												<div class="col-md-6">
													<div class="select-etiqueta">Periodo</div>
													<input id="al_3" type="text" readonly class="campo" placeholder="AL"
														required>
												</div>
												<div class="col-md-12">
													<div class="select">
														<div class="select-etiqueta">Empleado</div>
														<select id="usuario_3" multiple class="usuarios">
															<?php
																$conexion = conexion();
																$sql = "SELECT * FROM Usuario WHERE categoria = 'user' ORDER BY nombre ASC";
																$consulta = $conexion->query($sql);
																if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																	while ($res2 = mysqli_fetch_array($consulta)) {
																		echo '<option value="' . $res2["RFC"] . '">' . $res2["nombre"] . '</option>';
																	}
																} else {
																}
																$conexion->close();	
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="pie mt-3">
												<button class="btn btn-sm btn-success" id="reporte_usuario"><i
														class="material-icons">download</i> Generar reporte</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

   <?php include 'assets/layouts/scripts.php' ?>
   <script src="assets/js/reportes.js?v=3.8.0"></script>
   <script>$("#tab-reportes").addClass("active");</script>

</body>
</html>