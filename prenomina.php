<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if (rol() == 2) {
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
	<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	
	<link href="assets/js/plugins/izitoast/css/iziToast.css" rel="stylesheet" />
	<link href="assets/css/material-dashboard.css?v=3.4.6" rel="stylesheet" />
	<link href="assets/js/plugins/datatables/datatables.min.css"rel="stylesheet" type="text/css"/>
	<link href="assets/css/datepicker.min.css" rel="stylesheet" />
	<link href="assets/css/sweetalert2.min.css?v=3.4.6" rel="stylesheet" />
	<link href="assets/js/plugins/animate/adp.css">
	<link href="assets/js/plugins/tailselect/css/default/tail.select-light.css" rel="stylesheet" type="text/css">
	<link href="assets/css/animate.css" rel="stylesheet" rel="stylesheet" type="text/css"/>

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
					<li class="nav-item">
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
					<?php
					 if (rol() == 2) {
						echo '<li class="nav-item">
							  <a class="nav-link" href="#" onclick="no_pasar();">
								  <i class="material-icons">lock</i>
								  <p>Prenómina</p>
							  </a>
						  </li>';
					 } else {
						echo '<li id="link1" class="nav-item active">
							  <a class="nav-link" href="./prenomina">
							  <i class="material-icons">receipt_long</i>
							  <p>Prenómina</p>
						   </a>
						</li>';
					 }
					if (rol() == 2) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Impotar CFDI</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
							<a class="nav-link" href="./subir">
								<i class="material-icons">cloud_upload</i>
								<p>Impotar CFDI</p>
							</a>
						</li>';
					}
					?>

					<li class="nav-item">
						<a class="nav-link" href="./consultar">
							<i class="material-icons">text_snippet</i>
							<p>Nóminas</p>
						</a>
					</li>
					<?php
					if (rol() != 1) {
						echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Catálogos</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Plazas</p>
						</a>
					</li>';
					} else {
						echo '<li class="nav-item">
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
								<i class="material-icons">summarize</i>
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
			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="">Prenómina</a>
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
			<div class="content">
				<div id="msn-caja" class="container-fluid msn-caja">
				
					<div class="prenominas"></div>


				</div>
			</div>

			<div class="modal" id="modal" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title modal_titulo text-primary negrita"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="modal_aceptar">Si</button>
                    </div>
                    </div>
                </div>
            </div>

			<footer class="footer">
			</footer>
		</div>
	</div>
	<script src="assets/js/core/jquery.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="assets/js/plugins/sweetalert2.min.js"></script>
	<script src="assets/js/plugins/bootstrap-notify.js"></script>
	<script src="assets/js/material-dashboard.js" type="text/javascript"></script>
	<script src="assets/js/datepicker.min.js"></script>
	<script src="assets/js/plugins/datepicker.es.js"></script>
	<script src="assets/js/moment.js"></script>
	<script src="assets/js/plugins/izitoast/js/iziToast.js"></script>
	<script src="assets/js/sesion.js?v=3.4.6"></script>
	<script src="assets/js/plugins/datatables/datatables.min.js"></script>
	<script src="assets/js/plugins/tailselect/js/tail.select.min.js"></script>
    <script src="assets/js/plugins/tailselect/lang/tail.select-es.js"></script>
	<script src="assets/js/plugins/animate/adp.js"></script>
	<script src="assets/js/prenomina.js?v=3.4.6"></script>

</body>

</html>