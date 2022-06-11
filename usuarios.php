<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";


if(!in_array(14, rol())){
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
								<label onclick="nuevo_usuario();" class="btn-mostrar">
                                    <i class="material-icons">add_circle_outline</i>Nuevo empleado
                                </label>
                            </div>
                        </div>
                    </div>

					<div class="row usuarios_caja mt-3"></div>
				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

	<?php include 'assets/layouts/scripts.php' ?>
	<script src="assets/js/usuarios.js?v=3.7.2"></script>

	<script>
		$("#tab-usuarios").addClass("active");
	</script>

</body>

</html>