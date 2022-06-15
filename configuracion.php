<?php
include "assets/php/main_admin.php";

if( id_rol() != 1){
	header("location: ./perfil");
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
    
    <link href="assets/js/plugins/izitoast/css/iziToast.css" rel="stylesheet" />
	<link href="assets/css/material-dashboard.css?v=3.7.3" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet" />
    <link href="assets/css/dropzone.min.css" rel="stylesheet" />
    <link href="assets/css/sweetalert2.min.css?v=3.7.3" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />

</head>

<body>
    <div class="wrapper">
        <div class="wizard_fondo">
            <div class="wizard_caja">
                <div class="row">
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
                            <!-- INICIO -->
                            <div class="wizard_panel">
                                <div class="wizard_panel_inicio">
                                    <h2 class="text-primary pb-4 negrita">Vamos a configurarlo todo</h2>
                                    <p>Es necesario establecer algunos parámetros, puedes omitir el proceso de
                                        configuración
                                        y dirigirte diréctamente al menú principal para configurar todo manualmente.</p>
                                    <p>Para continuar completa la información requerida en los siguientes apartados.</p>
                                    <div class="wizard_img">
                                        <img src="./assets/img/1.png" alt="">
                                    </div>
                                </div>
                                <div class="wizard_footer">
                                    <small class="wizard_omitir" onclick="wizard_omitir();">Continuar al menú principal</small>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_perfil();">Siguiente <i
                                            class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <!-- PERFIL -->
                            <div class="wizard_panel hide">
                                <div class="wizard_panel_perfil">
                                    <h2 class="text-primary pb-4 negrita">Configura tu información de perfil
                                    </h2>
                                    <p>Configura tu nombre y establece una contraseña nueva para el inicio de sesión,
                                        antes de continuar presiona <a href="#">guardar información de perfil</a> para
                                        guardar cambios.</p>
                                    <form id="form_wizard_perfil">
                                        <p>¿Cúal es tu nombre?</p>
                                        <input id="nombre" name="nombre" type="text" placeholder="Jhon Brown"
                                            class="mb-4" value="<?php echo $varName ?>" required>
                                        <p class="text-mutted">Escribe una contraseña</p>
                                        <input id="password" name="password" type="password" placeholder="***********"
                                            class="mb-4" required minlength="6">
                                        <p>Repite tu contraseña</p>
                                        <input id="confirmar" name="confirmar" type="password" placeholder="***********"
                                            class="mb-4" required>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success btn-sm">Guardar
                                                información de perfil <i class="material-icons">save</i> </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="wizard_footer">
                                    <button type="button" class="btn btn-primary btn-sm px-3"
                                        onclick="wizard_inicio();">Anterior
                                        <i class="material-icons">undo</i> </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_logo();">Siguiente
                                        <i class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <!-- LOGO -->
                            <div class="wizard_panel hide">
                                <div class="wizard_panel_logo">
                                    <h2 class="text-primary pb-4 negrita">Selecciona tu logo</h2>
                                    <p>Puedes elegir una imagen y establecerla como logo para tus reportes y mostrarla en el panel principal. La imagen deberá estar en formato <span class="text-warning">PNG</span> y deberá pesar menos de <span class="text-warning">5 Mb</span>.</p>
                                    <form action="assets/php/wizard_logo.php" class="dropzone"
                                        id="dropzone-logo">
                                        <div class="dz-message">
                                            <div class="row">
                                                <div class="col-md-4"><img src="assets/img/upload_image.svg" alt=""></div>
                                                <div class="col-md-8">
                                                    <h3 class="negrita">Selecciona tu archivo</h3>
                                                    <div><small>Arrastra tu logo aquí o <span class="text-info">búscalo</span> para cargarlo.</small></div>
                                                    <button type="button" class="btn btn-sm btn-success mt-4">Seleccionar imagen</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="wizard_footer">
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_perfil();">Anterior <i
                                            class="material-icons">undo</i> </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_importar();">Siguiente
                                        <i class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <!-- IMPORTAR -->
                            <div class="wizard_panel hide">
                                <div class="wizard_panel_importar">
                                    <h2 class="text-primary pb-4 negrita">Importar plantilla de plazas</h2>
                                    <p>Con esta herramienta puede importar datos desde una hoja de cálculo sin necesidad
                                        de crear los registros manualmente, es necesario seguir los siguientes puntos:
                                    </p>
                                    <ol>
                                        <li>Descargue la plantilla para importar datos <a class="text-warning"
                                                href="./assets/docs/plazas.xlsx?v=3.7.3" download>aquí</a> .</li>
                                        <li>Busque la ubicación del archivo, edítelo y carguelo en la siguiente sección.
                                            <i class="material-icons">arrow_downward</i>
                                        </li>
                                    </ol>
                                    <form action="assets/php/importar_plazas.php" class="dropzone"
                                        id="dropzone-plantilla">
                                        <div class="dz-message">
                                            <div class="row">
                                                <div class="col-md-4"><img src="assets/img/upload.svg" alt=""></div>
                                                <div class="col-md-8">
                                                    <h3 class="negrita">Selecciona tu archivo</h3>
                                                    <div><small>Arrastra un archivo aquí o <span class="text-info">búscalo</span> para cargarlo.</small></div>
                                                    <button type="button" class="btn btn-sm btn-success mt-4">Seleccionar archivo</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="wizard_footer">
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_logo();">Anterior <i
                                            class="material-icons">undo</i> </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_finalizar();">Finalizar <i
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
    <script src="assets/js/plugins/sweetalert2.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/material-dashboard.js?v=3.7.3" type="text/javascript"></script>
    <script src="assets/js/dropzone.js"></script>
    <script src="assets/js/configuracion.js?v=3.7.3"></script>
</body>

</html>