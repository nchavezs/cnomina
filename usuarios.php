<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";


if(!in_array(13, rol())){
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
				<div class="card">
                        <div class="card-body">
                            <div class="msn-mostrar">
								<?php
								if(in_array( 17, rol())){	
									$crear = 'nuevo_rol();';
								}else{
									$crear = 'bloqueo();';
								}

								if(in_array( 18, rol())){	
									$editar = 'editar_rol_select();';
								}else{
									$editar = 'bloqueo();';
								}

								if(in_array( 19, rol())){	
									$eliminar = 'eliminar_rol_select();';
								}else{
									$eliminar = 'bloqueo();';
								}



								if(in_array( 14, rol())){
									echo '
									<label onclick="nuevo_usuario();" class="btn-mostrar">
										<i class="material-icons">person_add_alt_1</i>Nuevo usuario
									</label>
									';
								}else{
									echo '
									<label onclick="bloqueo();" class="btn-mostrar">
										<i class="material-icons">person_add_alt_1</i>Nuevo usuario
									</label>
									';
								}

								echo '
								<span class="dropdown">
									<div class="btn-mostrar" data-toggle="dropdown">
										<i class="material-icons">data_saver_on</i>Roles de usuario
									</div>
									<div class="dropdown-menu">
										<label onclick="'.$crear.'" class="dropdown-item"> <i class="material-icons">check</i> Nuevo rol de usuario</label>
										<label onclick="'.$editar.'" class="dropdown-item"> <i class="material-icons">check</i> Editar rol de usuario</label>
										<label onclick="'.$eliminar.'" class="dropdown-item"> <i class="material-icons">check</i> Eliminar rol de usuario</label>	
									</div>
								</span>
								';

								
								?>
								
                            </div>
                        </div>
                    </div>

					<div class="row usuarios_caja mt-3">
						<?php include 'assets/layouts/usuarios_loading.php'?>
					</div>
				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

	<?php include 'assets/layouts/scripts.php' ?>
	<script src="assets/js/usuarios.js?v=3.7.9"></script>

	<script>
		$("#tab-usuarios").addClass("active");
	</script>

</body>

</html>