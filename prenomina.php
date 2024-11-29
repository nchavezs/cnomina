<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(20, rol())){
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
					<div class="prenominas"></div>
				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

   <?php include 'assets/layouts/scripts.php' ?>
   <script src="assets/js/prenomina.js?v=4.0"></script>
   <script>$("#tab-prenomina").addClass("active");</script>

</body>
</html>