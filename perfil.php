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
		<?php include "assets/layouts/sidebar.php";?>
		<div class="main-panel">
			<?php include "assets/layouts/navbar.php"?>

			<div class="content">
				<div class="container-fluid">
            <div class="row">
               <div class="col-xl-4 mt-4">
                  <div class="card card-profile">
                     <div class="card-avatar">
                        <div id="subir">
                           <img id="foto" class="img" src="<?php echo $foto ?>" />
                        </div>
                        <input class="hidden" type="file" id="archivo" accept="image/jpg, image/png, image/jpeg">
                     </div>
                     <form  class="text-left px-3" id="form-user">
                        <div class="card-body">
                           <h6 class="card-category text-gray text-center">Mi perfil</h6>
                           <div class="row">
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Nombre</label>
                                       <input maxlength="20" minlength="3" id="nombre" type="text" name="nombre" required class="campo" value="<?php echo $varName ?>">
                                 </div>

                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Correo</label>
                                       <input id="email" type="email" class="campo" required name="email" value="<?php echo $varEmail ?>">
                                 </div>

                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Teléfono</label>
                                       <input id="telefono" class="campo" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 required name="telefono" value="<?php echo $varTel ?>">
                                 </div>

                              </div>
                        </div>
                        <div class="card-footer text-center">
                        <button type="submit" id="editar" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
                        </div>
                     </form>
                  </div>
               </div>

               <div class="col-xl-4 mt-4">
                  <div class="card card-profile">
                        <div class="card-avatar">
                           <div id="subir">
                              <img id="foto" class="img" src="assets/img/search.svg" />
                           </div>
                        </div>
                        <form class="text-left px-3" id="form-cambiar">
                           <div class="card-body">

                           <h6 class="card-category text-gray text-center">Cambiar contraseña</h6>
                              <div class="row">
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Contraseña actual</label>
                                          <input type="password" maxlength="20" minlength="3" name="pass" id="pass" class="campo" required autocomplete="off">
                                 </div>
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Nueva contraseña</label>
                                          <input type="password" maxlength="20" minlength="3" name="newPass" id="newPass" class="campo" required autocomplete="off">
                                 </div>
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Confirmar contraseña</label>
                                          <input type="password" maxlength="20" minlength="3" name="confirmacion" id="confirmacion" class="campo" required autocomplete="off">
                                 </div>
                              </div>

                           </div>
                           <div class="card-footer text-center">
                           <button type="submit" class="btn btn-success btn-sm btn-sm"><i class="material-icons">save</i> Guardar</button>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="col-xl-4 pt-2">
                     <!-- <div class="card">
                              <div class="card-body">
                                 <h5>Manual de usuario.</span></h5>
                                 <p>Descarga el manual usuario.</p>
                                 <div class="text-right w-100">
                                    <a href="./documentos/usuario.pdf" download class="btn btn-primary btn-sm px-3"><i class="material-icons">download</i> Descargar</a>
                                 </div>
                              </div>
                           </div> -->
                     <div class="card mt-5">
                        <div class="card-body">
                           <h5>Manual de administrador.</span></h5>
                           <p>Descarga el manual de administrador.</p>
                           <div class="text-right w-100">
                              <a href="./documentos/administrador.pdf" download class="btn btn-primary btn-sm px-3"><i class="material-icons">download</i> Descargar</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

   <?php include 'assets/layouts/scripts.php'?>
   <script src="assets/js/perfil.js?v=3.9.0"></script>

   <script>$("#tab-perfil").addClass("active");</script>

</body>
</html>