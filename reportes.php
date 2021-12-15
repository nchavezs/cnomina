<?php
session_start();

$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];

if ($varUser == null || $varUser == '' || $varCateg == "user") { // Si el usuario no esta autorizado, no lo deja acceder
	header("location: /");
}

include "./assets/php/rol.php";
$rol = rol();
if ($rol != 1) {
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
	<title>
		Consulta Nómina
	</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="assets/css/material-dashboard.css?v=3.1.4" rel="stylesheet" />
	<link href="assets/css/select2.css?v=3.1.4" rel="stylesheet" />
	<link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet" />
	<link href="assets/css/datepicker.min.css" rel="stylesheet" />

</head>

<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="purple" data-background-color="white" data-image="assets/img/sidebar-1.png?v=1.0.0">
			<div class="logo">
				<a class="simple-text logo-normal">
					<img src="assets/img/logo.svg?v=1.0.0" id="logo1">
				</a>
				<div class="simple-text municipio">Municipio de Yuriria</div>
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
							<i class="material-icons">person</i>
							<p>Perfil</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./subir">
							<i class="material-icons">cloud_upload</i>
							<p>Archivo</p>
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="./consultar">
							<i class="material-icons">content_paste</i>
							<p>Nóminas</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="./configuracion">
							<i class="material-icons">build</i>
							<p>Catálogos</p>
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
							<i class="material-icons">insert_drive_file</i>
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
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="">Reportes</a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="sr-only">Toggle navigation</span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end">
						<ul class="navbar-nav">
							<li class="nav-item dropdown">
								<a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="material-icons">notifications</i>
									<span class="notification noti-numero">0</span>
									<p class="d-lg-none d-md-block">Mensajes</p>
								</a>
								<div class="dropdown-menu dropdown-menu-right noti-caja" aria-labelledby="navbarDropdownMenuLink">
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
				<div id="barra"></div>
				<div id="msn-caja" class="container-fluid msn-caja">



					<div class="col-md-12">
						<div class="caja_magica">
							<div class="caja-todos-empleado">
								<div class="card card-stats">
									<div class="card-header card-header-success card-header-icon carta-dos">
										<div class="card-icon">
											<i class="material-icons">people</i>
										</div>

									</div>

									<div class="card-body estado-todos">
										<div class="row">
											<div class="col-6 reporte-texto">
												<h5>Puesto<i class="material-icons">keyboard_arrow_right</i></h5>
											</div>
											<div class="col-6 reporte-texto">
												<div id="filtro-puesto"></div>
												<input id="puesto" type="input" class="input-reporte" />
											</div>
											<div class="col-6 reporte-texto">
												<h5>Departamento <i class="material-icons">keyboard_arrow_right</i> </h5>
											</div>
											<div class="col-6 reporte-texto">
												<div id="filtro-departamento"></div>
												<input id="departamento" type="input" class="input-reporte" />
											</div>
										</div>
									</div>
									<div class="card-footer">
										<div class="estado-caja">
											<input id="todos_empleado" class="chk" checked type="checkbox" /><label for="todos_empleado">TODOS LOS EMPLEADOS</label>
										</div>
									</div>
								</div>
							</div>

							<div class="caja-por-empleado">
								<div class="card card-stats">
									<div class="card-header card-header-primary apagado2 card-header-icon carta-uno">
										<div class="card-icon">
											<i class="material-icons">person</i>
										</div>
										<div id="nuevo-empleado" class="btn nuevo-empleado-apagado"><i class="material-icons">add</i>Seleccionar empleado </div>
									</div>

									<div class="card-body estado-empleado apagado2">
										<table class="table table-hover">
											<thead class="titulos">
												<th></th>
												<th></th>
												<th></th>

											</thead>
											<tbody class="tabla-temporal">
											</tbody>
										</table>
										<div class="chat-nuevo">
											<i id="chat-icono" class="material-icons">error_outline</i>
											<p>Sin elementos</p>
										</div>
									</div>

									<div class="card-footer">
										<div class="estado-caja">
											<input id="por_empleado" class="chk" type="checkbox" /><label for="por_empleado">POR EMPLEADO</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header-primary">
								<h4 class="card-title">Parámetros</h4>
								<p class="card-category">Selecciona los parámetros a mostrar</p>
							</div>
							<div class="card-body table-responsive">
								<table class="table table-hover centrado">
									<thead class="text-primary">
										<th></th>
										<th class="titulo">Parámetro</th>
										<th class="titulo">Fecha del</th>
										<th class="titulo">Fecha al</th>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check1" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Movimientos</td>
											<td><input id="fecha1" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha2" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>
										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check2" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Descuentos</td>
											<td><input id="fecha3" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha4" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>
										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check3" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Vacaciones</td>
											<td><input id="fecha5" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha6" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>
										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check4" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Permisos con goce</td>
											<td><input id="fecha7" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha8" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>

										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check5" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Permisos sin goce</td>
											<td><input id="fecha9" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha10" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>

										<tr class="no_aplica">
											<td>
												<div class="toggle-btn">
													<input id="check8" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Altas</td>
											<td><input id="fecha13" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha14" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>

										<tr class="no_aplica">
											<td>
												<div class="toggle-btn">
													<input id="check9" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Bajas</td>
											<td><input id="fecha15" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha16" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>

										<tr>
											<td>
												<div class="toggle-btn">
													<input id="check10" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="titulo2">Pases</td>
											<td><input id="fecha17" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td><input id="fecha18" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>


									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="card">
							<div class="card-body table-responsive centrado">
								<table class="table">
									<thead class="text-primary">
										<th class="titulo2">Beneficiarios</th>
										<th class="titulo2">Aplicar a todo</th>
										<th class="titulo">Fecha del</th>
										<th class="titulo">Fecha al</th>
									</thead>
									<tbody>
										<tr>
											<td class="centrado">
												<div class="toggle-btn">
													<input id="check6" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="centrado">
												<div class="toggle-btn">
													<input id="check7" type="checkbox" class="cb-value" />
													<span class="round-btn"></span>
												</div>
											</td>
											<td class="centrado"><input id="fecha11" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
											<td class="centrado"><input id="fecha12" type='text' class="datepicker-here fecha-reporte input-reporte apagado titulo2" readonly /></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="p-5"></div>
			<div class="boton_generar_reporte">
				<div id="generar" class="btn btn-primary regresar"><i class="material-icons">download</i> Generar reporte </div>
			</div>

			<footer class="footer">

			</footer>
		</div>
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

	<!--  Plugin for Sweet Alert -->
	<link href="assets/css/sweetalert2.min.css?v=3.1.4" rel="stylesheet" />
	<script src="assets/js/plugins/sweetalert2.min.js"></script>

	<!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
	<script src="assets/js/plugins/jquery.dataTables.min.js"></script>


	<!-- Chartist JS -->

	<!--  Notifications Plugin    -->
	<script src="assets/js/plugins/bootstrap-notify.js"></script>
	<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="assets/js/material-dashboard.js?v=3.1.4" type="text/javascript"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/block.js"></script>
	<script src="assets/js/reportes.js?v=3.1.4"></script>
	<script src="assets/js/sesion.js?v=3.1.4"></script>
	<script src="assets/js/mensajes.js?v=3.1.4"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

</body>

</html>