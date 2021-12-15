<?php
session_start();
$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];

if ($varUser == null || $varUser == '' || $varCateg == "user") {
   header("location: /");
}

include "./assets/php/rol.php";
$rol = rol();

$conexion = conexion();

$consultaUsuario = "SELECT * FROM Usuario WHERE RFC = '" . $varUser . "'";

$resultadoUsuario = mysqli_query($conexion, $consultaUsuario);

$telefono = "";
$email = "";

if ($resultadoUsuario) {
   $resUser = mysqli_fetch_array($resultadoUsuario);

   if ($resUser) {
      $nombre = $resUser[6];
      $email = $resUser[3];
      $telefono = $resUser[4];
   }
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
   <link href="assets/css/material-dashboard.css?v=3.1.4" rel="stylesheet" />
   <link href="assets/css/animate.css" rel="stylesheet" />
</head>

<body class="">
   <div class="wrapper ">
      <div class="sidebar" data-color="purple" data-background-color="white" data-image="assets/img/sidebar-1.png?v=1.0.0">
         <div class="logo">
            <a class="simple-text logo-normal">
               <img src="assets/img/logo.svg?v=1.0.0" id="logo1">
            </a>
            <div class="simple-text municipio">Municipio de Yuriria</div>
         </div>
         <div class="sidebar-wrapper">
            <ul class="nav">
               <li class="nav-item">
                  <a class="nav-link" href="./registrar">
                     <i class="material-icons">people</i>
                     <p>Empleados</p>
                  </a>
               </li>

               <li id="link1" class="nav-item active">
                  <a class="nav-link" href="./perfil">
                     <i class="material-icons">person</i>
                     <p>Perfil</p>
                  </a>
               </li>
               <?php
               if ($rol == 2) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Archivo</p>
						</a>
					</li>';
               } else {
                  echo '<li class="nav-item">
							<a class="nav-link" href="./subir">
								<i class="material-icons">cloud_upload</i>
								<p>Archivo</p>
							</a>
						</li>';
               }
               ?>
               <?php
               if ($rol == 2) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Nóminas</p>
						</a>
					</li>';
               } else {
                  echo '<li class="nav-item">
							<a class="nav-link" href="./consultar">
								<i class="material-icons">content_paste</i>
								<p>Nóminas</p>
							</a>
						</li>';
               }
               ?>

               <?php
               if ($rol != 1) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Catálogos</p>
						</a>
					</li>';
               } else {
                  echo '<li class="nav-item">
							<a class="nav-link" href="./configuracion">
								<i class="material-icons">build</i>
								<p>Catálogos</p>
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
               if ($rol != 1) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Reportes</p>
						</a>
					</li>';
               } else {
                  echo '<li class="nav-item">
							<a class="nav-link" href="./reportes">
								<i class="material-icons">insert_drive_file</i>
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
         <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
               <div class="navbar-wrapper">
                  <a class="navbar-brand" href="">Editar Perfil</a>
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
            <div id="barra"></div>
            <div id="msn-caja" class="container-fluid msn-caja">
               <div class="row">
                  <div class="col-xl-4">
                     <div class="card card-profile cajas">
                        <div class="card-avatar">
                           <div id="subir">
                              <img id="foto" class="img" src="assets/img/user.svg" />
                           </div>
                           <input type="file" id="archivo" accept=".jpg, .png, .jpeg" style="display:none">
                        </div>
                        <form id="form-user">
                           <div class="card-body">
                              <h6 class="card-category text-gray">Mi perfil</h6>
                              <div class="formulario2">
                                 <div class="row">

                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label class="bmd-label-floating">Nombre</label>
                                          <input id="nombre" type="text" name="nombre" required class="form-control" value="<?php echo $nombre ?>">
                                       </div>
                                    </div>

                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label class="bmd-label-floating">Correo</label>
                                          <input id="email" type="email" class="form-control" required name="email" value="<?php echo $email ?>">
                                       </div>
                                    </div>

                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label class="bmd-label-floating">Teléfono</label>
                                          <input id="telefono" class="form-control" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 required name="telefono" value="<?php echo $telefono ?>">
                                       </div>
                                    </div>

                                 </div>
                                 <button type="submit" id="editar" class="btn btn-primary regresar"><i class="material-icons">save</i> Guardar </button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="col-xl-4">
                     <div class="card card-profile cajas">
                        <div class="card-header card-header-primary">
                           <h4 class="card-title ">CONTRASEÑA</h4>
                           <p class="card-category">Edita tu contraseña aquí</p>
                        </div>
                        <form id="form-cambiar">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <div class="formulario">
                                          <label class="bmd-label-floating">Contraseña actual</label>
                                          <input type="password" name="pass" id="pass" class="form-control" required autocomplete="off">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <div class="formulario">
                                          <label class="bmd-label-floating">Nueva contraseña</label>
                                          <input type="password" name="newPass" id="newPass" class="form-control" required autocomplete="off">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <div class="formulario">
                                          <label class="bmd-label-floating">Confirmar contraseña</label>
                                          <input type="password" name="confirmacion" id="confirmacion" class="form-control" required autocomplete="off">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <button type="submit" class="btn btn-primary regresar"><i class="material-icons">save</i>
                                 Guardar </button>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="col-xl-4">
                     <div class="row">
                        <div class="col-md 12">
                           <div class="card">
                              <div class="card-body">
                                 <h5>Eliminar <a href="consultar" class="text-info">archivos de nómina</a>.</h5>
                                 <p>Eliminar todos los archivos de nómina de la base de datos. Esto no eliminará la
                                    lista de empleados.</p>
                                 <?php
                                 if ($rol == 1) {
                                    echo '<div id="eliminar_nominas" class="btn btn-danger btn-sm regresar" style="float:right;"><i class="material-icons">delete_sweep</i> Proceder y eliminar </div>';
                                 } else {
                                    echo '<div onclick="no_pasar();" class="btn btn-danger btn-sm regresar" style="float:right;"><i class="material-icons">delete_sweep</i> Proceder y eliminar </div>';
                                 }
                                 ?>
                              </div>
                           </div>
                           <div class="card">
                              <div class="card-body">
                                 <h5>Manuales.</span></h5>
                                 <p>Descarga el manual de administrador y el manual de usuario.</p>
                                 <a href="./documentos/usuario.pdf" download class="btn btn-primary regresar btn-sm px-3"><i class="material-icons">download</i> Usuario</a>
                                 <a href="./documentos/administrador.pdf" download class="btn btn-primary regresar btn-sm px-3"><i class="material-icons">download</i> Administrador</a>

                              </div>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
         </div>

         <footer class="footer">
            <div class="chat_fondo"></div>
            <div class="chat">
               <i class="material-icons">chat</i>
            </div>

            <div class="chat_caja">
               <div class="chat_cerrar">x</div>
               <div class="chat_cuerpo"></div>
               <div class="chat_input">
                  <textarea id="chat-input" placeholder="Escribe tu mensaje" rows="1"></textarea>
                  <i class="material-icons text-success chat_enviar">send</i>
               </div>
            </div>
         </footer>
      </div>
   </div>
   <!--   Core JS Files   -->
   <script src="assets/js/core/jquery.min.js"></script>
   <script src="assets/js/core/popper.min.js"></script>
   <script src="assets/js/core/bootstrap-material-design.min.js"></script>
   <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
   <!-- Plugin for the momentJs  -->
   <script src="assets/js/plugins/moment.min.js"></script>
   <!--  Plugin for Sweet Alert -->
   <link href="assets/css/sweetalert2.min.css?v=3.1.4" rel="stylesheet" />
   <script src="assets/js/plugins/sweetalert2.min.js"></script>
   <!-- Forms Validations Plugin -->
   <script src="assets/js/plugins/jquery.validate.min.js"></script>
   <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
   <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
   <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
   <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
   <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
   <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>

   <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
   <script src="assets/js/plugins/fullcalendar.min.js"></script>
   <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
   <script src="assets/js/plugins/jquery-jvectormap.js"></script>
   <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
   <script src="assets/js/plugins/nouislider.min.js"></script>

   <!-- Library for adding dinamically elements -->
   <script src="assets/js/plugins/arrive.min.js"></script>
   <!-- Chartist JS -->

   <!--  Notifications Plugin    -->
   <script src="assets/js/plugins/bootstrap-notify.js"></script>
   <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
   <script src="assets/js/material-dashboard.js?v=3.1.4" type="text/javascript"></script>
   <script src="assets/js/sesion.js?v=3.1.4"></script>
   <script src="assets/js/perfil.js?v=3.1.4"></script>
   <script src="assets/js/mensajes.js?v=3.1.4"></script>
   <script src="assets/js/block.js"></script>

</body>

</html>