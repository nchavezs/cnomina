<?php
include "assets/php/main_user.php";

$conexion = conexion();
$consulta = "SELECT * FROM Usuario WHERE RFC = '" . $varUser . "'";

$sql = "SELECT
Empleado.RFC AS RFC,
nombre,
CRUP,
fechaRelLab,
email,
telefono,
estado,
(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE Trabajador.id_trabajador = Empleado.id_trabajador) AS tipoTrabajador  
FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE 
RFC = '" . $varUser . "'";

$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    $res = mysqli_fetch_array($resultado);
    $nombre = $res["nombre"];
    $CURP = $res["CURP"];
    $RFC = $res["RFC"];
    $fechaRelLab = $res["fechaRelLab"];
    $puesto = $res["puesto"];
    $departamento = $res["departamento"];
    $email = $res["email"];
    $telefono = $res["telefono"];
    $estado = $res["estado"];
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
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/css/material-dashboard.css?v=3.2.2" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet" />
    <link href="assets/css/select2.css?v=3.2.2" rel="stylesheet" />
    <link href="assets/css/datepicker.min.css" rel="stylesheet" />
</head>

<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="white">
            <div class="municipio">Consulta Nómina <small><?php echo get_municipio() ?><small></div>
            <div class="avatar">
                <?php
				$foto = "assets/img/user.png";
				if ($varFoto != null) {
					$foto = $varFoto;
				}

				?>
                <a href="./perfil"><img src="<?php echo $foto ?>"></a>
                <p><?php echo $varName ?></p>
                <a href="mailto:"><?php echo $varEmail ?></a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item  ">
                        <a class="nav-link" href="./tablas">
                            <i class="material-icons">content_paste</i>
                            <p>Nóminas</p>
                        </a>
                    </li>
                    <li id="link1" class="nav-item active">
                        <a class="nav-link" href="./perfil_user">
                            <i class="material-icons">person_pin</i>
                            <p>Perfil</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./movimientos">
                            <i class="material-icons">transfer_within_a_station</i>
                            <p>Movimientos</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./descuentos">
                            <i class="material-icons">trending_down</i>
                            <p>Descuentos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vacaciones">
                            <i class="material-icons">flight</i>
                            <p>Vacaciones</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./permisos">
                            <i class="material-icons">description</i>
                            <p>Permisos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./beneficiarios">
                            <i class="material-icons">people</i>
                            <p>Beneficiarios</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pases">
                            <i class="material-icons">people</i>
                            <p>Pases</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./gastos">
                            <i class="material-icons">healing</i>
                            <p>G. Médicos</p>
                        </a>
                    </li>
                    <li id="link2" class="nav-item ">
                        <a class="nav-link" href="./mensajes_user">
                            <i class="material-icons">message</i>
                            <p>Mensajes</p>
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
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="">Mi Perfil</a>
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
                        <div class="col-xl-6">
                            <div class="card card-profile">
                                <div class="card-avatar">
                                    <div id="subir">
                                        <img id="foto" class="img" src="assets/img/user.svg" />
                                    </div>
                                    <input type="file" id="archivo" accept=".jpg, .png, .jpeg" style="display:none">
                                </div>

                                <form id="form-user" action="./assets/php/datosUsuario.php" method="post">
                                    <div class="card-body px-5">
                                        <h6 class="card-category text-gray">Mi perfil</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Nombre</label>
                                                    <input id="nombre" type="text" class="form-control" disabled
                                                        value="<?php echo $nombre ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Correo</label>
                                                    <input id="email" type="email" class="form-control" name="email"
                                                        required value="<?php echo $email ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">CURP</label>
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $CURP ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">RFC</label>
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $RFC ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Puesto</label>
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $puesto ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Departamento</label>
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $departamento ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Teléfono</label>
                                                    <input id="telefono" class="form-control"
                                                        pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 required
                                                        name="telefono" required value="<?php echo $telefono ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Estado</label>
                                                    <input id="perfil-estado" type="text" class="form-control
                                                        <?php if ($estado === 'alta') {
                                                            echo ' text-success';
                                                        } else {
                                                            echo ' text-danger';
                                                        }
                                                        ?>" disabled value="<?php echo strtoupper($estado) ?>">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Fecha de inicio</label>
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $fechaRelLab ?>">
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" id="editar" class="btn btn-primary btn-sm regresar"><i
                                                class="material-icons">save</i> Guardar </button>
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
                                <form id="form-cambiar">
                                    <div class="card-body px-5">
                                        <h6 class="card-category text-gray">Cambiar contraseña</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Contraseña actual</label>
                                                    <input type="password" name="pass" id="pass" class="form-control"
                                                        required autocomplete="off">

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Nueva contraseña</label>
                                                    <input type="password" name="newPass" id="newPass"
                                                        class="form-control" required autocomplete="off">

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Confirmar contraseña</label>
                                                    <input type="password" name="confirmacion" id="confirmacion"
                                                        class="form-control" required autocomplete="off">

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-sm regresar"><i
                                                class="material-icons">save</i> Guardar </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <a href="./documentos/usuario.pdf" download class="manual"><i
                                                    class="material-icons">download</i>Manual</i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card mt-0">
                                        <div class="card-body">
                                            <a href="#" id="expediente" class="manual"><i
                                                    class="material-icons">content_paste</i>Expediente</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">

                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

    <!--  Plugin for Sweet Alert -->
    <link href="assets/css/sweetalert2.min.css?v=3.2.2" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <script src="assets/js/plugins/sweetalert2.min.js"></script>

    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>

    <!-- Chartist JS -->

    <!--  Notifications Plugin    -->
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/material-dashboard.js?v=3.2.2" type="text/javascript"></script>
    <script src="assets/js/sesion.js?v=3.2.2"></script>
    <script src="assets/js/block.js"></script>
    <script src="assets/js/perfil.js?v=3.2.2"></script>
    <script src="assets/js/mensajes-user.js?v=3.2.2"></script>
    <script src="assets/js/select.js"></script>
    <script src="assets/js/datepicker.min.js"></script>
    <script src="assets/js/plugins/datepicker.es.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

</body>

</html>