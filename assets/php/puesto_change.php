<?php
include "conexion.php";
$conexion = conexion();
$puesto = $_POST["puesto"];
$ano = date("Y");
$hoy = date("Y-m-d");
$fecha_movimiento = $_POST["fecha"];
$fecha_movimiento = date("Y-m-d", strtotime(str_replace("/", "-", $fecha_movimiento))); 

$sql = "SELECT * FROM Plaza LEFT JOIN Usuario ON Plaza.RFC = Usuario.RFC WHERE 
Plaza.id_puesto = " . $puesto . " AND 
Plaza.estado = 1 
ORDER BY Plaza.id_plaza";

$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_array($consulta)) {
        $ocupados = 0;
        $ocupados_total = 0;
        $vacantes = $res["dias"];

        $ultimo_historial = "";
        $sql = "SELECT fecha_fin FROM Historial_Plaza WHERE 
        id_plaza = " . $res["id_plaza"] . " AND 
        YEAR(fecha_inicio) = " . $ano." 
        ORDER BY fecha_fin DESC LIMIT 1";
        $consulta3 = $conexion->query($sql);
        if ($consulta3 && mysqli_num_rows($consulta3) > 0) {
            $ultimo_historial = mysqli_fetch_array($consulta3);
            $ultimo_historial =  $ultimo_historial[0];
        }

        $sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $res["id_plaza"] . " AND YEAR(fecha_inicio) = " . $ano;
        $consulta2 = $conexion->query($sql);

        $fin_ano = date("Y-m-d", strtotime($ano . "-12-31"));
        $fecha_presupuestada = date("Y-m-d",strtotime($fin_ano."- ".$res["dias"]." days"));
        // $fecha_presupuestada = date("Y-m-d",strtotime($fin_ano."- ".($res["dias"] - 1)." days"));


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

        if($fecha_movimiento < $fecha_presupuestada){
            $description = 'data-description="Plaza disponible hasta el '.$fecha_presupuestada.'" disabled';
        }else if($fecha_movimiento <= $ultimo_historial){
            $description = 'data-description="Seleccione una fecha mayor a '.$ultimo_historial.'" disabled';
        }else{
            $description = 'data-description="'.$vacantes.' días por ejercer"';
        }

        if($res["RFC"] != null){
            echo '<option disabled data-description="'.$res["nombre"].'" value="' . $res["id_plaza"] . '">PLAZA OCUPADA</option>';
        }else{
            echo '<option '.$description.' value="' . $res["id_plaza"] . '">PLAZA #'.$res["id_plaza"].'</option>';
        }
    }
} else {
    echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
}

mysqli_close($conexion);