<?php
include "assets/php/main_admin.php";
if (rol() != 1) {
	header('location:./registrar');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Consulta Nómina</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	<link href="assets/css/material-dashboard.css?v=3.2.6" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet" />
	<link href="assets/css/datepicker.min.css" rel="stylesheet" />
	<link href="assets/js/plugins/animate/adp.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/js/plugins/tailselect/css/default/tail.select-light.css">
	<link href="assets/css/sweetalert2.min.css?v=3.2.6" rel="stylesheet" />
</head>

<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="purple" data-background-color="white">
			<div class="municipio">Consulta Nómina <small><?php echo get_municipio() ?><small></div>
			<div class="avatar">
				<?php
				$foto = "assets/img/user.png";
				if ($varFoto != null) {
					$foto = $varFoto;
				}

				?>
				<a href="./perfil"><img src="<?php echo $foto ?>"></a>
				<p><?php echo $varName ?></p>
				<a href="mailto:"><?php echo $varEmail ?></a>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li class="nav-item ">
						<a class="nav-link" href="./registrar">
							<i class="material-icons">people</i>
							<p>Empleados</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="./perfil">
							<i class="material-icons">person_pin</i>
							<p>Perfil</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./prenomina">
							<i class="material-icons">receipt_long</i>
							<p>Prenómina</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./subir">
							<i class="material-icons">cloud_upload</i>
							<p>Impotar CFDI</p>
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="./consultar">
							<i class="material-icons">text_snippet</i>
							<p>Nóminas</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="./catalogos">
							<i class="material-icons">table_view</i>
							<p>Catálogos</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./plazas">
							<i class="material-icons">auto_awesome_motion</i>
							<p>Plazas</p>
						</a>
					</li>
					<li id="link2" class="nav-item">
						<a class="nav-link" href="./mensajes">
							<i class="material-icons">message</i>
							<p>Mensajes</p>
						</a>
					</li>
					<li id="link1" class="nav-item active">
						<a class="nav-link" href="./reportes">
							<i class="material-icons">summarize</i>
							<p>Reportes</p>
						</a>
					</li>
					<li class="nav-item" id="cerrar-btn">
						<a class="nav-link">
							<i class="material-icons">exit_to_app</i>
							<p>Cerrar sesión</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="">Reportes</a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
						aria-expanded="false" aria-label="Toggle navigation">
						<span class="sr-only">Toggle navigation</span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end">
						<ul class="navbar-nav">
							<li class="nav-item dropdown">
								<a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
									<i class="material-icons">notifications</i>
									<span class="notification noti-numero">0</span>
									<p class="d-lg-none d-md-block">Mensajes</p>
								</a>
								<div class="dropdown-menu dropdown-menu-right noti-caja"
									aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="#">No tiene notificaciones</a>
								</div>
							</li>
							<li class="nav-item">
								<a id="cerrar" class="nav-link" href="#">
									<i class="material-icons">exit_to_app</i>
									Cerrar sesión
								</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<!-- End Navbar -->
			<div class="content">
				<div id="msn-caja" class="container-fluid msn-caja">
					<div class="reportes">
						<div class="pagina pagina_1">
							<div class="row">
								<div class="col-md-4">
									<div class="card" onclick="pagina(2);">
										<div class="card-body text-center">
											<h3 class="text-primary">General</h3>
											<img src="assets/img/reportes.svg" alt="">
											<p class="text-muted">Reporte general de puestos y departamentos.</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card" onclick="pagina(3);">
										<div class="card-body text-center">
											<h3 class="text-primary">Plazas</h3>
											<img src="assets/img/plazas.svg" alt="">
											<p class="text-muted">Consulta información sobre las plazas.</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card" onclick="pagina(4);">
										<div class="card-body text-center">
											<h3 class="text-primary">Usuarios</h3>
											<img src="assets/img/usuarios.svg" alt="">
											<p class="text-muted">Realiza reportes de usuarios.</p>
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
																$consulta = mysqli_query($conexion, $sql);
																if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																	while ($res2 = mysqli_fetch_row($consulta)) {
																		echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
																	}
																} else {
																}
																mysqli_close($conexion);	
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
															$consulta = mysqli_query($conexion, $sql);
															if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																while ($res2 = mysqli_fetch_row($consulta)) {
																	echo '<option value="' . $res2[0] . '">' . $res2[1] . '</option>';
																}
															} else {
															}
															mysqli_close($conexion);	
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
																$consulta = mysqli_query($conexion, $sql);
																if ($consulta && (mysqli_num_rows($consulta)) > 0) {
																	while ($res2 = mysqli_fetch_array($consulta)) {
																		echo '<option value="' . $res2["RFC"] . '">' . $res2["nombre"] . '</option>';
																	}
																} else {
																}
																mysqli_close($conexion);	
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
			</div> <!-- Navbar -->
			<footer class="footer"></footer>
		</div>
	</div>
	<script src="assets/js/core/jquery.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="assets/js/plugins/sweetalert2.min.js"></script>
	<script src="assets/js/plugins/bootstrap-notify.js"></script>
	<script src="assets/js/material-dashboard.js?v=3.2.6"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/block.js"></script>
	<script src="assets/js/moment.js"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/animate/adp.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="assets/js/plugins/tailselect/js/tail.select.min.js"></script>
	<script src="assets/js/plugins/tailselect/lang/tail.select-es.js"></script>
	<script src="assets/js/sesion.js?v=3.2.6"></script>
	<script src="assets/js/reportes.js?v=3.2.6"></script>

</body>

</html>