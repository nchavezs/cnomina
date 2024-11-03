<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$RFC = $_POST["id"];
$fecha = $_POST["fecha"];
$id_puesto = $_POST["puesto"];
$id_departamento = $_POST["departamento"];
$observacion = $_POST["observacion"];
$id_plaza = $_POST["plaza"];

$sql = "SELECT * FROM Plaza WHERE RFC = '" . $RFC . "'";
$consulta = $conexion->query($sql);
$plazaAnterior = 0;
if ($consulta && mysqli_num_rows($consulta) > 0) {
    $plazaAnterior = mysqli_fetch_array($consulta);
    $plazaAnterior = $plazaAnterior["id_plaza"];
}

$sql = "SELECT *,
(SELECT nombre FROM Trabajador WHERE id_trabajador = Puesto.id_trabajador) AS trabajador,
(SELECT nombre FROM Departamento WHERE id_departamento = Puesto.id_departamento) AS departamento 
FROM Puesto WHERE id_puesto = " . $id_puesto;

$consulta = $conexion->query($sql);
$puesto = mysqli_fetch_array($consulta);


$sql = "SELECT
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE id_trabajador =(SELECT id_trabajador FROM Puesto WHERE id_puesto =  Empleado.id_puesto)) AS trabajador 
FROM Empleado WHERE RFC = '" . $RFC . "'";

$consulta = $conexion->query($sql);
if ($consulta) {
    $usuario = mysqli_fetch_array($consulta);
    
    $puestoAnterior = $usuario["puesto"];
    $departamentoAnterior = $usuario["departamento"];
    $tipoTrabajadorAnterior = $usuario["trabajador"];

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
        '" . $puesto["nombre"]. "',
        '" . $puesto["departamento"] . "',
         '" . $puestoAnterior . "',
		'" . $departamentoAnterior . "',
        '" . $observacion . "',
        " . $id_plaza . ",
        " . $plazaAnterior . ",
        '" . $puesto["trabajador"] . "',
        '" . $tipoTrabajadorAnterior . "',
        ".$id_prenomina."
    )";

    $consulta = $conexion->query($sql);

    if ($consulta) {
        $movimiento = mysqli_insert_id($conexion);

        // --------------------------------------------------------------------
        $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql);

        $sql = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $id_plaza;
        $consulta = $conexion->query($sql);

        $sql = "SELECT * FROM Historial_Plaza WHERE RFC = '" . $RFC . "' ORDER BY elaboracion desc LIMIT 1";
        $consulta = $conexion->query($sql);

        if($consulta && mysqli_num_rows($consulta) > 0){
            $historial = mysqli_fetch_row($consulta);
            $fecha_modificada = date("d/m/Y", strtotime(str_replace('/', '-', $fecha) . " -1 day"));
            $sql = "UPDATE Historial_Plaza SET fecha_fin = STR_TO_DATE('$fecha_modificada', '%d/%m/%Y') WHERE id_historial_plaza = " . $historial[0];
            $consulta = $conexion->query($sql);
        }
       
        $sql = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC)
        VALUES(" . $id_plaza . ", STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '" . $RFC . "')";
        $consulta = $conexion->query($sql);
        // --------------------------------------------------------------------

        $sql = "UPDATE Empleado SET id_puesto = " . $id_puesto . " WHERE RFC = '" . $RFC . "'";

        if ($conexion->query($sql)) {
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

$conexion->close();
