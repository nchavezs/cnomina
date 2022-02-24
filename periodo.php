<?php
include "assets/php/main_admin.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Consulta Nómina</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans"/>

    <link rel="stylesheet" href="assets/css/material-dashboard.css?v=3.4.4"/>
    <link rel="stylesheet" href="assets/js/plugins/animate/adp.css">
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css?v=3.4.4"/>
</head>

<body>
    <div class="periodo">
        <div class="pagina pagina_1">
            <div class="text-center">
                <h2 class="text-primary font-weight-bold">Tipo de periodo</h2>
                <h3>Selecciona el tipo de periodo para continuar</h3>
            </div>
            <div class="row centrado p-4 mt-4">
            <?php
                $conexion = conexion();
                $sql = "SELECT * FROM Periodo WHERE id_periodo <> 3";
                $consulta = $conexion->query($sql);

                while ($periodo = mysqli_fetch_array($consulta)) {
                    echo '<div class="col-xl-4 col-6">
                            <div onclick="seleccionar_ano(' . $periodo[0] . ');" class="card periodo_elemento">
                                <div class="card-body text-center">
                                    <i class="material-icons text-warning">highlight_alt</i>
                                    <p>' . $periodo["nombre"] . '</p>
                                </div>
                            </div>
                        </div>';
                }
                ?>
            </div>
            <!-- <div class="periodo_boton centrado"><button class="btn"><i class="material-icons">keyboard_backspace</i>Regresar</button></div> -->
        </div>

        <div class="pagina pagina_2 adp-hide">
            <div class="text-center">
                <h2 class="text-primary font-weight-bold">Año</h2>
                <h3>Selecciona el año del periodo</h3>
            </div>
            <div class="row centrado p-4 mt-4">
            <?php
            for ($i = 2022; $i <= 2022; $i++) {
                echo '<div class="col-xl-3 col-4">
                        <div onclick="seleccionar_periodo(' . $i . ');" class="card periodo_elemento">
                            <div class="card-body text-center">
                                <p>' . $i . '</p>
                            </div>
                        </div>
                    </div>';
            }
            ?>
            </div>
            <div class="periodo_boton centrado"><button onclick="seleccionar_tipoperiodo();" class="btn"><i class="material-icons">keyboard_backspace</i>Seleccionar tipo de periodo</button></div>
        </div>

        <div class="pagina pagina_3 adp-hide">
            <div class="text-center">
                <h2 class="text-primary font-weight-bold">Periodo</h2>
                <h3>Selecciona el periodo para finalizar</h3>
            </div>
            <div class="row centrado p-4 mt-4 periodos">
            </div>
            <div class="periodo_boton centrado"><button onclick="seleccionar_ano();" class="btn"><i class="material-icons">keyboard_backspace</i>Seleccionar año</button></div>
        </div>

    </div>

    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/plugins/sweetalert2.min.js"></script>
    <script src="assets/js/plugins/animate/adp.js"></script>
    <script src="assets/js/periodo.js?v=3.4.4"></script>
</body>

</html>