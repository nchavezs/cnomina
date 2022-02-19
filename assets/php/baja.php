<?php
include "conexion.php";
$RFC = $_POST['id'];
$fecha = $_POST['fecha'];
$razon = $_POST['razon'];
$condicion = $_POST['condicion'];

$conexion = conexion();

$sql = "SELECT * FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $RFC . "' AND estado = 'alta'";

$consulta = $conexion->query($sql);

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
            $sql = "SELECT * FROM Plaza WHERE RFC = '".$RFC."'";
            $consulta = $conexion->query($sql);
            $plaza = mysqli_fetch_array($consulta);

            $sql = "UPDATE Usuario SET estado = 'baja' WHERE RFC = '" . $RFC . "'";
            if ($conexion->query($sql)) {
                $sql = "INSERT INTO Baja(RFC, fecha, razon, dias,id_plaza) VALUES(
                    '" . $RFC . "', 
                    STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), 
                    '" . $razon . "', 
                    " . $condicion . ",
                    ".$plaza["id_plaza"].")";
                if ($conexion->query($sql)) {

                    // -----------------------------------------------------------------------
                    $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $RFC."'";
                    $consulta = $conexion->query($sql);

                    $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '" . $RFC . "' ORDER BY elaboracion desc LIMIT 1";
                    $consulta = $conexion->query($sql);
                    $historial = mysqli_fetch_row($consulta);

                    $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('" . $fecha . "','%d/%m/%Y') WHERE id_historial_plaza = " . $historial[0];
                    $consulta = $conexion->query($sql);

                    $sql = "INSERT INTO Historial(RFC,fecha,tipo,descripcion) 
                    VALUES('" . $RFC . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),'baja', '".$razon."')";
                    $consulta = $conexion->query($sql);

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
