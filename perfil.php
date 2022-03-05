<?php
include "assets/php/main_admin.php";

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
   <link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"  />
   <link href="assets/js/plugins/izitoast/css/iziToast.css" rel="stylesheet" />
   <link href="assets/css/animate.css" rel="stylesheet" />
   <link href="assets/css/sweetalert2.min.css?v=3.6.1" rel="stylesheet" />
   <link href="assets/css/material-dashboard.css?v=3.6.1" rel="stylesheet" />

</head>

<body class="">
   <div class="wrapper ">
      <div class="sidebar" data-color="purple" data-background-color="white" >
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

               <li id="link1" class="nav-item active">
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
                  echo '<li class="nav-item">
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
               if (rol() == 2) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Nóminas</p>
						</a>
					</li>';
               } else {
                  echo '<li class="nav-item">
							<a class="nav-link" href="./consultar">
								<i class="material-icons">text_snippet</i>
								<p>Nóminas</p>
							</a>
						</li>';
               }
               ?>

               <?php
               if (rol() != 1) {
                  echo '<li class="nav-item">
						<a class="nav-link" href="#" onclick="no_pasar();">
							<i class="material-icons">lock</i>
							<p>Catálogos</p>
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
                                <?php echo nombre_periodo(); ?>
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
            <div id="msn-caja" class="container-fluid msn-caja mt-5">
               <div class="row">
                  <div class="col-xl-4">
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
                                          <input id="nombre" type="text" name="nombre" required class="campo" value="<?php echo $nombre ?>">
                                    </div>

                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                    <label class="select-etiqueta">Correo</label>
                                          <input id="email" type="email" class="campo" required name="email" value="<?php echo $email ?>">
                                    </div>

                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                    <label class="select-etiqueta">Teléfono</label>
                                          <input id="telefono" class="campo" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 required name="telefono" value="<?php echo $telefono ?>">
                                    </div>

                                 </div>
                           </div>
                           <div class="card-footer text-center">
                           <button type="submit" id="editar" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="col-xl-4">
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
                                          <input type="password" name="pass" id="pass" class="campo" required autocomplete="off">
                                 </div>
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Nueva contraseña</label>
                                          <input type="password" name="newPass" id="newPass" class="campo" required autocomplete="off">
                                 </div>
                                 <div class="col-md-12">
                                 <label class="select-etiqueta">Confirmar contraseña</label>
                                          <input type="password" name="confirmacion" id="confirmacion" class="campo" required autocomplete="off">
                                 </div>
                              </div>
                             
                           </div>
                           <div class="card-footer text-center">
                             <button type="submit" class="btn btn-success btn-sm btn-sm"><i class="material-icons">save</i> Guardar</button>
                             </div>
                        </form>
                     </div>
                  </div>

                  <div class="col-xl-4">
                     <div class="row">
                        <div class="col-md 12">
                           <!-- <div class="card">
                              <div class="card-body">
                                 <h5>Eliminar <a href="consultar" class="text-info">archivos CFDI</a>.</h5>
                                 <p>Eliminar todos los archivos CFDI de la base de datos. Esto no eliminará la
                                    lista de empleados.</p>
                                 <?php
                                 if (rol() == 1) {
                                    echo '<div id="eliminar_nominas" class="btn btn-danger btn-sm regresar" style="float:right;"><i class="material-icons">delete_sweep</i> Proceder y eliminar </div>';
                                 } else {
                                    echo '<div onclick="no_pasar();" class="btn btn-danger btn-sm regresar" style="float:right;"><i class="material-icons">delete_sweep</i> Proceder y eliminar </div>';
                                 }
                                 ?>
                              </div>
                           </div> -->
                           <div class="card">
                              <div class="card-body">
                                 <h5>Manual de usuario.</span></h5>
                                 <p>Descarga el manual usuario.</p>
                                 <div class="text-right w-100">
                                    <a href="./documentos/usuario.pdf" download class="btn btn-primary btn-sm px-3"><i class="material-icons">download</i> Descargar</a>
                                 </div> 
                              </div>
                           </div>
                           <div class="card">
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
   <script src="assets/js/core/jquery.min.js"></script>
   <script src="assets/js/core/popper.min.js"></script>
   <script src="assets/js/core/bootstrap-material-design.min.js"></script>
   <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
   <script src="assets/js/plugins/sweetalert2.min.js"></script>
   <script src="assets/js/plugins/bootstrap-notify.js"></script>
   <script src="assets/js/material-dashboard.js?v=3.6.1" type="text/javascript"></script>
   <script src="assets/js/plugins/izitoast/js/iziToast.js"></script>
   <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
	<script src="assets/js/sesion.js?v=3.6.1"></script>
   <script src="assets/js/perfil.js?v=3.6.1"></script>
   <script src="assets/js/block.js"></script>

</body>

</html>