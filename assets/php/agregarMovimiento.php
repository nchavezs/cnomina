<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$RFC = $_POST["id"];
$fecha = $_POST["fecha"];
// $fecha = date("d/m/Y");
$id_puesto = $_POST["puesto"];
$id_departamento = $_POST["departamento"];
$observacion = $_POST["observacion"];
$id_trabajador = $_POST["trabajador"];
$id_plaza = $_POST["plaza"];

$sql = "SELECT * FROM Plaza WHERE RFC = '" . $RFC . "'";
$consulta = mysqli_query($conexion, $sql);
$plazaAnterior = 0;
if ($consulta && mysqli_num_rows($consulta) > 0) {
    $plazaAnterior = mysqli_fetch_array($consulta);
    $plazaAnterior = $plazaAnterior["id_plaza"];
}

$sql = "SELECT
nombre AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = Puesto.id_departamento) AS departamento
FROM Puesto WHERE id_puesto = " . $id_puesto;

$consulta = mysqli_query($conexion, $sql);
$res = mysqli_fetch_array($consulta);
$puesto = $res["puesto"];
$departamento = $res["departamento"];

$sql = "SELECT nombre FROM Trabajador WHERE id_trabajador = " . $id_trabajador;

$consulta = mysqli_query($conexion, $sql);
$trabajador = mysqli_fetch_array($consulta);
$trabajador = $trabajador["nombre"];

$sql = "SELECT
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE id_trabajador = Empleado.id_trabajador) AS trabajador 
FROM Empleado WHERE RFC = '" . $RFC . "'";

$consulta = mysqli_query($conexion, $sql);
if ($consulta) {
    $datos = mysqli_fetch_array($consulta);
    $puestoAnterior = $datos["puesto"];
    $departamentoAnterior = $datos["departamento"];
    $tipoTrabajadorAnterior = $datos["trabajador"];

    $sql = "INSERT INTO Movimiento(
        RFC,
        fecha,
        puesto,
        departamento,
        puestoAnterior,
        departamentoAnterior,
        observacion,
        plaza,
        plazaAnterior,
        tipoTrabajador,
        tipoTrabajadorAnterior,
        id_prenomina
        )VALUES(
        '" . $RFC . "',
        STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),
        '" . $puesto . "',
        '" . $departamento . "',
         '" . $puestoAnterior . "',
		'" . $departamentoAnterior . "',
        '" . $observacion . "',
        " . $id_plaza . ",
        " . $plazaAnterior . ",
        '" . $trabajador . "',
        '" . $tipoTrabajadorAnterior . "',
        ".$id_prenomina."
    )";

    $consulta = mysqli_query($conexion, $sql);

    if ($consulta) {
        $movimiento = mysqli_insert_id($conexion);

        // --------------------------------------------------------------------
        $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $RFC . "'";
        $consulta = mysqli_query($conexion, $sql);

        $sql = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $id_plaza;
        $consulta = mysqli_query($conexion, $sql);

        $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '" . $RFC . "' ORDER BY elaboracion desc LIMIT 1";
        $consulta = mysqli_query($conexion, $sql);

        if($consulta && mysqli_num_rows($consulta) > 0){
            $historial = mysqli_fetch_row($consulta);
            $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('" . $fecha . "','%d/%m/%Y') WHERE id_historial_plaza = " . $historial[0];
            $consulta = mysqli_query($conexion, $sql);
        }
       
        $sql = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC)
        VALUES(" . $id_plaza . ", STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '" . $RFC . "')";
        $consulta = mysqli_query($conexion, $sql);
        // --------------------------------------------------------------------

        $sql = "UPDATE Empleado SET
        id_puesto = " . $id_puesto . ",
        id_trabajador = " . $id_trabajador . "
        WHERE RFC = '" . $RFC . "'";

        if (mysqli_query($conexion, $sql)) {
            echo $movimiento;
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
