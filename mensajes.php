
<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(40, rol())){
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
						<p>Puedes enviar y recibir mensajes en conversaciones con los usuarios.</p>
						<hr>
						<p>No dudes en ponerte en contacto con nosotros diréctamente desde <span class="text-warning soporte" onclick="soporte();">aquí</span> en caso de que tengas alguna duda o sugerencias o envianos un correo a <a class="text-primary" href="mailto:info@consultanominacomonfort.com">info@consultanominacomonfort.com</a> .</p>
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
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

   <?php include 'assets/layouts/scripts.php' ?>
   <script src="assets/js/mensajes.js?v=3.7.4"></script>
   <script>$("#tab-mensajes").addClass("active");</script>

</body>
</html>