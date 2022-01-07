<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

$ano = date("Y");
$hoy = date("Y-m-d");
$html = "";

$sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $id . " AND YEAR(fecha_inicio) = " . $ano;
$consulta = mysqli_query($conexion, $sql);
if ($consulta && mysqli_num_rows($consulta) > 0) {
    while ($historial = mysqli_fetch_array($consulta)) {
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $historial["RFC"] . "'";
        $consulta2 = mysqli_query($conexion, $sql);
        $usuario = mysqli_fetch_row($consulta2);

        $fecha1 = new DateTime($historial["fecha_inicio"]);
        if ($historial["fecha_fin"] != "") {
            $fecha2 = new DateTime($historial["fecha_fin"]);
        } else {
            $fecha2 = new DateTime($hoy);
        }
        $diff = $fecha2->diff($fecha1);
        $ocupados = $diff->format('%a');

        $html=$html. '<div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>' . $usuario[0] . '</h5>
                                <small>' . $ocupados . ' dias ocupados</small>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    echo $html;
}else {
    echo 0;
}

mysqli_close($conexion);
