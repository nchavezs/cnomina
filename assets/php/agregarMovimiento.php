<?php
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST["id"]);
$id = $id[0];
$fecha1 = $_POST["fecha"];
$puesto = $_POST["puesto"];
$departamento = $_POST["departamento"];
$observacion = $_POST["observacion"];
$trabajador = $_POST["trabajador"];

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date('d/m/Y', time());

$sql = "SELECT puesto, departamento, tipoTrabajador FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
if ($consulta) {
    $datos = mysqli_fetch_array($consulta);
    $puestoAnterior = $datos[0];
    $departamentoAnterior = $datos[1];
    $tipoTrabajadorAnterior = $datos[2];

    $sql = "INSERT INTO Movimiento(RFC,fecha,puesto,departamento,puestoAnterior,departamentoAnterior,observacion, elaboracion, tipoTrabajadorAnterior)
		VALUES('" . $id . "', STR_TO_DATE('" . $fecha1 . "','%d/%m/%Y'),'" . $puesto . "','" . $departamento . "', '" . $puestoAnterior . "',
		'" . $departamentoAnterior . "', '" . $observacion . "', STR_TO_DATE('" . $hoy . "','%d/%m/%Y'), '" . $tipoTrabajadorAnterior . "')";
    if (mysqli_query($conexion, $sql)) {
        $sql = "UPDATE Usuario SET puesto = '" . $puesto . "', departamento = '" . $departamento . "', tipoTrabajador = '".$trabajador."'  WHERE RFC = '" . $id."'";
        if (mysqli_query($conexion, $sql)) {
            $sql = "SELECT MAX(id_movimiento) FROM Movimiento";
            $consulta = mysqli_query($conexion, $sql);
            if ($consulta) {
                $res = mysqli_fetch_row($consulta);
                echo $res[0];
            }
        } else {
            echo 0;
        }

    } else {
        echo 0;
    }

} else {
    echo 0;
}

mysqli_close($conexion);
