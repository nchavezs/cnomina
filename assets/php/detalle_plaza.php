<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

$ano = date("Y");
$hoy = date("Y-m-d");

$html = '<div class="row formulario p-4">
<div class="col-md-12">
<div class="text-left p-2">
						<h4 class="font-weight-bold text-primary">Historial de vacantes</h4>
						<small class="text-muted">Usuarios que han estado registrados en esta plaza en el año en curso.</small>
					</div>
</div>';

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

        if($historial["fecha_fin"] == null){
            $historial["fecha_fin"] = " -  ";
        }

        $html=$html. '<div class="col-md-6">
                        <div class="card overflow-hidden">
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

mysqli_close($conexion);
