<?php
include "conexion.php";
session_start();
$conexion = conexion();
$id = $_POST["id"];

// -------------------------------------------------------------------------------
$ano = $_SESSION["ano"];
$hoy = date("Y-m-d");

if($ano != date("Y")){
    $id_prenomina = $_SESSION["id_prenomina"];
    $sql = "SELECT * FROM Prenomina WHERE id_prenomina =".$id_prenomina;
    $consulta = $conexion->query($sql);
    $prenomina = mysqli_fetch_array($consulta);

    $hoy = $prenomina["al"];
}
// -------------------------------------------------------------------------------

$html = '<div class="row formulario_caja p-4">
<div class="col-md-12 formulario">
    <div class="text-left p-2">
        <h4 class="negrita text-primary">Historial de vacantes</h4>
        <small class="text-muted">Usuarios registrados en esta plaza en el año '.$ano.'.</small>
    </div>
</div>';

$sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $id . " AND YEAR(fecha_inicio) = " . $ano;
$consulta = $conexion->query($sql);
if ($consulta && mysqli_num_rows($consulta) > 0) {
    while ($historial = mysqli_fetch_array($consulta)) {
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $historial["RFC"] . "'";
        $consulta2 = $conexion->query($sql);
        $usuario = mysqli_fetch_row($consulta2);

        $fecha1 = new DateTime($historial["fecha_inicio"]);
        if ($historial["fecha_fin"] != "") {
            $fecha2 = new DateTime($historial["fecha_fin"]);
        } else {
            $fecha2 = new DateTime($hoy);
            $historial["fecha_fin"] = " - ";
        }
        $diff = $fecha2->diff($fecha1);
        $ocupados = $diff->format('%a') + 1;

        $html=$html. '<div class="col-md-6">
                        <div class="card overflow-hidden my-3">
                            <div class="card-body historial_caja">
                                <div class="historial_img"><img src="assets/img/user.png" alt=""></div>
                                <div class="historial_usuario">
                                ' . $usuario[0] . '
                                <p>' . $ocupados . ' dias ocupados</p>
                                <p>Fecha de inicio: ' . $historial["fecha_inicio"] . '</p>
                                <p>Fecha de término: ' . $historial["fecha_fin"] . '</p>
                                </div>
                            </div>
                        </div>
                    </div>';
    }

    $html=$html. '</div>';
    echo $html;
}else {
    echo 0;
}

$conexion->close();
