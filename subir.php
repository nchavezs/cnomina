<?php
session_start();

$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];

if ($varUser == null || $varUser == '' || $varCateg == "user") {
	header("location: /");
}

include "./assets/php/rol.php";
$rol = rol();
if ($rol == 2) {
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
	<link href="assets/css/dropzone.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/animate.css">
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
					<li class="nav-item">
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
					<li id="link1" class="nav-item active">
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
					<?php
					if ($rol != 1) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Catálogos</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./configuracion">
								<i class="material-icons">build</i>
								<p>Catálogos</p>
							</a>
						</li>';
					}
					?>
					<li id="link2" class="nav-item">
						<a class="nav-link" href="./mensajes">
							<i class="material-icons">message</i>
							<p>Mensajes</p>
						</a>
					</li>
					<?php
					if ($rol != 1) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Reportes</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./reportes">
								<i class="material-icons">insert_drive_file</i>
								<p>Reportes</p>
							</a>
						</li>';
					}
					?>
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
						<a class="navbar-brand" href="">Subir recibos de nómina</a>
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
					<div class="row">
						<div class="col-md-8">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-2">
											<div class="toggle-btn">
												<input id="check1" type="checkbox" class="cb-value" />
												<span class="round-btn"></span>
											</div>
										</div>
										<div class="col-10">
											<h5>Registrar trabajadores al subir <a href="consultar" class="text-info">archivos de nómina</a>.</h5>
											<p>Los datos del trabajador con número de empleado ya registrado no serán sobreescritos.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="drop-fondo">
								<form action="assets/php/subir.php" class="dropzone" id="myAwesomeDropzone" enctype="multipart/form-data">
									<div class="dz-message" data-dz-message>
										<div class="row">
											<div class="col-md-4 drop-icon">
												<i id="drop-icon" class="material-icons">cloud_upload</i>
											</div>
											<div id="drop-text" class="col-md-6">
												<h2>Seleccionar archivo PDF</h2>
												<h4>o suelta los documentos PDF aquí.</h4>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="col-md-12">
							<div id="enviar"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="p-5"></div>


			<footer class="footer">
				<div class="chat_fondo"></div>
				<div class="chat">
					<i class="material-icons">chat</i>
				</div>

				<div class="chat_caja">
					<div class="chat_cerrar">x</div>
					<div class="chat_cuerpo"></div>
					<div class="chat_input">
						<textarea id="chat-input" placeholder="Escribe tu mensaje" rows="1"></textarea>
						<i class="material-icons text-success chat_enviar">send</i>
					</div>
				</div>
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
	<script src="assets/js/sesion.js?v=3.1.4"></script>
	<script src="assets/js/dropzone.js"></script>
	<script src="assets/js/subir.js?v=3.1.4"></script>
	<script src="assets/js/mensajes.js?v=3.1.4"></script>

</body>

</html>