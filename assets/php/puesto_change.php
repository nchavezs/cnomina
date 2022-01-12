<?php
include "conexion.php";
$conexion = conexion();
$puesto = $_POST["puesto"];
$ano = date("Y");
$hoy = date("Y-m-d");

$sql = "SELECT * FROM Plaza WHERE id_puesto = " . $puesto . " ORDER BY elaboracion";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_array($consulta)) {
        $ocupados = 0;
        $ocupados_total = 0;
        $vacantes = $res["dias"];
        $sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $res["id_plaza"] . " AND YEAR(fecha_inicio) = " . $ano;
        $consulta2 = mysqli_query($conexion, $sql);
        if ($consulta2 && mysqli_num_rows($consulta2) > 0) {
            while ($historial = mysqli_fetch_array($consulta2)) {
                $fecha1 = new DateTime($historial["fecha_inicio"]);
                if ($historial["fecha_fin"] != "") {
                    $fecha2 = new DateTime($historial["fecha_fin"]);
                }else{
                    $fecha2 = new DateTime($hoy);
                }
                $diff = $fecha2->diff($fecha1);
                $ocupados = $diff->format('%a');
                $ocupados_total = $ocupados_total + $ocupados;
            }
            $vacantes = $res["dias"] - $ocupados_total;
        }

        if($res["RFC"] != null){
            echo '<option disabled data-description="'.$vacantes.' días vacantes" value="' . $res[0] . '">PLAZA #' . $res[0] .'</option>';
        }else{
            echo '<option data-description="'.$vacantes.' días vacantes" value="' . $res[0] . '">PLAZA #' . $res[0] .'</option>';
        }
    }
} else {
    echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
}
