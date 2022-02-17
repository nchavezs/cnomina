<?php
include "assets/php/main_admin.php";
include "./assets/php/comprobar_catalago.php";
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans"/>
    
    <link rel="stylesheet" href="assets/js/plugins/datatables/datatables.min.css"/>
    <link rel="stylesheet" href="assets/css/material-dashboard.css?v=3.4.3"/>
    <link rel="stylesheet" href="assets/css/animate.css"/>
    <link rel="stylesheet" href="assets/css/datepicker.min.css"/>
    <link rel="stylesheet" href="assets/js/plugins/tailselect/css/default/tail.select-light.css">
    <link rel="stylesheet" href="assets/js/plugins/animate/adp.css">
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css?v=3.4.3"/>

</head>

<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="white">
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
                    <li id="link1" class="nav-item active">
                        <a class="nav-link" href="./registrar">
                            <i class="material-icons">people</i>
                            <p>Empleados</p>
                        </a>
                    </li>
                    <li class="nav-item ">
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
                        <a class="navbar-brand" href="">Lista de empleados</a>
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
                <div id="msn-caja" class="container-fluid msn-caja">
                    <div class="card">
                        <!-- <div class="card-header text-center">
                            <h6 class="card-category text-gray">Empleados</h6>
                        </div> -->
                        <div class="card-body">
                            <div class="msn-mostrar">
                                <?php
                                if (rol() == 1) {
                                    echo '<button id="nuevo-empleado" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>
											<div class="">Nuevo empleado</div>
                                        </button>
                                        <label onclick="generar_empleados();" class="btn-mostrar">
                                            <i class="material-icons">file_download</i>Exportar
                                        </label>

                                        <input type="file" id="importar-empleado" accept=".xlsx" />
                                        <input type="file" id="importar-empleado-puesto" accept=".xlsx" />
                                        
                                        <span class="dropdown">
                                            <div class="btn-mostrar" data-toggle="dropdown">
                                                <i class="material-icons">upload</i>Importar
                                            </div>
                                            <div class="dropdown-menu">
                                                <label class="dropdown-item" for="importar-empleado"> <i class="material-icons">check</i> Por # de plaza</label>
                                                <label class="dropdown-item" for="importar-empleado-puesto">  <i class="material-icons">check</i> Por puesto y departamento</label>
                                            </div>
                                        </span>
                                        <span class="dropdown">
                                            <div class="btn-mostrar" data-toggle="dropdown">
                                                <i class="material-icons">line_style</i>Plantilla
                                            </div>
                                            <div class="dropdown-menu">
                                                <a href="assets/docs/empleado/importar/empleado.xlsx" class="dropdown-item"> <i class="material-icons" >check</i>Por # de plaza</a>
                                                <a href="assets/docs/empleado/actualizar/empleado.xlsx" class="dropdown-item"> <i class="material-icons" >check</i>Por puesto y departamento</a>
                                            </div>
                                        </span>
                                    ';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive adp-hide">
                        <div class="opciones_tabla">
                            <select id="estado">
                                <option value="alta">ALTA</option>
                                <option value="baja">BAJA</option>
                            </select>
                        </div>
                        <table id="tabla-empleado" class="table table-striped" style="width:100%">
                            <thead class="text-primary">
                                <tr>
                                    <th class=""># Empleado</th>
                                    <th class="">Nombre</th>
                                    <th class="oculto">RFC</th>
                                    <th class="oculto">Departamento</th>
                                    <th class="oculto">Trabajador</th>
                                    <th class="">Editar</th>
                                    <th class="">Eliminar</th>
                                </tr>
                            </thead>
                        </table>
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
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/material-dashboard.js?v=3.4.3" type="text/javascript"></script>
    <script src="assets/js/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/datepicker.min.js"></script>
    <script src="assets/js/plugins/datepicker.es.js"></script>
    <script src="assets/js/block.js"></script>
    <script src="assets/js/plugins/tailselect/js/tail.select.min.js"></script>
    <script src="assets/js/plugins/tailselect/lang/tail.select-es.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/plugins/animate/adp.js"></script>
    <script src="assets/js/registrar.js?v=3.4.3"></script>
    <script src="assets/js/sesion.js?v=3.4.3"></script>

</body>

</html>