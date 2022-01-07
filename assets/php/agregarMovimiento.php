<?php
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST["id"]);
$RFC = $id[0];
$fecha = $_POST["fecha"];
$puesto = $_POST["puesto"];
$departamento = $_POST["departamento"];
$observacion = $_POST["observacion"];
$trabajador = $_POST["trabajador"];
$plaza = $_POST["plaza"];

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date('d/m/Y', time());

$sql = "SELECT * FROM Plaza WHERE RFC = '".$RFC."'";
$consulta = mysqli_query($conexion, $sql);
$plazaAnterior = mysqli_fetch_array($consulta);

$sql = "SELECT puesto, departamento, tipoTrabajador FROM Usuario WHERE RFC = '" . $RFC . "'";
$consulta = mysqli_query($conexion, $sql);
if ($consulta) {
    $datos = mysqli_fetch_array($consulta);
    $puestoAnterior = $datos[0];
    $departamentoAnterior = $datos[1];
    $tipoTrabajadorAnterior = $datos[2];

    $sql = "INSERT INTO Movimiento(RFC,fecha,puesto,departamento,puestoAnterior,departamentoAnterior,observacion, elaboracion, tipoTrabajadorAnterior)
		VALUES('" . $RFC . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),'" . $puesto . "','" . $departamento . "', '" . $puestoAnterior . "',
		'" . $departamentoAnterior . "', '" . $observacion . "', STR_TO_DATE('" . $hoy . "','%d/%m/%Y'), '" . $tipoTrabajadorAnterior . "')";
    
        if (mysqli_query($conexion, $sql)) {

        // --------------------------------------------------------------------
        $sql = "UPDATE Plaza SET RFC = NULL WHERE id_plaza = " . $plazaAnterior[0];
        $consulta = mysqli_query($conexion, $sql);

        $sql = "UPDATE Plaza SET RFC = '".$RFC."' WHERE id_plaza = " . $plaza;
        $consulta = mysqli_query($conexion, $sql);

        $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '".$RFC."' ORDER BY elaboracion desc LIMIT 1";
        $consulta = mysqli_query($conexion, $sql);
        $historial = mysqli_fetch_row($consulta);

        $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('" . $fecha . "','%d/%m/%Y') WHERE id_historial_plaza = ".$historial[0];
        $consulta = mysqli_query($conexion, $sql);

        $sql = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC) 
        VALUES(".$plaza.", STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '".$RFC."')";
        $consulta = mysqli_query($conexion, $sql);
        // --------------------------------------------------------------------

        $sql = "UPDATE Usuario SET puesto = '" . $puesto . "', departamento = '" . $departamento . "', tipoTrabajador = '" . $trabajador . "'  WHERE RFC = '" . $RFC . "'";
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
