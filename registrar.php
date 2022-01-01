<?php
include "assets/php/main_admin.php";
include "./assets/php/comprobar_catalago.php";
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
	<link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet" />
	<link href="assets/css/datepicker.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/js/plugins/tailselect/css/default/tail.select-light.css">
	<link href="assets/css/sweetalert2.min.css?v=3.1.4" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />

</head>

<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="purple" data-background-color="white" data-image="assets/img/sidebar-1.png?v=1.0.0">
			<div class="logo">
				<a class="simple-text logo-normal">
					<img src="assets/img/<?php echo get_logo()?>" id="logo1">
				</a>
				<div class="text-center municipio">MUNICIPIO DE <?php echo get_municipio();?></div>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li id="link1" class="nav-item active">
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
					<?php
					if (rol() == 2) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Cargar CFDI</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./subir">
								<i class="material-icons">cloud_upload</i>
								<p>Cargar CFDI</p>
							</a>
						</li>';
					}
					?>

					<?php
					if (rol() == 2) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Nóminas</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./consultar">
								<i class="material-icons">content_paste</i>
								<p>Nóminas</p>
							</a>
						</li>';
					}
					?>
					<?php
					if (rol() != 1) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Catálogos</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./catalogos">
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
					if (rol() != 1) {
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
						<a class="navbar-brand" href="">Lista de empleados</a>
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

					<div class="card tabla-empleados">
						<div class="card-header card-header-primary">
							<div class="row">
								<div class="col-md-6">
									<h4 class="card-title ">Lista de empleados</h4>
									<p class="card-category"> Empleados dados de alta</p>
								</div>
								<div class="col-md-6 msn-mostrar botones-tabla">
									<?php
									if (rol() == 1) {
										echo '<button id="nuevo-empleado" class="btn-mostrar"><i class="material-icons">add</i>
											<div class="oculto">Nuevo empleado</div>
												</button>
												<input type="file" id="importar-empleado" accept=".xlsx" /><label
													class="btn-mostrar" for="importar-empleado"><i
														class="material-icons">arrow_upward</i>Importar</label>
														<button id="generar-prenomina" class="btn-mostrar"><i
													class="material-icons">assignment</i>Prenómina</button>
													<button onclick="generar_empleados();" class="btn-mostrar"><i
													class="material-icons">arrow_downward</i>
												<div class="oculto">Exportar</div>
											</button>';
									}
									?>
								</div>
							</div>

						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table id="tabla-empleado" class="table table-striped" style="width:100%">
									<thead class="text-primary">
										<tr>
											<th class="titulo">Detalle</th>
											<th class="titulo">Nombre</th>
											<th class="oculto titulo">RFC</th>
											<th class="oculto titulo">Puesto</th>
											<th class="oculto titulo">Departamento</th>
											<th class="oculto titulo">Estado</th>
											<th class="titulo">Editar</th>
											<th class="titulo">Eliminar</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>

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
	<script src="assets/js/core/jquery.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="assets/js/plugins/sweetalert2.min.js"></script>
	<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
	<script src="assets/js/plugins/bootstrap-notify.js"></script>
	<script src="assets/js/material-dashboard.js?v=3.1.4" type="text/javascript"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/block.js"></script>
	<script src="assets/js/plugins/tailselect/js/tail.select.min.js"></script>
	<script src="assets/js/plugins/tailselect/lang/tail.select-es.js"></script>
	<script src="assets/js/registrar.js?v=3.1.4"></script>
	<script src="assets/js/sesion.js?v=3.1.4"></script>
	<script src="assets/js/mensajes.js?v=3.1.4"></script>
	<script src="assets/js/moment.js"></script>

</body>

</html>