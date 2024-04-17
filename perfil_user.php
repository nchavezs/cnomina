<?php
include "assets/php/main_user.php";

$conexion = conexion();
$sql = "SELECT *,
(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE Usuario.RFC = '" . $varUser . "'";
$consulta = $conexion->query($sql);
if ($conexion->query($sql)) {
    $usuario = mysqli_fetch_array($consulta);
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
	<link href="assets/css/material-dashboard.css?v=3.9.5" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet" />
    <link href="assets/css/select2.css?v=3.9.5" rel="stylesheet" />
    <link href="assets/css/datepicker.min.css" rel="stylesheet" />
    <link href="assets/css/sweetalert2.min.css?v=3.9.5" rel="stylesheet" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
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
                    <li class="nav-item  ">
                        <a class="nav-link" href="./tablas">
                            <i class="material-icons">content_paste</i>
                            <p>CFDI</p>
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
                            <p>Mensajes <span class="material-icons comprobar_mensajeria animate__animated animate__swing animate__infinite animate__slower hide">markunread</span></p>
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
                <div id="msn-caja" class="container-fluid msn-caja mt-5">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card card-profile">
                            <div class="card-avatar">
                                <div id="subir">
                                    <img id="foto" class="img" src="assets/img/user.svg" />
                                </div>
                                <input type="file" id="archivo" accept=".jpg, .png, .jpeg" style="display:none">
                            </div>

                                <div class="card-body px-5">
                                    <h6 class="card-category text-gray">Mi perfil</h6>
                                    <div class="row text-left">
                                        <div class="col-md-6">
                                            <label class="bmd-label-floating">Nombre</label>
                                            <input id="nombre" type="text" class="campo" disabled value="<?php echo $usuario["nombre"] ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="bmd-label-floating">No. de empleado</label>
                                            <input id="domicilio" disabled type="text" class="campo"  value="<?php echo str_pad($usuario['id_empleado'], 5, '0', STR_PAD_LEFT) ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="bmd-label-floating">Estado</label>
                                            <input id="perfil-estado" type="text" class="campo
                                            <?php if ($usuario["estado"] === 'alta') {
                                            echo ' text-success';
                                        } else {
                                            echo ' text-danger';
                                        }
                                        ?>" disabled value="<?php echo mb_strtoupper($usuario["estado"]) ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="bmd-label-floating">CURP</label>
                                            <input type="text" class="campo" disabled value="<?php echo $usuario["CURP"] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="bmd-label-floating">RFC</label>
                                            <input type="text" class="campo" disabled value="<?php echo $usuario["RFC"] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="bmd-label-floating">Puesto</label>
                                            <input type="text" class="campo" disabled value="<?php echo $usuario["puesto"] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="bmd-label-floating">Departamento</label>
                                            <input type="text" class="campo" disabled value="<?php echo $usuario["departamento"] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="form-empleado">
                                <div class="card my-4">
                                    <div class="card-body px-5">
                                      <div class="row">
                                            <div class="col-md-5">
                                                <label class="bmd-label-floating">Correo</label>
                                                <input id="email" type="email" class="campo" name="email" required value="<?php echo $usuario["email"] ?>">
                                            </div>
                                            <div class="col-md-7">
                                                <label class="bmd-label-floating">Domicilio</label>
                                                <input id="domicilio" type="text" class="campo" value="<?php echo $usuario["domicilio"] ?>">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="bmd-label-floating">No. de cuenta</label>
                                                <input id="banca" type="text" class="campo" value="<?php echo $usuario["banca"] ?>">
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="bmd-label-floating">No. de afiliación</label>
                                                <input id="afiliacion" type="text" class="campo" value="<?php echo $usuario["afiliacion"] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="bmd-label-floating">Teléfono</label>
                                                <input id="telefono" class="campo" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" maxlength=14 required
                                                    name="telefono" required value="<?php echo $usuario["telefono"] ?>">
                                            </div>
                                      </div>
                                    </div>
                                    <div class="card-footer centrado">
                                        <button type="submit" id="editar" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
                                    </div>
                                </div>
                            </form>
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
                                            <label class="bmd-label-floating">Contraseña actual</label>
                                            <input type="password" maxlength="20" minlength="3" name="pass" id="pass" class="campo" required autocomplete="off">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Nueva contraseña</label>
                                            <input type="password" maxlength="20" minlength="3" name="newPass" id="newPass" class="campo" required
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Confirmar contraseña</label>
                                            <input type="password" maxlength="20" minlength="3" name="confirmacion" id="confirmacion" class="campo" required
                                                autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>

                                </div>
                            </form>
                        </div>

                        <div class="card my-4">
                            <div class="card-body text-center">
                            <h6 class="card-category text-gray">Expediente</h6>
                                <a href="#" id="expediente" class="btn btn-primary btn-sm"><i class="material-icons">content_paste</i> Ver Expediente</i></a>
                            </div>
                        </div>
                        <!-- <div class="card">
                            <div class="card-body text-center">
                                <h6 class="card-category text-gray">Manual de usuario</h6>
                                <a href="./documentos/usuario.pdf" download class="btn btn-primary btn-sm"><i class="material-icons">download</i> Descargar manual</i></a>
                            </div>
                        </div> -->

                    </div>
                </div>


            </div>
            </div>
            <div class="modal" id="modal" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title modal_titulo text-primary "></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="modal_aceptar">Continuar</button>
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
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/js/plugins/sweetalert2.min.js"></script>
    <script src="assets/js/material-dashboard.js?v=3.9.5" type="text/javascript"></script>
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/izitoast/js/iziToast.js"></script>
	<script src="assets/js/sesion.js?v=3.9.5"></script>
    <script src="assets/js/block.js"></script>
    <script src="assets/js/perfil.js?v=3.9.5"></script>
    <script src="assets/js/select.js"></script>

</body>

</html>