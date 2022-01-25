<?php
include "conexion.php";
$conexion = conexion();
$RFC = $_POST['id'];
$fecha = $_POST['fecha'];
$razon = $_POST['razon'];
$condicion = $_POST['condicion'];

$sql = "SELECT * FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $RFC . "' AND estado = 'alta'";

$consulta = mysqli_query($conexion, $sql);
if ($consulta && mysqli_num_rows($consulta) == 1) {
    $usuario = mysqli_fetch_array($consulta);
    $inicio = trim($usuario['fechaRelLab']);

    $array = explode("/", $inicio);
    if (sizeof($array) == 3) {
        $fecha1 = new DateTime(date("Y-m-d", strtotime(str_replace('/', '-', $inicio))));
        $fecha2 = new DateTime(date("Y-m-d", strtotime(str_replace('/', '-', $fecha))));
        $diff = $fecha1->diff($fecha2);
        $dias = $diff->format('%a') + 1;

        if ($dias >= 1) {
            $sql = "UPDATE Usuario SET estado = 'baja' WHERE RFC = '" . $RFC . "'";
            if (mysqli_query($conexion, $sql)) {
                $sql = "INSERT INTO Baja(RFC, fecha, razon, dias) VALUES('" . $RFC . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '" . $razon . "', " . $condicion . ")";
                if (mysqli_query($conexion, $sql)) {

                    // -----------------------------------------------------------------------
                    $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $RFC."'";
                    $consulta = mysqli_query($conexion, $sql);

                    $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '" . $RFC . "' ORDER BY elaboracion desc LIMIT 1";
                    $consulta = mysqli_query($conexion, $sql);
                    $historial = mysqli_fetch_row($consulta);

                    $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('" . $fecha . "','%d/%m/%Y') WHERE id_historial_plaza = " . $historial[0];
                    $consulta = mysqli_query($conexion, $sql);

                    // -----------------------------------------------------------------------

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
