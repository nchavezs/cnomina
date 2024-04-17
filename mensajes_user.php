<?php
include "assets/php/main_user.php";
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
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
	
	<link href="assets/css/select.css" rel="stylesheet" />
	<link type="text/css" rel="stylesheet" href="assets/js/plugins/animate/adp.css">
	<link href="assets/css/animate.css" rel="stylesheet" />
	<link href="assets/css/sweetalert2.min.css?v=3.9.5" rel="stylesheet" />
	<link href="assets/js/plugins/izitoast/css/iziToast.css" rel="stylesheet" />
	<link href="assets/css/material-dashboard.css?v=3.9.5" rel="stylesheet" />

</head>

<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="purple" data-background-color="white">
		 <div class="municipio">MUNICIPIO DE <?php echo get_municipio() ?></div>
            <div class="avatar">
                <?php
				$foto = "assets/img/user.png";
				if ($varFoto != null) {
					$foto = $varFoto;
				}

				?>
                <a href="./perfil"><img src="<?php echo $foto ?>"></a>
                <!-- <p class="logo_titulo">MUNICIPIO DE <?php echo get_municipio() ?></p> -->
                <p><?php echo $varName ?></p>
                <a href="mailto:"><?php echo $varEmail ?></a>
            </div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li class="nav-item ">
						<a class="nav-link" href="./tablas">
							<i class="material-icons">text_snippet</i>
							<p>CFDI</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="./perfil_user">
							<i class="material-icons">person_pin</i>
							<p>Perfil</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./movimientos">
							<i class="material-icons">transfer_within_a_station</i>
							<p>Movimientos</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="./descuentos">
							<i class="material-icons">trending_down</i>
							<p>Descuentos</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./vacaciones">
							<i class="material-icons">flight</i>
							<p>Vacaciones</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./permisos">
							<i class="material-icons">description</i>
							<p>Permisos</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./beneficiarios">
							<i class="material-icons">people</i>
							<p>Beneficiarios</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./pases">
							<i class="material-icons">people</i>
							<p>Pases</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./gastos">
							<i class="material-icons">healing</i>
							<p>G. Médicos</p>
						</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="./mensajes_user">
							<i class="material-icons">message</i>
							<p>Mensajes</p>
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
			<nav class="navbar navbar-expand-lg  navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="">Mensajes</a>
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
			<div class="mensajeria">
					<div class="mensajeria_panel">
						<div class="mensajeria_busqueda_caja">
							<div class="mensajeria_busqueda"> <input type="text" placeholder="Buscar usuario ..."> <i class="material-icons">search</i> </div>
						</div>
						<div class="mensajeria_contactos"></div>
					</div>
					<div class="mensajeria_vacio">
						<img src="assets/img/chat.svg" alt="">
						<h2 class="text-primary my-4 negrita">Inicia una conversación</h2>
						<p>Envía y recibe mensajes, cualquier duda o aclaración, comunícate con tu administrador.</p>
						<!-- <hr> -->
						<!-- <p>No dudes en ponerte en contacto con nosotros diréctamente desde <span class="text-warning soporte" onclick="soporte();">aquí</span> en caso de que tengas alguna duda o sugerencias o envianos un correo a <a class="text-primary" href="mailto:info@consultanominacomonfort.com">info@consultanominacomonfort.com</a> .</p> -->
					</div>
					<div class="mensajeria_caja adp-hide">
						<div class="mensajeria_usuario"><i class="material-icons regresar">keyboard_backspace</i> <img src="assets/img/user.png" alt=""><span></span></div>
						<div class="mensajeria_chat"></div>
						<div class="mensajeria_enviar">
							<textarea placeholder="Escribe tu mensaje ..." maxlength="500" rows="1"></textarea>
							<input type="file" id="file" class="hide" accept=".xls,.xlsx,.doc,.docx,.pdf,.zip,.rar,image/*">
							<i class="material-icons" onclick="archivo();">attach_file</i>
							<i class="material-icons" onclick="enviar();">send</i>
						</div>
					</div>
				</div>
			</div>

			<footer class="footer">
				<div class="container-fluid">

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
	<script src="assets/js/material-dashboard.js?v=3.9.5" type="text/javascript"></script>
	<script src="assets/js/block.js"></script>
	<script src="assets/js/plugins/animate/adp.js"></script>
	<script src="assets/js/plugins/izitoast/js/iziToast.js"></script>
	<script src="assets/js/sesion.js?v=3.9.5"></script>
	<script src="assets/js/mensajes-user.js?v=3.9.5"></script>

</body>

</html>