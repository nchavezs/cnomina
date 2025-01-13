<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(26, rol())){
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
		<?php include "assets/layouts/sidebar.php";?>
		<div class="main-panel">
			<?php include "assets/layouts/navbar.php"?>

			<div class="content">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">
							<div class="msn-mostrar">
							<?php
							if(in_array(27 , rol())){
								echo '<button onclick="eliminar_periodo();" class="btn-mostrar"><i class="material-icons">delete</i>Eliminar por periodo</button>';
							}else{
								echo '<button onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">delete</i>Eliminar por periodo</button>';
							}
							if(in_array(24 , rol())){
								echo '<a href="./subir" class="btn-mostrar"><i class="material-icons">file_upload</i>Impotar CFDI</a>';
							}
							?>
							</div>
						</div>
					</div>
					<div class="table-responsive adp-hide">
						<div class="opciones_tabla">
							<!-- <select id="ano">
								<option value="2022">2022</option>
								<option value="2023">2023</option>
								<option value="2024">2024</option>
								<option value="2025">2025</option>
							</select> -->
							<select id="id_periodo">
								<?php
									$conexion = conexion();
									$sql = "SELECT * FROM Periodo";
									$consulta = $conexion->query($sql);
									while($res = mysqli_fetch_array($consulta)){
										echo '<option value="'.$res[0].'">'.$res[1].'</option>';
									}
								?>
							</select>
						</div>
						<table id="tabla-nominas" class="table table-striped" style="width:100%">
							<thead class="text-primary">
								<tr>
									<th class="">Nombre</th>
									<th class="">Periodo</th>
									<th class="oculto">Dias de pago</th>
									<th class="">Detalle</th>
									<th class="">Eliminar</th>
								</tr>
							</thead>
						</table>
					</div>

				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

	<?php include 'assets/layouts/scripts.php'?>
	<script src="assets/js/consultar.js?v=4.1"></script>
	<script>$("#tab-nominas").addClass("active");</script>

</body>

</html>