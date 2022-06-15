<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(24, rol())){
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

					<div class="row">
						<div class="col-md-12 col-xl-8">
						<?php
						if(in_array(25, rol())){
							echo '
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
											<h5>Registrar trabajadores al subir <a href="consultar"
													class="text-info">archivos CFDI</a>.</h5>
											<p class="my-0">Los datos del empleado ya registrados no serán
												sobreescritos.</p>
											<p class="my-0">No se registrarán plazas ni fecha de inicio laboral (en caso
												de no aparecer en el CFDI).</p>
										</div>
									</div>
								</div>
							</div>
							';
						}
						?>
							
						</div>
						<div class="col-md-12">
							<div class="card">
								<form action="assets/php/subir.php" class="dropzone" id="myAwesomeDropzone"
									method="POST" enctype="multipart/form-data">
									<div class="dz-message">
										<div class="row">
											<div class="col-md-4"><img src="assets/img/upload.svg" alt=""></div>
											<div class="col-md-8">
												<h2 class="negrita mt-3">Selecciona tus archivos CFDI</h2>
												<div>
													<p>Arrastra tus archivos en formato <span
															class="text-danger">PDF</span> aquí o búscalos para
														cargarlos.</p>
												</div>
												<button type="button" class="btn btn-sm btn-success mt-4">Seleccionar
													CDFI</button>
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

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

	<?php include 'assets/layouts/scripts.php' ?>
	<script src="assets/js/subir.js?v=3.7.3"></script>
	<script>$("#tab-subir").addClass("active");</script>

</body>

</html>