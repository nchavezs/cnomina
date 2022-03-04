<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$RFC = $_POST['id'];
$fecha = $_POST['fecha'];
$observacion = trim($_POST['observacion']);
$id_plaza = $_POST['plaza'];

$sql = "SELECT * FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE Usuario.RFC = '" . $RFC . "'";
$consulta = $conexion->query($sql);
$resultado = mysqli_fetch_array($consulta);
$fechaRelLab = $resultado['fechaRelLab'];

$sql = "SELECT id_puesto FROM Plaza WHERE id_plaza = ".$id_plaza;
$consulta = $conexion->query($sql);
$resultado = mysqli_fetch_array($consulta);
$id_puesto = $resultado['id_puesto'];

$conexion = conexion();
mysqli_autocommit($conexion, false);
$errors = [];

$sql1 = "UPDATE Usuario SET estado = 'alta' WHERE RFC = '" . $RFC . "'";

$sql2 = "UPDATE Empleado SET 
fechaRelLab = '" . $fechaRelLab . "',
id_puesto = ".$id_puesto." 
WHERE RFC = '" . $RFC . "'";

$sql3 = "INSERT INTO Reingreso(RFC, fecha, inicio, observacion,id_plaza) VALUES(
'" . $RFC . "',
STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),
STR_TO_DATE('" . $fechaRelLab . "','%d/%m/%Y'),
NULLIF('" . $observacion . "', ''),
" . $id_plaza . ")";

$sql4 = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $id_plaza;

$sql5 = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC)
VALUES(" . $id_plaza . ", STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), '" . $RFC . "')";

$sql6 = "INSERT INTO Historial(RFC,fecha,tipo,descripcion,id_prenomina) 
VALUES('" . $RFC . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'),'reingreso', '".$observacion."',".$id_prenomina.")";

if (!$conexion->query($sql1)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql2)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql3)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql4)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql5)) {
    $errors[] = $conexion->error;
}

if (!$conexion->query($sql6)) {
    $errors[] = $conexion->error;
}

if (count($errors) === 0) {
    $conexion->commit();
    echo 1;
} else {
    $conexion->rollback();
    echo 0;
}

$conexion->close();
