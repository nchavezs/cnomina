<?php
session_start();

$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];
$varName = $_SESSION['nombre'];

if ($varUser == null || $varUser == '' || $varCateg == "user") { // Si el usuario no esta autorizado, no lo deja acceder
    header("location: /");
}

include "./assets/php/rol.php";
$rol = rol();
if ($rol != 1) {
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
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/css/material-dashboard.css?v=3.1.4" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet" />

</head>

<body>
    <div class="wrapper">
        <div class="wizard_fondo">
            <div class="wizard_caja">
                <div class="row w-100">
                    <div class="col-lg-4">
                        <div class="wizard_steps">
                            <div class="wizard_step wizard_step_inicio activo">
                                <p>Inicio</p>
                                <small>Vamos a comenzar</small>
                            </div>
                            <div class="wizard_step wizard_step_perfil">
                                <p>Completa tu perfil</p>
                                <small>Configura tu información de perfil</small>
                            </div>
                            <div class="wizard_step wizard_step_logo">
                                <p>Selecciona tu logo</p>
                                <small>Elige tu logo para reportes y menú principal</small>
                            </div>
                            <div class="wizard_step wizard_step_importar">
                                <p>Importa tus datos</p>
                                <small>No inicies sin nada. Importa tus datos ahora mismo</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="wizard_contenido">
                            <div class="wizard_panel wizard_panel_inicio">
                                <h2 class="text-primary pb-4 font-weight-bold">Vamos a configurarlo todo</h2>
                                <p>Es necesario establecer algunos parámetros, puedes omitir el proceso de configuración
                                    y dirigirte diréctamente al menú principal para configurar todo manualmente.</p>
                                <p>Para continuar completa la información requerida en los siguientes apartados.</p>
                                <div class="wizard_img">
                                    <img src="./assets/img/1.png" alt="">
                                </div>
                                <div class="wizard_footer">
                                    <small class="wizard_omitir">Continuar al menú principal</small>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_perfil();">Siguiente <i
                                            class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <div class="wizard_panel wizard_panel_perfil hidden">
                                <form id="form_wizard_perfil">
                                    <h2 class="text-primary pb-4 font-weight-bold">Configura tu información de perfil
                                    </h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>¿Cúal es tu nombre?</p>
                                            <input id="nombre" name="nombre" type="text" placeholder="Jhon Brown"
                                                class="mb-4" value="<?php echo $varName ?>" required>
                                            <p>Escribe una contraseña</p>
                                            <input id="password" name="password" type="password"
                                                placeholder="***********" class="mb-4" required minlength="6">
                                            <p>Repite tu contraseña</p>
                                            <input id="confirmar" name="confirmar" type="password"
                                                placeholder="***********" class="mb-4" required>
                                            <!-- <button class="btn btn-success btn-sm">Guardar
                                                información de perfil <i class="material-icons">save</i> </button> -->
                                        </div>

                                    </div>
                                    <div class="wizard_footer">
                                        <button type="button" class="btn btn-primary btn-sm px-3" onclick="wizard_inicio();">Anterior
                                            <i class="material-icons">undo</i> </button>
                                        <button type="submit" class="btn btn-primary btn-sm px-3">Siguiente
                                            <i class="material-icons">navigate_next</i> </button>
                                    </div>
                                </form>
                            </div>
                            <div class="wizard_panel wizard_panel_logo hidden">
                                <h2 class="text-primary pb-4 font-weight-bold">Selecciona tu logo</h2>

                                <div class="wizard_footer">
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_perfil();">Anterior <i
                                            class="material-icons">undo</i> </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_importar();">Siguiente
                                        <i class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <div class="wizard_panel wizard_panel_importar hidden">
                                <h2 class="text-primary pb-4 font-weight-bold">Importar plantilla de puestos</h2>
                                <input type="file">
                                <div class="wizard_footer">
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_logo();">Anterior <i
                                            class="material-icons">undo</i> </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="">Finalizar <i
                                            class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <link href="assets/css/sweetalert2.min.css?v=3.1.4" rel="stylesheet" />
    <script src="assets/js/plugins/sweetalert2.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/material-dashboard.js?v=3.1.4" type="text/javascript"></script>
    <script src="assets/js/block.js"></script>
    <script src="assets/js/configuracion.js?v=3.1.4"></script>
</body>

</html>