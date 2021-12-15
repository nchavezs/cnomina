<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$fecha = $_POST['fecha'];
$razon = $_POST['razon'];
$condicion = $_POST['condicion'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "' AND estado = 'alta'";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && mysqli_num_rows($consulta) == 1) {
    $resultado = mysqli_fetch_array($consulta);
    $inicio = trim($resultado['fechaRelLab']);

    $array = explode("/", $inicio);
    if (sizeof($array) == 3) {
        $fecha1 = new DateTime(date("Y-m-d", strtotime(str_replace('/', '-', $inicio))));
        $fecha2 = new DateTime(date("Y-m-d", strtotime(str_replace('/', '-',$fecha))));
        $diff = $fecha1->diff($fecha2);
        $dias = $diff->format('%a') + 1;

        if ($dias >= 15) {
            $sql = "UPDATE Usuario SET estado = 'baja' WHERE RFC = '" . $id . "'";
            if (mysqli_query($conexion, $sql)) {
                $sql = "INSERT INTO Baja(RFC, fecha, razon, dias) VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '" . $razon . "', " . $condicion . ")";
                if (mysqli_query($conexion, $sql)) {
                    echo 1;
                } else {
                    echo 0;
                }

            } else {
                echo 0;
            }
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }
} else {
    echo 0;
}

mysqli_close($conexion);
