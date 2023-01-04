<?php
include "conexion.php";
session_start();
$conexion = conexion();
$puesto = $_POST["puesto"];

function diferencia($fecha1, $fecha2)
{
    $fecha1 = new DateTime($fecha1);
    $fecha2 = new DateTime($fecha2);
    $diff = $fecha2->diff($fecha1);
    return $diff->format('%a');
}


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
$fecha_movimiento = $_POST["fecha"];
$fecha_movimiento = date("Y-m-d", strtotime(str_replace("/", "-", $fecha_movimiento))); 

$sql = "SELECT * FROM Plaza LEFT JOIN Usuario ON Plaza.RFC = Usuario.RFC WHERE 
Plaza.id_puesto = " . $puesto . " AND 
Plaza.estado = 1 AND
YEAR(Plaza.elaboracion) = ".$ano." 
ORDER BY Plaza.id_plaza";

$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_array($consulta)) {
        $ocupados = 0;
        $ocupados_total = 0;
        // $vacantes = $res["dias"];

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

        $fin_ano = date("Y-m-d", strtotime($ano . "-12-31"));
        $fecha_presupuestada = date("Y-m-d",strtotime($fin_ano."- ".($res["dias"] - 1)." days"));

        $sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $res["id_plaza"] . " AND YEAR(fecha_inicio) = " . $ano;
        $consulta2 = $conexion->query($sql);
        
        if ($consulta2 && mysqli_num_rows($consulta2) > 0) {
            while ($historial = mysqli_fetch_array($consulta2)) {
                $fecha1 = $historial["fecha_inicio"];
                if ($historial["fecha_fin"] != null) {
                    $fecha2 = $historial["fecha_fin"];
                } else {
                    $fecha2 = $hoy;
                }
                $ocupados = diferencia($fecha1, $fecha2);
                $ocupados_total = $ocupados_total + $ocupados + 1;
            }
            // $vacantes = $res["dias"] - $ocupados_total;
        }

        if($fecha_movimiento < $fecha_presupuestada){
            $description = 'data-description="Plaza disponible hasta el '.$fecha_presupuestada.'" disabled';
        }else if($fecha_movimiento <= $ultimo_historial){
            $description = 'data-description="Seleccione una fecha mayor a '.$ultimo_historial.'" disabled';
        }else{
            $description = 'data-description="'.$ocupados_total.' días ocupados"';
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

$conexion->close();