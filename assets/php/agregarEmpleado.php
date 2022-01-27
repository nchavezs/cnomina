<?php
include "conexion.php";

$id_empleado = trim($_POST["numero"]);
$RFC = mb_strtoupper(trim($_POST["rfc"]));
$CURP = mb_strtoupper(trim($_POST["curp"]));
$fechaRelLab = trim($_POST["ingreso"]);
$puesto = $_POST["puesto"];
$banca = trim($_POST["banca"]);
$afiliacion = trim($_POST["afiliacion"]);
$nombres = trim(ucwords(mb_strtolower($_POST["nombres"])));
$apellidom = trim(ucfirst(mb_strtolower($_POST["apellidom"])));
$apellidop = trim(ucfirst(mb_strtolower($_POST["apellidop"])));
$trabajador = $_POST["trabajador"];
$nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;
$password = str_pad($id_empleado, 5, '0', STR_PAD_LEFT);
$plaza = $_POST["plaza"];
$periodo = $_POST["periodo"];

$conexion = conexion();
mysqli_autocommit($conexion, false);
$errors = [];

$sql1 = "SELECT RFC FROM Usuario WHERE RFC = '" . $RFC . "'";

$sql2 = "INSERT INTO Empleado(
    id_empleado,
    RFC,
    CURP,
    fechaRelLab,
    id_puesto,
    banca,
    afiliacion,
    apellidop,
    apellidom,
    nombres,
    id_trabajador,
    id_periodo) VALUES(
    " . $id_empleado . ",
    '" . $RFC . "',
    '" . $CURP . "',
    '" . $fechaRelLab . "',
    " . $puesto . ",
    NULLIF('" . $banca . "', ''),
    NULLIF('" . $afiliacion . "',''),
    '" . $apellidop . "' ,
    '" . $apellidom . "',
    '" . $nombres . "',
    " . $trabajador . ",
    " . $periodo . "
)";

$sql3 = "INSERT INTO Usuario(categoria,contrasenia, nombre, RFC) VALUES(
    'user',
    '" . $password . "',
    '" . $nombreEmpleado . "',
    '" . $RFC . "'
)";

$sql4 = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $plaza;

$sql5 = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC)
VALUES(" . $plaza . ", STR_TO_DATE('" . $fechaRelLab . "','%d/%m/%Y'), '" . $RFC . "')";

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

if (count($errors) === 0) {
    $conexion->commit();
    echo 1;
} else {
    $conexion->rollback();
    echo 0;
    // print_r($errors);
}

mysqli_close($conexion);
