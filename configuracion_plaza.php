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
	<link href="assets/css/material-dashboard.css?v=3.9.5" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet" />
    <link href="assets/css/dropzone.min.css" rel="stylesheet" />
    <link href="assets/css/sweetalert2.min.css?v=3.9.5" rel="stylesheet" />
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
                                <small>Configurar nuevo presupuesto</small>
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
                                    <h2 class="text-primary pb-4 negrita">Configurar nuevo presupuesto</h2>
                                    <p>Es necesario establecer el presupuesto anual de plazas, puedes omitir el proceso de
                                        configuración y dirigirte diréctamente al menú principal para configurar todo manualmente.</p>
                                    <p>Para continuar completa la información requerida en los siguientes apartados.</p>
                                    <div class="wizard_img my-4">
                                        <img src="./assets/img/configuracion.svg" alt="">
                                    </div>
                                </div>
                                <div class="wizard_footer">
                                    <small class="wizard_omitir" onclick="wizard_omitir();">Continuar al menú principal</small>
                                    <div></div>
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_importar();">Siguiente <i
                                            class="material-icons">navigate_next</i> </button>
                                </div>
                            </div>
                            <!-- IMPORTAR -->
                            <div class="wizard_panel hide">
                                <div class="wizard_panel_importar">
                                    <h2 class="text-primary pb-4 negrita">Importar presupuesto de plazas </h2>
                                    <p>Se importará el presupuesto a partir de la plantilla de plazas, se crearan nuevas plazas para aquellos puestos nuevos, 
                                        se asignarán plazas automáticamente a los empleados para el año en curso, es necesario seguir los siguientes puntos:
                                    </p>
                                    <ol>
                                        <li>Descargue la plantilla para importar datos <a class="text-warning"
                                                href="./assets/docs/plazas.xlsx?v=3.9.5" download>aquí</a> .</li>
                                        <li>Busque la ubicación del archivo, edítelo y carguelo en la siguiente sección.
                                            <i class="material-icons">arrow_downward</i>
                                        </li>
                                    </ol>
                                    <form action="assets/php/importar_presupuesto.php" class="dropzone"
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
                                    <button class="btn btn-primary btn-sm px-3" onclick="wizard_inicio();">Anterior <i
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
    <script src="assets/js/material-dashboard.js?v=3.9.5" type="text/javascript"></script>
    <script src="assets/js/dropzone.js"></script>
    <script src="assets/js/configuracion_plaza.js?v=3.9.5"></script>
</body>

</html>