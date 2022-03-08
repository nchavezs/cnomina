<?php
include "assets/php/main_admin.php";

if (rol() != 1) {
    header('location:./registrar');
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
    
    <link href="assets/js/plugins/izitoast/css/iziToast.css" rel="stylesheet" />
	<link href="assets/css/material-dashboard.css?v=3.6.3" rel="stylesheet" />
    <link href="assets/css/select2.css?v=3.6.3" rel="stylesheet" />
    <link rel="stylesheet" href="assets/js/plugins/datatables/datatables.min.css"/>
    <link href="assets/css/animate.css" rel="stylesheet" />
    <link href="assets/css/datepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/js/plugins/animate/adp.css">
	<link rel="stylesheet" href="assets/js/plugins/tailselect/css/default/tail.select-light.css">
	<link href="assets/css/sweetalert2.min.css?v=3.6.3" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />

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
                    <li class="nav-item ">
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
                    <li class="nav-item">
						<a class="nav-link" href="./prenomina">
							<i class="material-icons">receipt_long</i>
							<p>Prenómina</p>
						</a>
					</li>
                    <li class="nav-item">
                        <a class="nav-link" href="./subir">
                            <i class="material-icons">cloud_upload</i>
                            <p>Impotar CFDI</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./consultar">
                            <i class="material-icons">text_snippet</i>
                            <p>Nóminas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./catalogos">
                            <i class="material-icons">table_view</i>
                            <p>Catálogos</p>
                        </a>
                    </li>
                    <li id="link1" class="nav-item active">
                        <a class="nav-link" href="./plazas">
                            <i class="material-icons">auto_awesome_motion</i>
                            <p>Plazas</p>
                        </a>
                    </li>
                    <li id="link2" class="nav-item">
                        <a class="nav-link" href="./mensajes">
                            <i class="material-icons">message</i>
                            <p>Mensajes</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./reportes">
                            <i class="material-icons">summarize</i>
                            <p>Reportes</p>
                        </a>
                    </li>
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
            <nav class="navbar navbar-expand-lg  navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="">Plazas</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">notifications</i>
                                    <span class="notification noti-numero">0</span>
                                    <p class="d-lg-none d-md-block">Mensajes</p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right noti-caja"
                                    aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="#">No tiene notificaciones</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <?php echo nombre_periodo(); ?>
                            </li>
                            <li class="nav-item">
                                <a id="cerrar" class="nav-link" href="#">
                                    <i class="material-icons">exit_to_app</i>Cerrar sesión
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
                    <div class="card">
                        <!-- <div class="card-header text-center">
                            <h6 class="card-category text-gray">PLAZAS</h6>
                        </div> -->
                        <div class="card-body">
                            <div class="msn-mostrar">
                                <label onclick="nueva_plaza();" class="btn-mostrar"><i
                                        class="material-icons">add_circle_outline</i>Nueva plaza</label>
                                <input type="file" id="importar-plazas" accept=".xlsx" /><label class="btn-mostrar"
                                    for="importar-plazas"><i class="material-icons">file_upload</i>Importar</label>
                                <!-- <label onclick="exportar_plazas();" class="btn-mostrar"><i
                                        class="material-icons">file_download</i>Exportar</label> -->
                                        <a href="./assets/docs/plazas.xlsx?v=3.6.3" download class="btn-mostrar"><i class="material-icons">line_style</i>Plantilla</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive adp-hide">
                        <div class="opciones_tabla">
                            <select id="id_puesto">
                                <option value="0" selected>TODOS LOS PUESTOS</option>
                                <?php
                                    $conexion = conexion();
                                    $sql = "SELECT 
                                    id_puesto, 
                                    Puesto.nombre AS puesto,
                                    Departamento.nombre AS departamento 
                                    FROM Puesto LEFT JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento 
                                    ORDER BY puesto ASC";
                                    $consulta = $conexion->query($sql);
                                    if($consulta && (mysqli_num_rows($consulta)) > 0){
                                        while($res = mysqli_fetch_array($consulta)){
                                            echo '<option data-description="'.$res["departamento"].'" value="'.$res[0].'">'.$res[1].'</option>';
                                        }	
                                    }else{
                                        echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
                                    }
                                    $conexion->close();
                                ?>
                            </select>
                            <select id="estado">
                                <option value="0">TODAS LAS PLAZAS</option>
                                <option value="1">PLAZAS OCUPADAS</option>
                                <option value="2">PLAZAS DISPONIBLES</option>
                            </select>
                        </div>
                        <table id="tabla-plaza" class="table table-striped" style="width:100%">
                            <thead class="text-primary">
                                <tr>
                                    <th class="">ID</th>
									<th class="">Puesto</th>
                                    <th class="">Trabajador</th>
                                    <th class="oculto">Ocupados</th>
                                    <th class="oculto">Desocupados</th>
                                    <th class="oculto">Por ejercer</th>
                                    <th class="oculto">Presupuestados</th>
									<th class="oculto">Eliminar</th>
                                    <th class="">Estado</th>

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
    <script src="assets/js/material-dashboard.js?v=3.6.3" type="text/javascript"></script>
    <script src="assets/js/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/datepicker.min.js"></script>
    <script src="assets/js/plugins/datepicker.es.js"></script>
    <script src="assets/js/block.js"></script>
	<script src="assets/js/plugins/tailselect/js/tail.select.min.js"></script>
    <script src="assets/js/plugins/tailselect/lang/tail.select-es.js"></script>
    <script src="assets/js/plugins/animate/adp.js"></script>
	<script src="assets/js/moment.js"></script>
    <script src="assets/js/plugins/izitoast/js/iziToast.js"></script>
	<script src="assets/js/sesion.js?v=3.6.3"></script>
    <script src="assets/js/plazas.js?v=3.6.3"></script>

</body>

</html>