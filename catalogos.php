<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";
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
						<div class="col-xl-12 pagina_1">
							<div class="card">

								<div class="card-body">
									<div class="msn-mostrar">
										<button onclick="nuevo_puesto();" class="btn-mostrar"><i
												class="material-icons">add_circle_outline</i>Nuevo puesto</button>
										<input type="file" id="importar-puestos" accept=".xlsx" /><label
											class="btn-mostrar" for="importar-puestos"><i
												class="material-icons">file_upload</i>Importar</label>
										<button onclick="exportar_puesto();" class="btn-mostrar"><i
												class="material-icons">file_download</i>Exportar</button>
										<button onclick="exportar_puesto_agrupado();" class="btn-mostrar"><i
												class="material-icons">file_download</i>Puestos agrupados</button>
									</div>
								</div>
							</div>
							<div class="table-responsive adp-hide">
								<div class="opciones_tabla">
									<span class="pestana pestana_1 activo">Puestos</span>
									<span class="pestana pestana_2">Departamentos</span>
								</div>
								<table id="tabla-puesto" class="table table-striped" style="width:100%">
									<thead class="text-primary">
										<tr>
											<th class="">#</th>
											<th class="">Puesto</th>
											<th class="">Plazas</th>
											<th class="">Categoría</th>
											<th class="">Editar</th>
											<th class="">Eliminar</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>

						<div class="col-xl-12 pagina_2 adp-hide">
							<div class="card">
								<div class="card-body">
									<div class="msn-mostrar">
										<button onclick="nuevo_departamento();" class="btn-mostrar"><i
												class="material-icons">add_circle_outline</i>Nuevo departamento</button>
										<input type="file" id="importar-departamentos" accept=".xlsx" /><label
											class="btn-mostrar" for="importar-departamentos"><i
												class="material-icons">file_upload</i>Importar</label>
										<button onclick="exportar_depa();" class="btn-mostrar"><i
												class="material-icons">file_download</i>Exportar</button>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<div class="opciones_tabla">
									<span class="pestana pestana_1">Puestos</span>
									<span class="pestana pestana_2 activo">Departamentos</span>
								</div>
								<table id="tabla-departamento" class="table table-striped" style="width:100%">
									<thead class="text-primary">
										<tr>
											<th class="">#</th>
											<th class="">Departamento</th>
											<th class="">Editar</th>
											<th class="">Eliminar</th>
										</tr>
									</thead>
								</table>
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
	<script src="assets/js/catalagos.js?v=3.7.2"></script>
	<script>$("#tab-catalogos").addClass("active");</script>

</body>

</html>